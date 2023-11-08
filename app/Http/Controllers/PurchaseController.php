<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('products')->get();
        return response()->json($purchases);
    }

    public function show(Purchase $purchase)
    {
        return response()->json($purchase->load('products'));
    }

    public function store(Request $request)
    {
        $request->validate($this->getValidationRules());

        $purchase = Purchase::create($request->only(['customer_name', 'total_amount']));

        $this->attachProducts($purchase, $request->input('products'));

        return response()->json(['message' => 'Purchase created successfully'], 201);
    }

    public function update(Request $request, Purchase $purchase)
    {
        $request->validate($this->getValidationRules());

        $purchase->update($request->only(['customer_name', 'total_amount']));

        $purchase->products()->delete();
        $this->attachProducts($purchase, $request->input('products'));

        return response()->json(['message' => 'Purchase updated successfully']);
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->products()->delete();
        $purchase->delete();

        return response()->json(['message' => 'Purchase deleted successfully']);
    }

    private function getValidationRules()
    {
        return [
            'customer_name' => 'required|string',
            'total_amount' => 'required|numeric',
            'products' => 'required|array',
            'products.*.name' => 'required|string',
            'products.*.quantity' => 'required|integer',
            'products.*.price' => 'required|numeric',
            'products.*.cost' => 'required|numeric',
        ];
    }

    private function attachProducts(Purchase $purchase, array $productsData)
    {
        foreach ($productsData as $productData) {
            $product = new Product($productData);
            $purchase->products()->save($product);
        }
    }
}
