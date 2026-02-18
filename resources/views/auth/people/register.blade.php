@extends('layouts.guest')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-12">

        <div class="w-full max-w-2xl">
            <!-- Logo -->
            <div class="text-center mb-10">
                <a href="{{ url('/') }}" class="inline-block group">
                    <img src="{{ url('logo-nobg.png') }}" alt="Logo" class="h-12 mx-auto group-hover:scale-105 transition-all duration-300 drop-shadow-md">
                </a>
            </div>

            <!-- Register Card -->
            <div class="glass-panel p-10 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-secondary/10 rounded-full blur-3xl"></div>

                <div class="relative">
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Create Account</h1>
                    <p class="text-slate-500 font-medium mb-8">Join NGO Connect and make a difference</p>

                    @if (session('success'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 mb-6 rounded-xl">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-600 p-4 mb-6 rounded-xl">
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm font-medium">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.people') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-bold text-slate-700">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                    class="input-premium"
                                    placeholder="Enter your full name" required>
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-bold text-slate-700">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="input-premium"
                                    placeholder="Enter your email address" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                                <input type="password" name="password" id="password"
                                    class="input-premium"
                                    placeholder="Create a secure password" required>
                            </div>

                            <div class="space-y-2">
                                <label for="password_confirmation" class="block text-sm font-bold text-slate-700">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="input-premium"
                                    placeholder="Confirm your password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary w-full py-3 text-base">
                            <i class="fas fa-user-plus"></i>
                            Create Account
                        </button>
                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-slate-500 text-sm font-medium">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-primary hover:text-primary-hover font-bold transition-colors">
                                Sign In
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection