<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Get *all* products for customers (no filtering by merchant).
        $products = Product::all();
        return response()->json($products);
    }
}
