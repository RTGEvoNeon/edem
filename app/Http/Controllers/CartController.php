<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cart()
    {
        return view('cart');
    }

    /**
     * Актуальные данные товаров для корзины, которая хранится в браузере.
     */
    public function products(Request $request): JsonResponse
    {
        $ids = collect(explode(',', (string) $request->query('ids')))
            ->map(fn (string $id) => (int) $id)
            ->filter()
            ->unique()
            ->take(50);

        $products = Product::query()
            ->available()
            ->withImages()
            ->whereIn('id', $ids)
            ->get()
            ->map(fn (Product $product) => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'image' => $product->main_image,
            ]);

        return response()->json(['products' => $products->values()]);
    }
}
