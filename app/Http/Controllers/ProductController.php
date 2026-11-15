<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $category = $request->query('category');

        $query = Product::active();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Product::active()->distinct()->pluck('category')->filter()->values();

        return view('products.index', compact('products', 'categories', 'search', 'category'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): View
    {
        abort_if(! $product->is_active, 404);

        $relatedProducts = Product::active()
            ->where('id', '!=', $product->id)
            ->where('category', $product->category)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
