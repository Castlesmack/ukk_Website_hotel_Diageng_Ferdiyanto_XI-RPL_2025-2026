<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Request a Midtrans Snap token (sandbox) using server key from env.
     * Expects JSON: { amount: 100000 }
     */
    public function token(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $clientKey = env('MIDTRANS_CLIENT_KEY');

        if (! $serverKey) {
            return response()->json(['error' => 'MIDTRANS_SERVER_KEY not configured in .env'], 400);
        }

        $amount = (int) $request->input('amount', 100000);
        $orderId = 'ORDER-' . time();

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $request->user()?->name ?? 'Guest',
                'email' => $request->user()?->email ?? $request->input('email', 'guest@example.com'),
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://app.sandbox.midtrans.com/snap/v1/transactions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($serverKey . ':'),
        ]);

        $resp = curl_exec($ch);
        $err = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) {
            return response()->json(['error' => 'Curl error: ' . $err], 500);
        }

        $data = json_decode($resp, true);
        if (! $data) {
            return response()->json(['error' => 'Invalid response from Midtrans', 'raw' => $resp], 502);
        }

        // Midtrans returns 'token' (snap_token) in response
        if (isset($data['token'])) {
            return response()->json(['token' => $data['token'], 'order_id' => $orderId, 'client_key' => $clientKey]);
        }

        return response()->json(['error' => 'Midtrans error', 'response' => $data], $code >= 400 ? $code : 500);
    }
}
