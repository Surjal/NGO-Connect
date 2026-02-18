@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center space-x-4">
            <div class="w-20 h-20 rounded-lg overflow-hidden">
                @if ($ngo->logo)
                    <img src="{{ asset('storage/' . $ngo->logo) }}" alt="{{ $ngo->name }}"
                        class="w-full h-full object-cover">
                @else
                    <span class="iconify" data-icon="fluent:people-community-20-filled" data-width="80" data-height="80"
                        style="color: #6b7280;"></span>
                @endif
            </div>
            <div>
                <h2 class="text-xl font-medium">{{ $ngo->name }}</h2>
                <p class="text-gray-600">{{ $ngo->user->name }}</p>
                <p class="text-gray-600">Mission: {{ $ngo->mission }}</p>
                <p class="text-gray-600">Location: {{ $ngo->location }}</p>
                <form action="{{ route('people.ngo.favorite', $ngo->id) }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="text-orange-500 hover:text-orange-600">
                        <span class="iconify inline-block mr-2"
                            data-icon="{{ auth()->user()->favoriteNgos()->where('ngo_id', $ngo->id)->exists() ? 'fluent:heart-20-filled' : 'fluent:heart-20-regular' }}"
                            data-width="20" data-height="20"></span>
                        {{ auth()->user()->favoriteNgos()->where('ngo_id', $ngo->id)->exists() ? 'Remove from Favorites' : 'Add to Favorites' }}
                    </button>
                </form>
            </div>
        </div>
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900">Details</h3>
            <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                @if ($ngo->category)
                    <div>
                        <p class="text-sm text-gray-500">Category</p>
                        <p class="text-gray-900">{{ $ngo->category }}</p>
                    </div>
                @endif
                @if ($ngo->sub_categories)
                    <div>
                        <p class="text-sm text-gray-500">Sub Category</p>
                        <p class="text-gray-900">{{ $ngo->sub_categories }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
