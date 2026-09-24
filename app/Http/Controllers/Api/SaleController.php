<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Http\Resources\SaleResource;
use App\Http\Requests\StoreSaleRequest;
use Illuminate\Support\Facades\DB;
use App\Events\SaleCreated;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        return SaleResource::collection(
            Sale::with(['customer', 'saleItems.product'])->latest()->paginate(15)
        );
    }

    public function store(StoreSaleRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $sale = Sale::create([
                'company_id' => $request->user()->company_id,
                'customer_id' => $request->customer_id,
                'user_id' => $request->user()->id,
                'status' => 'pending',
                'total' => 0,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $sale->saleItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                ]);
            }

            $sale->update(['total' => $total]);

            event(new SaleCreated($sale));

            return new SaleResource($sale->load(['customer', 'saleItems.product']));
        });
    }

    public function show(Sale $sale)
    {
        return new SaleResource($sale->load(['customer', 'saleItems.product']));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'status' => ['required', 'in:pending,completed,cancelled'],
        ]);

        $sale->update(['status' => $request->status]);

        return new SaleResource($sale);
    }
}
