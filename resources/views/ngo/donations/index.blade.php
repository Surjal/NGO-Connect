@extends('layouts.app')
@section('content')

    @if ($donations->isEmpty())
        <div class="text-center py-12">
            <div class="w-20 h-20 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-donate text-3xl text-red-300"></i>
            </div>
            <h3 class="text-lg font-black text-slate-900 mb-2">No Donations</h3>
            <p class="text-slate-500 text-sm font-medium">No donations have been received yet.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($donations as $donation)
                <div class="glass-panel p-5 flex items-start space-x-4 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-donate text-red-500"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-700">
                            {{ $donation->user->name }} donated NPR {{ $donation->donation_amount }}
                            <span
                                class="text-{{ $donation->status === 'completed' ? 'emerald' : ($donation->status === 'pending' ? 'amber' : 'rose') }}-600 text-xs font-bold">({{ ucfirst($donation->status) }})</span>
                        </p>
                        @if ($donation->payments->isNotEmpty())
                            <p class="text-sm text-slate-500 font-medium">Payment Method:
                                {{ ucfirst($donation->payments->first()->payment_method) }}</p>
                            <p class="text-sm text-slate-500 font-medium">Payment Status:
                                {{ ucfirst($donation->payments->first()->status) }}</p>
                        @endif
                        <p class="text-xs text-slate-400 font-medium mt-1">{{ $donation->created_at->diffForHumans() }}</p>
                        @if ($donation->status === 'pending' && $donation->payments->isNotEmpty() && in_array($donation->payments->first()->payment_method, ['cash', 'cheque']))
                            <form action="{{ route('ngo.donations.verify', $donation->id) }}" method="POST" class="mt-3">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn-primary py-2 px-4 text-sm">
                                    <i class="fas fa-check"></i> Verify Donation
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        {{ $donations->links() }}
    @endif

@endsection
