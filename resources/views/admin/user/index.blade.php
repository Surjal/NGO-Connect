@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-8">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Create New User</h1>

        <!-- Success message -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-red-200">
                @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-red-200">
                @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-red-200">
                @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-red-200">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo (optional)</label>
                <input type="file" name="profile_photo" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-red-200">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role_id" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-red-200">
                    <option value="">Select Role</option>
                    <option value="0">Admin</option>
                    <option value="1">NGO</option>
                    <option value="2">People</option>
                </select>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="verified" value="1" id="verified">
                <label for="verified" class="ml-2 text-sm text-gray-700">Mark as Verified</label>
            </div>

            <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium">
                Create User
            </button>
        </form>
    </div>
</div>
@endsection
