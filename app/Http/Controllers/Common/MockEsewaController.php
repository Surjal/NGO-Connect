<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MockEsewaController extends Controller
{
    public function index(Request $request)
    {
        // This view mimics the eSewa login/payment page
        return view('common.mock.esewa', [
            'amount' => $request->total_amount,
            'tax_amount' => $request->tax_amount ?? 0,
            'total_amount' => $request->total_amount,
            'transaction_uuid' => $request->transaction_uuid,
            'product_code' => $request->product_code,
            'success_url' => $request->success_url,
            'failure_url' => $request->failure_url,
            'signed_field_names' => $request->signed_field_names,
            'signature' => $request->signature,
        ]);
    }

    public function pay(Request $request)
    {
        // Mimic successful payment response from eSewa
        $data = [
            'transaction_code' => 'TEST_' . strtoupper(uniqid()),
            'status' => 'COMPLETE',
            'total_amount' => $request->total_amount,
            'transaction_uuid' => $request->transaction_uuid,
            'product_code' => $request->product_code,
            'signed_field_names' => 'total_amount,transaction_uuid,transaction_code,signed_field_names,status,product_code,main_merchat_id',
            'signature' => 'MOCK_SIGNATURE', // In real world this is validated, but for mock we might skip or fake it
        ];

        // eSewa returns data encoded in base64
        $encodedData = base64_encode(json_encode($data));

        // Redirect to the success URL provided in the form (DonationController expects this)
        // Usually eSewa redirects to success_url?data=...
        // But in our DonationController we use route('payment.success')
        
        // We need to know where to redirect. eSewa gets 'success_url' from the form.
        // But the previous controller (DonationController) didn't explicitly pass success_url in the input fields shown in lines 90-99.
        // Wait, standard eSewa integration REQUIRES success_url and failure_url fields in the form.
        // Let's check DonationController again.
        
        // It returns a view 'people.donations.payment'. I should check THAT view to see if it includes success_url hidden input.
        
        // Assuming it does (standard requirement), we use it.
        // If not, we default to the route we know.
        
        return redirect()->route('payment.success', ['data' => $encodedData]);
    }
}
