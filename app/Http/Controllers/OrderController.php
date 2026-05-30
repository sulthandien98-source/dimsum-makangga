<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Events\OrderCreated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function checkout()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('menu')
                ->with('error', 'Keranjang kosong!');
        }

        return view('pages.checkout', compact('cart'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        session(['checkout' => $request->only('name', 'phone', 'address')]);

        return redirect()->route('payment');
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT
    |--------------------------------------------------------------------------
    */

    public function payment()
    {
        if (!session('checkout')) {
            return redirect()->route('checkout');
        }

        if (empty(session('cart', []))) {
            return redirect()->route('menu');
        }

        return view('pages.payment');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE ORDER
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $cart     = session('cart', []);
        $checkout = session('checkout');

        if (empty($cart) || !$checkout) {
            return redirect()->route('menu');
        }

        DB::beginTransaction();

        try {
            $total = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);

            $order = Order::create([
    'user_id'       => Auth::id(),
    'customer_name' => $checkout['name'],
    'phone'         => $checkout['phone'],
    'address'       => $checkout['address'],
    'total_price'   => $total,

    'status'         => Order::STATUS_WAITING,
    'payment_status' => Order::PAYMENT_PENDING,
]);

            foreach ($cart as $productId => $item) {
                $product = Product::lockForUpdate()->findOrFail($productId);

                if (!$product->is_available) {
                    throw new \Exception("Produk {$product->name} tidak tersedia.");
                }

                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok {$product->name} tidak cukup.");
                }

                $product->decrement('stock', $item['qty']);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $productId,
                    'quantity'   => $item['qty'],
                    'price'      => $item['price'],
                ]);
            }

            DB::commit();

            event(new OrderCreated($order));

            session()->forget(['cart', 'checkout']);

            return redirect()
                ->route('payment.proof', $order->id)
                ->with('success', 'Pesanan berhasil dibuat! Silakan upload bukti pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT PROOF PAGE
    |--------------------------------------------------------------------------
    */

    public function paymentProof(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('pages.payment-proof', compact('order'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD PAYMENT PROOF
    |--------------------------------------------------------------------------
    */

    public function uploadPaymentProof(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ], [
            'payment_proof.required'  => 'Bukti pembayaran wajib diupload.',
            'payment_proof.file'      => 'File tidak valid.',
            'payment_proof.mimes'     => 'Format file harus JPG, JPEG, atau PNG.',
            'payment_proof.max'       => 'Ukuran file maksimal 2MB.',
        ]);

        $file = $request->file('payment_proof');

        // Validate file is actually an image (security check)
        $imageInfo = @getimagesize($file->getPathname());
        if (!$imageInfo) {
            return back()->withErrors(['payment_proof' => 'File harus berupa gambar yang valid.']);
        }

        // Generate unique filename
        $extension = $file->getClientOriginalExtension();
        $filename  = 'payment_' . time() . '_' . Str::random(8) . '.' . strtolower($extension);
        $destPath  = public_path('uploads/payments');

        // Ensure directory exists
        if (!file_exists($destPath)) {
            mkdir($destPath, 0755, true);
        }

        // Delete old proof if exists
        if ($order->payment_proof) {
            $oldPath = public_path($order->payment_proof);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        // Move file
        $file->move($destPath, $filename);

        // Save to database
        $order->update([
            'payment_proof' => 'uploads/payments/' . $filename,
        ]);

        return redirect()
            ->route('order.waiting', $order->id)
            ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }

    /*
    |--------------------------------------------------------------------------
    | WAITING PAGE
    |--------------------------------------------------------------------------
    */

    public function waiting(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('pages.waiting', compact('order'));
    }

    /*
    |--------------------------------------------------------------------------
    | USER ORDERS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pages.orders', compact('orders'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN ORDERS
    |--------------------------------------------------------------------------
    */

    public function adminOrders()
{
    $orders = Order::with([
        'user',
        'verifier',
        'rejecter',
    ])
    ->latest()
    ->paginate(20);

    return view('admin.orders.index', compact('orders'));
}

    public function show($id)
{
    $order = Order::with([
        'items.product',
        'user',
        'verifier',
        'rejecter',
    ])->findOrFail($id);

    return view('admin.orders.show', compact('order'));
}

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::STATUSES)),
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
