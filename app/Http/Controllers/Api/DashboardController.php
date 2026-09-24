<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;

        $data = Cache::remember("dashboard:{$companyId}", now()->addMinutes(10), function () {
            return [
                'total_sales_month' => Sale::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('total'),
                'sales_count_month' => Sale::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'total_customers' => Customer::count(),
                'total_products' => Product::count(),
                'top_products' => Sale::with('saleItems.product')
                    ->get()
                    ->flatMap(fn ($sale) => $sale->saleItems)
                    ->groupBy('product_id')
                    ->map(fn ($items, $productId) => [
                        'product' => $items->first()->product->name,
                        'quantity_sold' => $items->sum('quantity'),
                    ])
                    ->sortByDesc('quantity_sold')
                    ->take(5)
                    ->values(),
            ];
        });

        return response()->json($data);
    }
}