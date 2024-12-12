<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class TransaksiController extends Controller
{
    public function pay(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'id_tipe_transaksi' => 'required',
            'bank' => 'required|in:bca,bni'
            
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'invalid', 'data' => $validator->errors()]);
        }

        $tipetransaksi = DB::table('tipe_transaksi')->where('id', $request->id_tipe_transaksi)->first();
        if(!$tipetransaksi) {
            return response()->json(['message' => 'Jenis Transaksi tidak Ada', 'data' => ['id_tipe_transaksi' => ['not in database']]], 422);
        }

        try {
            DB::beginTransaction();
            $serverKey = config('midtrans.key');

            $orderId = Str::uuid()->toString();
            $grossAmount = $tipetransaksi->price;

            $response = Http::withBasicAuth($serverKey, '')
                ->post('https://api.sandbox.midtrans.com/v2/charge',[
                     'payment_type' => 'bank_transfer',
                     'transaction_details' => [
                            'order_id' => $orderId,
                            'gross_amount' => $grossAmount,
                        ],
                        'bank_transfer' => [
                           'bank' => $request->bank
                        ],
                        'customer_details' => [
                            'first_name' => $request->name,
                        ]
                ]);

                if($response->failed()) {
                    return response()->json(['message' => 'failed charge'], 500);
                }
                $result = $response->json();
                if ($result['status_code'] != 201) {
                    return response()->json(['message' => $result['statusmessage']], 500);
                }

                DB::table('transaksi')->insert([
                    'order_id' => $orderId,
                    'name' => $request->name,
                    'id_tipe_transaksi' => $request->id_tipe_transaksi,
                    // 'bank' => $request->bank,
                    'amount' => $grossAmount,
                    'va_number' => $result['va_numbers'][0]['va_number'],
                    'status' => 'Pending',
                    'created_at' => now(),

                ]);
                
                
                DB::commit();

                return response()->json([
                    'data' => [
                        'va' => $result['va_numbers'][0]['va_number'],
                    ]
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'error', 'data' => $e->getMessage()],500);
        }
    }
}
