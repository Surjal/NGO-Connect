@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white border border-gray-200 rounded-lg p-8 mb-6">
        <h2 class="text-xl font-medium mb-8 text-center text-gray-900">Make a Donation</h2>
        <form action="{{ route('donations.payment.request') }}" class="space-y-6" method="post">
            @csrf
            <!-- NGO Selection -->
            <div>
                <label for="ngo_id" class="block text-sm font-medium text-gray-700 mb-2">Select NGO</label>
                <select name="ngo_id" id="ngo_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
                    @if($followedNgos->count() > 0)
                        <optgroup label="Followed NGOs">
                            @foreach ($followedNgos as $ngo)
                                <option value="{{ $ngo->id }}">{{ $ngo->name }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                    <optgroup label="Other NGOs">
                        @foreach ($otherNgos as $ngo)
                                <option value="{{ $ngo->id }}">{{ $ngo->name }}</option>
                        @endforeach
                    </optgroup>
                </select>
                @error('ngo_id')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Donation Amount -->
            <div>
                <label for="donation_amount" class="block text-sm font-medium text-gray-700 mb-2">Donation Amount
                    (NPR)</label>
                <input type="number" name="donation_amount" id="donation_amount" min="10" step="10"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400"
                    required>
                @error('donation_amount')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Payment Method -->
            <div>
                <span class="block text-sm font-medium text-gray-700 mb-3">Payment Method</span>
                <div class="grid grid-cols-2 gap-3">
                    <label
                        class="has-checked:bg-red-50 has-checked:text-red-900 has-checked:ring-red-200 flex items-center space-x-3 border border-gray-200 p-3 rounded-md cursor-pointer hover:border-gray-300 transition-colors">
                        <input type="radio" name="payment_method" value="esewa" class="checked:border-red-500">
                        <img src="https://cdn.esewa.com.np/ui/images/logos/esewa-icon-large.png" alt="eSewa"
                            class="h-5 w-5">
                        <span class="text-sm text-gray-700">eSewa</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button
                    class="w-full bg-red-600 hover:bg-red-800 cursor-pointer text-white py-2.5 px-4 rounded-md text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <i class="fas fa-donate mr-2"></i> Donate
                </button>
            </div>
        </form>
    </div>
@endsection
