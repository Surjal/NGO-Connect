@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center space-x-4">
            <div class="w-20 h-20 rounded-lg overflow-hidden">
                @if ($ngo->logo)
                    <img src="{{ asset('storage/' . $ngo->logo) }}" alt="{{ $ngo->user->name }}"
                        class="w-full h-full object-cover">
                @else
                    <span class="iconify" data-icon="fluent:people-community-20-filled" data-width="80" data-height="80"
                        style="color: #6b7280;"></span>
                @endif
            </div>
            <div>
                <h2 class="text-xl font-medium">{{ $ngo->user->name }}</h2>
                @if ($ngo->mission)
                    <p class="text-gray-600">{{ $ngo->mission }}</p>
                @endif
                @if ($ngo->location)
                    <p class="text-gray-600">{{ $ngo->location }}</p>
                @endif
                <a href="{{ route('ngo.profile.edit') }}"
                    class="mt-2 inline-block px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                    <span class="iconify inline-block mr-2" data-icon="fluent:person-edit-20-filled" data-width="20"
                        data-height="20"></span>
                    Edit Profile
                </a>
            </div>
        </div>
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900">NGO Details</h3>
            <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="text-gray-900">{{ $ngo->user->name }}</p>
                </div>
                @if ($ngo->location)
                    <div>
                        <p class="text-sm text-gray-500">Location</p>
                        <p class="text-gray-900">{{ $ngo->location }}</p>
                    </div>
                @endif
                @if ($ngo->category)
                    <div>
                        <p class="text-sm text-gray-500">Category</p>
                        <p class="text-gray-900">{{ $ngo->category }}</p>
                    </div>
                @endif
                @if ($ngo->sub_categories)
                    <div>
                        <p class="text-sm text-gray-500">Sub Categories</p>
                        <p class="text-gray-900">{{ $ngo->sub_categories }}</p>
                    </div>
                @endif
                @if ($ngo->description)
                    <div class="sm:col-span-2">
                        <p class="text-sm text-gray-500">Description</p>
                        <p class="text-gray-900">{{ $ngo->description }}</p>
                    </div>
                @endif
            </div>
        </div>
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900">Analytics</h3>
            <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-500">Total Events Conducted</p>
                    <p class="text-gray-900">{{ $ngo->events()->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Followers</p>
                    <p class="text-gray-900">{{ $ngo->followers()->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Years of Operation</p>
                    <p class="text-gray-900">{{ now()->diffInYears($ngo->created_at) }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
