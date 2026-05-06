<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // Info rekening bank perusahaan
    private array $bankAccounts;
    public function __construct()
    {
        $this->bankAccounts = config('bearindo.bank_accounts');
    }

    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with(['items.product.brand', 'items.product.stock'])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Validasi stok sebelum checkout
        foreach ($cart->items as $item) {
            $stock = $item->product->stock?->quantity ?? 0;
            if ($item->quantity > $stock) {
                return redirect()->route('customer.cart.index')
                    ->with('error', 'Stok "'.$item->product->name.'" tidak mencukupi. Silakan sesuaikan jumlah.');
            }
        }

        $user         = auth()->user();
        $bankAccounts = $this->bankAccounts;
        $subtotal = $cart->items->sum(fn($i) => $i->price * $i->quantity);
        $tax      = round($subtotal * config('bearindo.tax_rate'));
        $total    = $subtotal + $tax;

    return view('customer.checkout.index', compact('cart', 'user', 'bankAccounts', 'subtotal', 'tax', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address'     => 'required|string',
            'shipping_city'        => 'required|string|max:100',
            'shipping_province'    => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:10',
            'shipping_phone'       => 'required|string|max:20',
            'notes'                => 'nullable|string|max:500',
            'bank_name'            => 'required|string',
            'account_number'       => 'required|string',
            'account_name'         => 'required|string',
        ]);

        $cart = Cart::where('user_id', auth()->id())
            ->with(['items.product.stock'])
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Re-validasi stok
        foreach ($cart->items as $item) {
            $stock = $item->product->stock?->quantity ?? 0;
            if ($item->quantity > $stock) {
                return redirect()->route('customer.cart.index')
                    ->with('error', 'Stok "'.$item->product->name.'" tidak mencukupi.');
            }
        }

        $order = DB::transaction(function () use ($validated, $cart) {
            $subtotal = $cart->items->sum(fn($i) => $i->price * $i->quantity);
            $tax      = round($subtotal * config('bearindo.tax_rate')); // ← PPN 11%
            $total    = $subtotal + $tax;

            // Buat order
            $order = Order::create([
                'order_number'         => 'CBI-'.date('Y').'-'.str_pad(Order::count() + 1, 5, '0', STR_PAD_LEFT),
                'user_id'              => auth()->id(),
                'subtotal'             => $subtotal,
                'tax'                  => $tax,
                'total'                => $total,
                'shipping_address'     => $validated['shipping_address'],
                'shipping_city'        => $validated['shipping_city'],
                'shipping_province'    => $validated['shipping_province'],
                'shipping_postal_code' => $validated['shipping_postal_code'],
                'shipping_phone'       => $validated['shipping_phone'],
                'notes'                => $validated['notes'] ?? null,
                'status'               => 'pending',
            ]);

            // Buat order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_sku'  => $item->product->sku,
                    'quantity'     => $item->quantity,
                    'price'        => $item->price,
                    'subtotal'     => $item->price * $item->quantity,
                ]);
            }

            // Buat payment record
            Payment::create([
                'order_id'       => $order->id,
                'payment_code'   => 'PAY-'.date('Y').'-'.str_pad($order->id, 5, '0', STR_PAD_LEFT),
                'amount'         => $total,
                'bank_name'      => $validated['bank_name'],
                'account_number' => $validated['account_number'],
                'account_name'   => $validated['account_name'],
                'status'         => 'pending',
            ]);

            // Kosongkan keranjang
            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat! Silakan upload bukti pembayaran.');
    }
}