@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-2xl p-8 bg-white rounded shadow-md">
            <h2 class="text-2xl font-bold text-center text-gray-800">NGO Feed</h2>
            <p class="mt-4 text-center text-gray-600">Welcome, {{ Auth::user()->name }}!</p>
            <div class="mt-6 space-y-4">
                <div class="p-4 bg-gray-50 border rounded-md">
                    <p class="text-gray-700">Sample NGO post or update.</p>
                    <span class="iconify inline text-red-500" data-icon="mdi:bullhorn"></span>
                </div>
                <a href="{{ route('logout') }}"
                    class="flex items-center justify-center px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700">
                    <span class="iconify mr-2" data-icon="mdi:logout"></span> Logout
                </a>
            </div>
        </div>
    </div>
@endsection
