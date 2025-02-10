<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the request data.
        $validator = Validator::make($request->all(), [
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // 2. BEGIN the database transaction.
        DB::beginTransaction();
    
        try {
            $totalAmount = 0;
    
            // Create the order.
            $order = Order::create([
                'user_id' => auth()->user()->id,
                'total_amount' => 0,  // We'll update this later.
                'status' => 'pending',
            ]);
    
            // Process each item in the order.
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
    
                if ($product->stock < $item['quantity']) {
                    DB::rollBack(); // Rollback IMMEDIATELY if stock is insufficient.
                    return response()->json(['message' => "Not enough stock for product: {$product->name}"], 422);
                }
    
                $price = $product->price;
                $totalAmount += $price * $item['quantity'];
    
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ]);
    
                $product->decrement('stock', $item['quantity']);
            }
    
            // Update the total amount of the order.
            $order->update(['total_amount' => $totalAmount]);
    
            // Calculate and add reward points.
            $rewardPoints = floor($totalAmount / 10);
            $user = auth()->user();
            $user->update(['points' => $user->points + $rewardPoints]);
    
            // *** NO DB::commit() HERE ***
    
        } catch (\Exception $e) {
            // 3. If *ANYTHING* goes wrong inside the 'try', ROLLBACK the transaction.
            DB::rollBack();
            return response()->json(['message' => 'Order creation failed', 'error' => $e->getMessage()], 500);
        }
    
        // 4. If everything in the 'try' block succeeded, COMMIT the transaction.
        DB::commit();
        return response()->json($order, 201);
    }
     public function index()
    {
        $orders = Order::where('user_id', auth()->user()->id)->get();
        return response()->json($orders);
    }
}