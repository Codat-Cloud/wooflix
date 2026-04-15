<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        // 1. Fetch Product and Variant
        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant_id ? ProductVariant::findOrFail($request->variant_id) : null;

        // 2. Define Unique ID (Important for separating variations)
        $id = $variant ? 'v' . $variant->id : 'p' . $product->id;

        // 3. Add to Cart using the package API
        Cart::add([
            'id' => $id,
            'name' => $product->name . ($variant ? " - " . $variant->name : ""),
            'price' => $variant ? $variant->sale_price : $product->sale_price,
            'quantity' => 1,
            'attributes' => [
                'image' => $product->main_image,
                'product_id' => $product->id,
                'variant_id' => $request->variant_id,
                'sku' => $variant ? $variant->sku : $product->sku,
            ],
            'associatedModel' => $product
        ]);

        return response()->json([
            'status' => 'success',
            'cart_count' => Cart::getContent()->count(),
            'total' => Cart::getTotal(),
            'message' => 'Item added to cart!'
        ]);
    }
}
