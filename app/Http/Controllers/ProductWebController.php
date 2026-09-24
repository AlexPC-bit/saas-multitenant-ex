<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductWebController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        Product::create([
            'company_id' => $request->user()->company_id,
            ...$request->only(['name', 'description', 'price']),
        ]);

        return redirect()->route('products.index')->with('success', 'Produto criado!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produto removido.');
    }
}