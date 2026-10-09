<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Artisan;
use Cache;
use CoreComponentRepository;
use App\Services\ActivityLogger;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_dashboard(Request $request)
    {
        CoreComponentRepository::initializeCache();
        $root_categories = Category::where('level', 0)->get();

        // Live Dynamic Statistics
        $total_customers = \App\Models\User::where('user_type', 'customer')->count();
        $total_orders = \App\Models\Order::count();
        $total_categories = Category::count();
        $total_brands = \App\Models\Brand::count();
        $total_products = Product::count();
        $total_sales_amount = \App\Models\Order::where('payment_status', 'paid')->sum('grand_total');

        // Product Breakdown Stats
        $published_products = Product::where('published', 1)->count();
        $seller_products = Product::where('published', 1)->where('added_by', 'seller')->count();
        $admin_products = Product::where('published', 1)->where('added_by', 'admin')->count();

        // Seller Breakdown Stats
        $total_sellers = \App\Models\Shop::count();
        $approved_sellers = \App\Models\Shop::where('verification_status', 1)->count();
        $pending_sellers = \App\Models\Shop::where('verification_status', 0)->count();

        // Dynamic Category Performance Arrays
        $category_names = [];
        $category_sales = [];
        $category_stocks = [];

        foreach ($root_categories as $category) {
            $category_ids = \App\Utility\CategoryUtility::children_ids($category->id);
            $category_ids[] = $category->id;

            $products = Product::with('stocks')->whereIn('category_id', $category_ids)->get();
            $qty = 0;
            $sale = 0;
            foreach ($products as $product) {
                $sale += $product->num_of_sale;
                foreach ($product->stocks as $stock) {
                    $qty += $stock->qty;
                }
            }
            $category_names[] = $category->getTranslation('name');
            $category_sales[] = $sale;
            $category_stocks[] = $qty;
        }

        // Recent Orders
        $recent_orders = \App\Models\Order::orderBy('id', 'desc')->limit(6)->get();

        return view('backend.dashboard', compact(
            'root_categories',
            'total_customers',
            'total_orders',
            'total_categories',
            'total_brands',
            'total_products',
            'total_sales_amount',
            'published_products',
            'seller_products',
            'admin_products',
            'total_sellers',
            'approved_sellers',
            'pending_sellers',
            'category_names',
            'category_sales',
            'category_stocks',
            'recent_orders'
        ));
    }

    function clearCache(Request $request)
    {
        Artisan::call('optimize:clear');
        flash(translate('Cache cleared successfully'))->success();
        return back();
    }

    function runMigrationsPage(Request $request)
    {
        return view('backend.system.run_migrations');
    }

    function runMigrations(Request $request)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('This action is disabled in demo mode'))->error();
            return back();
        }

        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();

        app(ActivityLogger::class)->log('migrations_run', null, [
            'output' => Str::limit($output, 5000, ''),
        ], 'Migration command executed from admin panel');

        flash(translate('Migration command executed successfully'))->success();
        return back()->with('migration_output', $output);
    }

}
