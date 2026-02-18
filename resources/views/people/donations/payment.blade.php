<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSewa Payment Integration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-red-500 p-4">
            <h1 class="text-white text-xl font-bold text-center">eSewa Payment Gateway</h1>
        </div>

        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-gray-800 text-lg font-semibold mb-2">Order Summary</h2>
                <div class="border-t border-b border-gray-200 py-3">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">NGO</span>
                        <span class="font-medium">My NGO</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Amount</span>
                        <span class="font-medium">NPR <span id="displayAmount">{{$total}}</span></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-medium">NPR <span id="displayTax">10.00</span></span>
                    </div>
                </div>
                <div class="flex justify-between mt-3 font-bold">
                    <span class="text-gray-800">Total</span>
                    <span class="text-red-600">NPR <span id="displayTotal">{{$total+10}}</span></span>
                </div>
            </div>

            <!-- eSewa Payment Form -->
            <form id="esewaForm" action="{{ $esewa_url }}" method="POST">
                <!-- Hidden fields -->
                <input type="hidden" id="amount" name="amount" value="{{$donation_amount}}">
                <input type="hidden" id="tax_amount" name="tax_amount" value="10">
                <input type="hidden" id="total_amount" name="total_amount" value="{{$donation_amount + 10}}">
                <input type="hidden" id="transaction_uuid" name="transaction_uuid" value="{{ $transaction_uuid }}">
                <input type="hidden" id="product_code" name="product_code" value="{{ $product_code }}">
                <input type="hidden" id="product_service_charge" name="product_service_charge" value="0">
                <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0">
                <input type="hidden" name="success_url" value="{{ route('payment.success') }}">
                <input type="hidden" name="failure_url" value="{{ route('payment.fail') }}">
                <input type="hidden" id="signed_field_names" name="signed_field_names" 
                       value="total_amount,transaction_uuid,product_code">
                <input type="hidden" id="signature" name="signature" value="{{ $signature }}">
                
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded-md transition duration-300">
                    Pay with eSewa
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500">
                <p>You will be redirected to eSewa to complete your payment.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form submission handler
            document.getElementById('esewaForm').addEventListener('submit', function(e) {
                console.log('Redirecting to eSewa...');
            });
        });
    </script>
</body>
</html>