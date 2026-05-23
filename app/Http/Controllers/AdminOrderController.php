<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | VERIFY PAYMENT
    |--------------------------------------------------------------------------
    */

    public function verify(int $id)
    {
        $order = Order::findOrFail($id);

        if (!$order->hasPaymentProof()) {
            return back()->with('error', 'Customer belum mengupload bukti pembayaran.');
        }

        if ($order->isPaid()) {
            return back()->with('error', 'Pembayaran sudah diverifikasi sebelumnya.');
        }

        $order->update([
            'payment_status'   => Order::PAYMENT_PAID,
            'status'           => Order::STATUS_DIPROSES,
            'verified_at'      => now(),
            'verified_by'      => Auth::id(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', "Pembayaran order #{$order->id} berhasil diverifikasi. Status diubah ke Diproses.");
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT PAYMENT (tolak bukti pembayaran)
    |--------------------------------------------------------------------------
    */

    public function reject(Request $request, int $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:5|max:500',
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.min'      => 'Alasan penolakan minimal 5 karakter.',
            'rejection_reason.max'      => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        $order = Order::findOrFail($id);

        if ($order->isPaid()) {
            return back()->with('error', 'Tidak dapat menolak pembayaran yang sudah terverifikasi.');
        }

        $order->update([
            'payment_status'   => Order::PAYMENT_REJECTED,
            'rejection_reason' => $request->rejection_reason,
            'verified_at'      => now(),
            'verified_by'      => Auth::id(),
        ]);

        return back()->with('success', "Pembayaran order #{$order->id} telah ditolak.");
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT ORDER (tolak pesanan — berbeda dari tolak pembayaran)
    |--------------------------------------------------------------------------
    */

    public function rejectOrder(Request $request, int $id)
    {
        $request->validate([
            'order_rejection_reason' => 'required|string|min:5|max:500',
        ], [
            'order_rejection_reason.required' => 'Alasan penolakan pesanan wajib diisi.',
            'order_rejection_reason.min'      => 'Alasan minimal 5 karakter.',
            'order_rejection_reason.max'      => 'Alasan maksimal 500 karakter.',
        ]);

        $order = Order::findOrFail($id);

        if ($order->isOrderRejected()) {
            return back()->with('error', 'Pesanan ini sudah ditolak sebelumnya.');
        }

        if ($order->isDone()) {
            return back()->with('error', 'Pesanan yang sudah selesai tidak dapat ditolak.');
        }

        $order->update([
            'status'                 => Order::STATUS_REJECTED,
            'rejection_reason'       => $request->order_rejection_reason,
            'rejected_at'            => now(),
            'rejected_by'            => Auth::id(),
        ]);

        return back()->with('success', "Pesanan #{$order->id} ({$order->customer_name}) berhasil ditolak.");
    }
}
