<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSewa Mock Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-[#F5F9FA] h-screen flex items-center justify-center font-sans">

    <div class="bg-white w-full max-w-sm rounded-[2rem] shadow-2xl overflow-hidden relative">
        <!-- Header -->
        <div class="bg-[#60BB46] p-6 text-center relative overflow-hidden">
            <div class="relative z-10">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                    <img src="https://esewa.com.np/common/images/esewa_logo.png" alt="eSewa" class="h-8 object-contain">
                </div>
                <h2 class="text-white font-bold text-xl">Mock Payment Gateway</h2>
                <p class="text-white/80 text-sm font-medium">Test Environment</p>
            </div>
            
            <!-- Decor -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        </div>

        <!-- content -->
        <div class="p-8">
            <div class="text-center mb-8">
                <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Total Amount</p>
                <h1 class="text-4xl font-black text-gray-800">
                    <span class="text-base align-top text-gray-400 font-bold mr-1">NPR</span>{{ number_format($total_amount, 2) }}
                </h1>
            </div>

            <form action="{{ route('mock.esewa.pay') }}" method="POST" class="space-y-4">
                @csrf
                <!-- Pass through all the data -->
                <input type="hidden" name="total_amount" value="{{ $total_amount }}">
                <input type="hidden" name="transaction_uuid" value="{{ $transaction_uuid }}">
                <input type="hidden" name="product_code" value="{{ $product_code }}">
                <input type="hidden" name="success_url" value="{{ $success_url }}">
                <input type="hidden" name="failure_url" value="{{ $failure_url }}">
                
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">eSewa ID / Mobile</label>
                    <div class="relative">
                        <i class="fas fa-mobile-alt absolute left-4 top-3.5 text-gray-400"></i>
                        <input type="text" value="9806800001" readonly
                            class="w-full bg-gray-50 border-2 border-gray-100 text-gray-800 text-sm font-bold rounded-xl focus:ring-[#60BB46] focus:border-[#60BB46] block pl-10 p-3 outline-none transition-colors">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Password / MPIN</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400"></i>
                        <input type="password" value="1122" readonly
                            class="w-full bg-gray-50 border-2 border-gray-100 text-gray-800 text-sm font-bold rounded-xl focus:ring-[#60BB46] focus:border-[#60BB46] block pl-10 p-3 outline-none transition-colors">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" 
                        class="w-full bg-[#60BB46] hover:bg-[#4CA035] text-white font-bold rounded-xl text-sm px-5 py-4 text-center transition-all shadow-lg hover:shadow-[#60BB46]/40 flex items-center justify-center gap-2">
                        <span>CONFIRM PAYMENT</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    
                    <a href="{{ $failure_url }}" class="block w-full text-center py-3 text-red-500 text-sm font-bold hover:bg-red-50 rounded-xl transition-colors mt-2">
                        CANCEL TRANSACTION
                    </a>
                </div>
            </form>
        </div>
        
        <div class="bg-gray-50 p-4 text-center border-t border-gray-100">
            <p class="text-xs text-gray-400 font-medium">Development Mode • Mock Payment</p>
        </div>
    </div>

</body>
</html>
