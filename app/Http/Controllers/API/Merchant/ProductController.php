<?php

namespace App\Http\Controllers\API\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        // Get all products belonging to the authenticated merchant.
        $products = Product::where('merchant_id', auth()->user()->id)->get();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        // 1. Validate the request data.
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        // 2. Create the product and associate it with the logged-in merchant.
        $product = Product::create([
            'merchant_id' => auth()->user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return response()->json($product, 201); // 201 Created
    }

    public function show(Product $product)
    {
        // Check if the product belongs to the authenticated merchant.
        if ($product->merchant_id !== auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403); // 403 Forbidden
        }
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        // 1. Validate the request data.
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Check if the product belongs to the authenticated merchant.
        if ($product->merchant_id !== auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403); // 403 Forbidden
        }

        // 3. Update the product.
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        // Check if the product belongs to the authenticated merchant.
        if ($product->merchant_id !== auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403); // 403 Forbidden
        }

        $product->delete();
        return response()->json(null, 204); // 204 No Content (successful deletion)
    }
}