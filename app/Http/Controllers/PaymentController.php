<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Villa;
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
        $orderId = $request->input('order_id', 'ORDER-' . time());
        $name = $request->input('name', 'Guest');
        $email = $request->input('email', 'guest@example.com');

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $name,
                'email' => $email,
            ],
        ];

        // Make curl request to Midtrans
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://app.sandbox.midtrans.com/snap/v1/transactions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($serverKey . ':'),
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $resp = curl_exec($ch);
        $err = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) {
            return response()->json(['error' => 'Curl error: ' . $err, 'code' => 500], 500);
        }

        $data = json_decode($resp, true);
        
        if (!$data) {
            return response()->json(['error' => 'Invalid response from Midtrans', 'raw' => $resp, 'http_code' => $code], 502);
        }

        // Check for Midtrans errors
        if (isset($data['status']) && $data['status'] >= 400) {
            return response()->json(['error' => $data['error_message'] ?? 'Midtrans error', 'response' => $data], $code >= 400 ? $code : 400);
        }

        // Return token and client key
        if (isset($data['token'])) {
            return response()->json([
                'token' => $data['token'],
                'redirect_url' => $data['redirect_url'] ?? null,
                'order_id' => $orderId,
                'client_key' => $clientKey
            ]);
        }

        return response()->json(['error' => 'No token in response', 'response' => $data], 500);
    }

    /**
     * Handle payment success - update reservation status to confirmed
     */
    public function success(Request $request)
    {
        $bookingCode = $request->input('booking_code');
        $status = $request->input('status', 'confirmed');

        if (!$bookingCode) {
            return redirect('/')->with('error', 'Invalid booking code');
        }

        $booking = Booking::where('booking_code', $bookingCode)->first();

        if (!$booking) {
            return redirect('/')->with('error', 'Booking not found');
        }
        
        // Verify villa is still available
        $villa = Villa::find($booking->villa_id);
        $inactiveStatuses = ['inactive', 'unavailable', 'maintenance'];
        if (!$villa || in_array($villa->status, $inactiveStatuses)) {
            return redirect('/')->with('error', 'Villa is no longer available.');
        }

        // Update payment status to paid and reservation status to confirmed
        $booking->update([
            'payment_status' => 'paid',
            'status' => 'confirmed'
        ]);

        return redirect('/')->with('success', 'Payment successful! Your reservation is confirmed.');
    }
}
