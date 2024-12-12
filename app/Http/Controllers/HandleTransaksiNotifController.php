<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;

use Illuminate\Http\Request;

class HandleTransaksiNotifController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $payload = $request->all();

        
        $orderId = $payload['order_id'];
        $status = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];
        $midtransServerKey = config('midtrans.key');
        $signature = hash('sha512', $orderId.$status.$grossAmount.$midtransServerKey);
        $reqSignature = $payload['signature_key'];

        if ($signature != $reqSignature) {
            return response()->json([
                'message' => 'invalid signature'
            ], 401);
        }


        $transactionStatus = $payload['transaction_status'];

        if ($orderId) {
            $transaksi = Transaksi::where('order_id', $orderId)->first();
    
            if (!$transaksi) {
                return response()->json([
                    'message' => 'invalid order id'
                ], 400);
            }
        }

        if ($transactionStatus == 'settlement') {
            $transaksi->status = 'Paid';
            $transaksi->save();
        } else if ($transactionStatus == 'expire') {
            $transaksi->status = 'Expired';
            $transaksi->save();
        }

        return response()->json([
            'message' => 'success'
        ]);

    }
}
