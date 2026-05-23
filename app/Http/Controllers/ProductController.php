<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PUBLIC MENU
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $products = Product::where('is_available', true)
            ->where('stock', '>', 0)
            ->latest()
            ->get();

        return view('pages.menu', compact('products'));
    }

    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */

    public function addToCart(Request $request)
    {
        $request->validate(['id' => 'required|integer|exists:products,id']);

        $product = Product::findOrFail($request->id);

        if ($product->stock <= 0 || !$product->is_available) {
            return response()->json(['error' => 'Produk tidak tersedia'], 400);
        }

        $cart = session()->get('cart', []);

        $currentQty = $cart[$product->id]['qty'] ?? 0;

        if ($currentQty >= $product->stock) {
            return response()->json(['error' => 'Stok tidak mencukupi'], 400);
        }

        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty']++;
        } else {
            $cart[$product->id] = [
                'name'  => $product->name,
                'price' => $product->price,
                'qty'   => 1,
            ];
        }

        session()->put('cart', $cart);

        return response()->json(['cart' => $cart, 'count' => array_sum(array_column($cart, 'qty'))]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'id'     => 'required|integer',
            'action' => 'required|in:plus,minus',
        ]);

        $cart    = session()->get('cart', []);
        $product = Product::find($request->id);

        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        if (isset($cart[$request->id])) {
            if ($request->action === 'plus') {
                if ($cart[$request->id]['qty'] >= $product->stock) {
                    return response()->json(['error' => 'Stok maksimal tercapai'], 400);
                }
                $cart[$request->id]['qty']++;
            } else {
                $cart[$request->id]['qty']--;
                if ($cart[$request->id]['qty'] <= 0) {
                    unset($cart[$request->id]);
                }
            }
        }

        session()->put('cart', $cart);

        return response()->json(['cart' => $cart, 'count' => array_sum(array_column($cart, 'qty'))]);
    }

    public function clearCart()
    {
        session()->forget('cart');
        return response()->json(['success' => true]);
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - PRODUCT MANAGEMENT
    |--------------------------------------------------------------------------
    */

    public function adminIndex()
    {
        $products = Product::latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        Product::create([
            'name'         => $request->name,
            'price'        => $request->price,
            'stock'        => $request->stock,
            'description'  => $request->description,
            'is_available' => true,
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'name'        => $request->name,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Produk berhasil diupdate.');
    }

    public function addStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:1|max:9999',
        ]);

        $product = Product::findOrFail($id);
        $product->increment('stock', $request->stock);

        return back()->with('success', "Stok +{$request->stock} berhasil ditambahkan.");
    }

    public function toggle($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_available' => !$product->is_available]);

        $status = $product->is_available ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Produk berhasil {$status}.");
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
