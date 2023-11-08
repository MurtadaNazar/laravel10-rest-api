<?php


namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::all();
        return response()->json($purchases);
    }

    public function show($id)
    {
        $purchase = Purchase::with('product')->findOrFail($id);
        return response()->json($purchase);
    }

    public function store(Request $request)
    {
        $purchaseData = $request->all();

        // Calculate total amount, total price, and product cost for each product
        $totalAmount = 0;
        $totalPrice = 0;

        $productsData = [];

        foreach ($purchaseData['products'] as $productItem) {
            $product = Product::findOrFail($productItem['product_id']);

            $productCost = $productItem['quantity'] * $product->price;
            $productItem['cost'] = $productCost;

            $totalAmount += $productItem['quantity'];
            $totalPrice += $productCost;

            // Update product quantity
            $product->decrement('quantity', $productItem['quantity']);

            array_push($productsData, $productItem);
        }

        $purchaseData['total_amount'] = $totalAmount;
        $purchaseData['total_price'] = $totalPrice;
        $purchaseData['products'] = json_encode($productsData);

        // Create a new purchase entry
        $purchase = Purchase::create($purchaseData);

        return response()->json($purchase, 201);
    }


    public function update(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->update($request->all());

        return response()->json($purchase, 200);
    }



    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);

        if (is_array($purchase->products)) {
            // Update product quantities when deleting a purchase
            foreach ($purchase->products as $productData) {
                $product = Product::findOrFail($productData['product_id']);
                $product->increment('quantity', $productData['quantity']);
            }
        }

        $purchase->delete();

        return response()->json('deleted successfully', 200);
    }
}
