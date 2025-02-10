<?php

namespace App\Http\Controllers\API\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $merchant = auth()->user();
        // Get orders that contain at least one product belonging to the merchant.
        // This uses a "nested whereHas" for efficient querying.
        $orders = Order::whereHas('items.product', function ($query) use ($merchant) {
            $query->where('merchant_id', $merchant->id);
        })->with('items.product', 'user')->get(); // Eager load related data.

        return response()->json($orders);
    }

    public function show(Order $order)
    {
        $merchant = auth()->user();

        // Ensure the merchant owns at least one product in the order.
        $orderBelongsToMerchant = $order->items()->whereHas('product', function ($query) use ($merchant) {
            $query->where('merchant_id', $merchant->id);
        })->exists();

        if (!$orderBelongsToMerchant) {
            return response()->json(['message' => 'Unauthorized'], 403); // 403 Forbidden
        }
        $order->load('items.product', 'user'); //load detail order, product and user

        return response()->json($order);
    }
}