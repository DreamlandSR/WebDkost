<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index(Request $request)
{
    $query = Payment::with(['order.user']);

    // Filter status
    if ($request->filled('status')) {
        $query->where('status_pembayaran', $request->status);
    }

    // Filter berdasarkan nama pembeli
    if ($request->has('search') && $request->search !== '') {
        $query->whereHas('order.user', function ($q) use ($request) {
            $q->where('nama', 'like', '%' . $request->search . '%');
        });
    }

    $payments = $query->paginate(5)->withQueryString();

    return view('dashboard.payment', compact('payments'));
}

}
