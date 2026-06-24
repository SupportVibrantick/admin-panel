<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(){
        $productCategory = ProductCategory::where('status', 1)->get();
        $products = Product::with('category')
            ->where('status', 1)
            ->get()
            ->map(function ($product) {
                $firstImage = collect($product->images)->first();

                $product->image_url = $firstImage
                    ? asset('storage/' . $firstImage)
                    : null;

                return $product;
            });

        
        return response()->json([
            'products' => $products,
            'product_category' => $productCategory,
        ]);
    }
}