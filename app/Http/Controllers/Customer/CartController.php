<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getOrCreateCart()
    {
        return Cart::firstOrCreate(['user_id' => auth()->id()]);
    }

    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with(['items.product.brand', 'items.product.stock'])
            ->first();

        return view('customer.cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:999',
        ]);

        $product = Product::with('stock')->findOrFail($request->product_id);

        if (!$product->is_active) {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        $stock = $product->stock?->quantity ?? 0;

        $cart     = $this->getOrCreateCart();
        $existing = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $product->id)
                            ->first();

        $currentQty  = $existing?->quantity ?? 0;
        $requestedQty = $currentQty + $request->quantity;

        if ($requestedQty > $stock) {
            return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: '.$stock.' pcs.');
        }

        if ($existing) {
            $existing->update(['quantity' => $requestedQty]);
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
                'price'      => $product->price,
            ]);
        }

        return back()->with('success', '"'.$product->name.'" berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, CartItem $item)
    {
        abort_if($item->cart->user_id !== auth()->id(), 403);

        $request->validate(['quantity' => 'required|integer|min:1|max:999']);

        $stock = $item->product->stock?->quantity ?? 0;

        if ($request->quantity > $stock) {
            return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: '.$stock.' pcs.');
        }

        $item->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove(CartItem $item)
    {
        abort_if($item->cart->user_id !== auth()->id(), 403);
        $item->delete();
        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function clear()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        $cart?->items()->delete();
        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }
}