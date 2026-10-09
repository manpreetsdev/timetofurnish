<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Models\Product;
use App\Services\ProductFlashDealService;
use App\Services\ProductService;
use App\Services\ProductStockService;
use App\Services\ProductTaxService;
use Illuminate\Http\Request;

/**
 * Admin create/edit product screens, sharing the seller panel form and save logic.
 */
class ProductFormController extends SellerProductController
{
    public function __construct(
        ProductService $productService,
        ProductTaxService $productTaxService,
        ProductFlashDealService $productFlashDealService,
        ProductStockService $productStockService
    ) {
        parent::__construct($productService, $productTaxService, $productFlashDealService, $productStockService);

        $this->middleware(['permission:add_new_product'])->only('create', 'store');
        $this->middleware(['permission:product_edit'])->only('edit', 'update');
    }

    public function edit(Request $request, $id)
    {
        if (Product::findOrFail($id)->digital == 1) {
            return redirect('admin/digitalproducts/' . $id . '/edit');
        }

        return parent::edit($request, $id);
    }

    protected function isSellerPanel(): bool
    {
        return false;
    }

    protected function formView(string $page): string
    {
        return 'backend.product.products.form.' . $page;
    }

    protected function productsIndexUrl(): string
    {
        return route('products.all');
    }

    protected function canManageProduct(Product $product): bool
    {
        return true;
    }

    protected function storedMessage(): string
    {
        return translate('Product has been inserted successfully');
    }
}
