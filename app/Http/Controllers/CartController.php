<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Auth;
use App\Utility\CartUtility;
use Session;
use Cookie;
use App\Models\ProductAddon;
use App\Models\ProductAddonOption;

class CartController extends Controller
{
    public function savePendingSelection(Request $request)
    {
        $data = $request->except('_token');

        if (empty($data['id'])) {
            return response()->json(['status' => 0]);
        }

        Session::put('pending_cart_selection', $data);

        return response()->json(['status' => 1]);
    }

    public function addPendingSelectionToCart(): bool
    {
        if (!auth()->check() || auth()->user()->user_type != 'customer' || !Session::has('pending_cart_selection')) {
            return false;
        }

        $pendingSelection = Session::pull('pending_cart_selection');

        if (empty($pendingSelection['id']) || empty($pendingSelection['quantity'])) {
            return false;
        }

        $response = $this->addToCart(new Request($pendingSelection));

        return is_array($response) && (int) ($response['status'] ?? 0) === 1;
    }

    public function index(Request $request)
    {
        if (Session::has('edit_cart_item_id')) {
            Session::forget('edit_cart_item_id');
        }

        $expired_carts = collect();

        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            if ($request->session()->get('temp_user_id')) {
                Cart::withExpiredReservations()->where('temp_user_id', $request->session()->get('temp_user_id'))
                    ->update(
                        [
                            'user_id' => $user_id,
                            'temp_user_id' => null
                        ]
                    );

                Session::forget('temp_user_id');
            }
            $carts = Cart::with(['product.thumbnail', 'product.stocks', 'product.taxes'])
                ->whereHas('product', function($q) {
                    $q->isApprovedPublished();
                })->where('user_id', $user_id)->get();

            $expired_carts = Cart::expiredReservations()
                ->with(['product.thumbnail', 'product.stocks', 'product.taxes'])
                ->whereHas('product', function($q) {
                    $q->isApprovedPublished();
                })
                ->where('user_id', $user_id)
                ->latest('reserved_until')
                ->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = ($temp_user_id != null)
                ? Cart::with(['product.thumbnail', 'product.stocks', 'product.taxes'])
                    ->whereHas('product', function($q) {
                    $q->isApprovedPublished();
                })->where('temp_user_id', $temp_user_id)->get()
                : collect();

            if ($temp_user_id != null) {
                $expired_carts = Cart::expiredReservations()
                    ->with(['product.thumbnail', 'product.stocks', 'product.taxes'])
                    ->whereHas('product', function($q) {
                        $q->isApprovedPublished();
                    })
                    ->where('temp_user_id', $temp_user_id)
                    ->latest('reserved_until')
                    ->get();
            }
        }

        sync_cart_prices($carts);
        return view('frontend.view_cart', compact('carts', 'expired_carts'));
    }

    public function showCartModal(Request $request)
    {
        $product = Product::with(['thumbnail', 'stocks', 'taxes'])->find($request->id);
        return view('frontend.' . get_setting('homepage_select') . '.partials.addToCart', compact('product'));
    }

    public function showCartModalAuction(Request $request)
    {
        $product = Product::with(['thumbnail', 'stocks', 'taxes'])->find($request->id);
        return view('auction.frontend.addToCartAuction', compact('product'));
    }

    public function addToCart(Request $request)
    {
        if (!auth()->check() || auth()->user()->user_type !== 'customer') {
            return response()->json(['status' => 0, 'message' => translate('Please login as a customer to add products to the cart.')], 403);
        }

        $userId = auth()->id();
        $carts = Cart::where('user_id', $userId)->get();
        $check_auction_in_cart = CartUtility::check_auction_in_cart($carts);
        $product = Product::with(['thumbnail', 'stocks', 'taxes'])->find($request->id);
        $carts = array();

        if (!$product) {
            return response()->json(['status' => 0, 'message' => translate('Product not found.')], 404);
        }

        if ($check_auction_in_cart && $product->auction_product == 0) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.' . get_setting('homepage_select') . '.partials.removeAuctionProductFromCart')->render(),
                'nav_cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart')->render(),
            );
        }

        $quantity = $request['quantity'];

        if ($quantity < $product->min_qty) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.' . get_setting('homepage_select') . '.partials.minQtyNotSatisfied', ['min_qty' => $product->min_qty])->render(),
                'nav_cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart')->render(),
            );
        }

        //check the color enabled or disabled for the product
        $str = CartUtility::create_cart_variant($product, $request->all());

        $product_stock = $product->stocks->where('variant', $str)->first();

        $is_update = false;
        if ($request->has('cart_item_id') && !empty($request->cart_item_id)) {
            $cart = Cart::withExpiredReservations()->find($request->cart_item_id);
            if ($cart && $cart->user_id == $userId && $cart->product_id == $product->id) {
                $cart->variation = $str;
                $is_update = true;
            }
        }

        if (!$is_update) {
            $cart = Cart::firstOrNew([
                'variation' => $str,
                'user_id' => $userId,
                'product_id' => $request['id']
            ]);
        }

        // A product page represents the customer's current intended setup.
        // Re-submitting the same product/variant replaces its quantity and
        // selected add-ons; it never silently adds the amount a second time.
        // This makes repeated clicks predictable and prevents false
        // out-of-stock errors such as 7 + 7 being tested against stock of 10.
        $cart_was_updated = $cart->exists;
        $quantity = (int) $request['quantity'];

        // Validate the desired final quantity against the exact selected stock.
        if ($product->digital == 0 && $product->auction_product == 0
            && $quantity > cart_available_qty(['variation' => $str], $product)) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.' . get_setting('homepage_select') . '.partials.outOfStockCart')->render(),
                'nav_cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart')->render(),
            );
        }

        // Full base price for ALL selected variant attributes (after discount).
        // Uses the exact combined stock row when present, otherwise sums every
        // individual attribute stock row — same logic as the product page's
        // variant_price endpoint. Addons are still stored separately.
        if ($product->wholesale_product && $product_stock) {
            // Wholesale needs the concrete stock row for tiered pricing
            $price = CartUtility::get_price($product, $product_stock, $request->quantity);
        } else {
            $price = CartUtility::get_variant_price($product, $request->all(), $request->quantity);
        }

        //shivani  (addon code)
        $addons = [];
        $addon_total = 0;

        if ($request->has('addons')) {

            foreach ($request->addons as $addon_id => $option_ids) {

                // Get addon (e.g. "Assembly Required")
                $addon = ProductAddon::find($addon_id);

                if (!$addon) continue;

                if (!is_array($option_ids) && !is_object($option_ids)) {
                    $option_ids = !empty($option_ids) ? [$option_ids] : [];
                }
                foreach ($option_ids as $option_id) {

                    // Get selected option (e.g. "Yes")
                    $option = ProductAddonOption::find($option_id);

                    if (!$option) continue;

                    $optionDetails = get_product_addon_option_details($option);
                    $resolvedAddonPrice = (float) ($optionDetails['price'] ?? $option->price ?? 0);

                    $addons[] = [
                        'addon_id'   => $addon->id,
                        'addon_name' => $addon->name,          // "Assembly Required"
                        'name'       => $option->option_name,  // "Yes"
                        // Persist the same resolved value used to render the
                        // product option. This keeps Fabric and every other
                        // add-on price identical in product, cart and modal.
                        'price'      => $resolvedAddonPrice,
                        'image'      => $optionDetails['image'] ?? '',
                    ];
                }
            }

            $addon_total = collect($addons)->sum('price');
            // Store addon data on the cart model — saved by save_cart_data below
            $cart->addon_price = $addon_total;
            $cart->addons = json_encode($addons);
        } else {
            // No addons submitted — clear previous addon data
            $cart->addon_price = 0;
            $cart->addons = null;
        }

        // Tax is calculated on the combined total (base + addons) for accuracy
        $tax = CartUtility::tax_calculation($product, $price + $addon_total);

        // (Re)start the 1-hour inventory hold for this line. Physical products
        // only — digital/auction items are not stock-limited.
        if (\App\Models\Cart::reservationEnabled() && $product->digital == 0 && $product->auction_product == 0) {
            $cart->reserved_until = now()->addMinutes(\App\Models\Cart::RESERVATION_MINUTES);
        }

        // save_cart_data saves $price as the BASE price only (addons in addon_price column)
        CartUtility::save_cart_data($cart, $product, $price, $tax, $quantity);



        Session::forget('edit_cart_item_id');
        $carts = Cart::where('user_id', $userId)->get();
        return array(
            'status' => 1,
            'cart_count' => count($carts),
            'modal_view' => view('frontend.' . get_setting('homepage_select') . '.partials.addedToCart', compact('product', 'cart', 'cart_was_updated'))->render(),
            'nav_cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart')->render(),
        );
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        // include expired-reservation lines so their "Remove" button works
        $cartToRemove = Cart::withExpiredReservations()->whereKey($request->id);
        if (auth()->check()) {
            $cartToRemove->where('user_id', auth()->id());
        } else {
            $cartToRemove->where('temp_user_id', $request->session()->get('temp_user_id'));
        }
        $cartToRemove->delete();
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::whereHas('product', function($q) {
                $q->isApprovedPublished();
            })->where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = ($temp_user_id != null)
                ? Cart::whereHas('product', function($q) {
                    $q->isApprovedPublished();
                })->where('temp_user_id', $temp_user_id)->get()
                : collect();
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart_details', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart')->render(),
        );
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cartItem = Cart::findOrFail($request->id);

        if (!auth()->check() || $cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        if ($cartItem['id'] == $request->id) {
            $product = Product::find($cartItem['product_id']);
            $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
            $cartItem['discount'] = 0;
            $cartItem['coupon_applied'] = 0;
            $cartItem['coupon_code'] = '';

            // Available quantity (combined row or min of individual attribute
            // rows), already net of other shoppers' active reservations.
            $quantity = cart_available_qty($cartItem, $product);

            // Clamp the requested quantity between min_qty and what is available
            // instead of silently ignoring it (so the -/+ buttons always work,
            // and lowering the quantity is never blocked by a stock shortfall).
            $min_qty = max(1, (int) $product->min_qty);
            $available = max($min_qty, (int) $quantity);
            $requested = (int) $request->quantity;
            if ($requested < $min_qty) {
                $requested = $min_qty;
            }
            if ($requested > $available) {
                $requested = $available;
            }
            $cartItem['quantity'] = $requested;

            // Editing the line renews this shopper's 1-hour hold.
            if (\App\Models\Cart::reservationEnabled() && $product->digital == 0 && $product->auction_product == 0) {
                $cartItem->reserved_until = now()->addMinutes(\App\Models\Cart::RESERVATION_MINUTES);
            }

            // Base price = sum of each selected attribute's own option price
            // (same rule as the product page and checkout). Already discounted.
            $price = cart_product_price($cartItem, $product, false, false);

            if ($product->wholesale_product && $product_stock) {
                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
                if ($wholesalePrice) {
                    $price = $wholesalePrice->price;
                }
            }
            $tax = CartUtility::tax_calculation(0, $price);

            $cartItem['tax'] = $tax;

            $cartItem['price'] = $price;
            $cartItem->save();
        }

        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::whereHas('product', function($q) {
                $q->isApprovedPublished();
            })->where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = ($temp_user_id != null)
                ? Cart::whereHas('product', function($q) {
                    $q->isApprovedPublished();
                })->where('temp_user_id', $temp_user_id)->get()
                : collect();
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart_details', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart')->render(),
        );
    }

    public function editItem(Request $request, $id)
    {
        $cartItem = Cart::find($id);
        if ($cartItem) {
            if (auth()->check() && $cartItem->user_id == auth()->user()->id) {
                Session::put('edit_cart_item_id', $id);
                $product = Product::find($cartItem->product_id);
                if ($product) {
                    return redirect()->route('product', $product->slug);
                }
            }
        }
        return redirect()->route('cart');
    }
}
