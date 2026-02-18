@extends('layouts.app')
@section('content')
    @if ($ngos->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-building text-6xl text-gray-300 mx-auto block mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No NGOs</h3>
            <p class="text-gray-600">No NGOs have registered yet.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($ngos as $ngo)
                <div class="p-4 bg-white rounded-lg shadow hover:bg-gray-50 flex items-start space-x-3">
                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                        @php
                            $photos = is_string($ngo->ngo->photos)
                                ? json_decode($ngo->ngo->photos, true)
                                : $ngo->ngo->photos;
                        @endphp

                        @if ($photos && count($photos) > 0)
                            <img src="{{ Storage::url($photos[0]) }}" alt="{{ $ngo->name }}"
                                class="w-full h-full object-cover rounded-full">
                        @else
                            <i class="fas fa-building text-gray-500"></i>
                        @endif

                    </div>
                    <div class="flex-1">
                        <a href="{{ route('admin.ngos.show', $ngo->id) }}"
                            class="text-sm text-red-500 hover:underline">{{ $ngo->name }} ({{ $ngo->email }})</a>
                        <p class="text-sm text-gray-500">Category:
                            {{ $ngo->ngo ? ucfirst($ngo->ngo->category) : 'N/A' }}</p>
                        <p class="text-sm text-gray-500">Status: {{ $ngo->verified ? 'Verified' : 'Pending' }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $ngo->created_at->diffForHumans() }}</p>
                        @if (!$ngo->verified)
                            <div class="mt-2 flex space-x-2">
                                <form action="{{ route('admin.ngos.verify', $ngo->id) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm">
                                        <i class="fas fa-check mr-2"></i> Verify
                                    </button>
                                </form>
                                <button type="button"
                                    class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm"
                                    onclick="$('#rejectModal{{ $ngo->id }}').removeClass('hidden')">
                                    <i class="fas fa-times mr-2"></i> Reject
                                </button>
                                <!-- Rejection Modal -->
                                <div id="rejectModal{{ $ngo->id }}"
                                    class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                                    <div class="bg-white rounded-lg p-6 w-full max-w-md">
                                        <h2 class="text-lg font-bold mb-4">Reject NGO</h2>
                                        <form action="{{ route('admin.ngos.reject', $ngo->id) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <div class="mb-4">
                                                <label for="rejection_reason"
                                                    class="block text-sm font-medium text-gray-700 mb-2">Rejection
                                                    Reason</label>
                                                <textarea name="rejection_reason" id="rejection_reason" rows="4"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400"
                                                    required></textarea>
                                                @error('rejection_reason')
                                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="flex space-x-2">
                                                <button type="submit"
                                                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                                    <i class="fas fa-times mr-2"></i> Confirm Rejection
                                                </button>
                                                <button type="button"
                                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400"
                                                    onclick="$('#rejectModal{{ $ngo->id }}').addClass('hidden')">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        {{ $ngos->links() }}
    @endif
@endsection