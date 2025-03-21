<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index()
    {
        // Exemple d'ID de commande
        $orderId = 10; // Remplacez ceci par l'ID de commande réel que vous souhaitez utiliser
        // Remplacez 'orders_id' par 'order_id'
        $payments = Payments::where('order_id', $orderId)->get();

        return view('payments.index', compact('payments'));
    }
    public function processCashPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $payment = Payments::createCashPayment($request->order_id, $request->amount);

        return response()->json(['message' => 'Payment processed successfully', 'payment' => $payment]);
    }
}
