@extends('layouts.guest')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-12">

        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-10">
                <a href="{{ url('/') }}" class="inline-block group">
                    <img src="{{ url('logo-nobg.png') }}" alt="Logo"
                        class="h-12 mx-auto group-hover:scale-105 transition-all duration-300 drop-shadow-md">
                </a>
            </div>

            <!-- Login Card -->
            <div class="glass-panel p-10 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-secondary/10 rounded-full blur-3xl"></div>

                <div class="relative">
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Welcome Back</h1>
                    <p class="text-slate-500 font-medium mb-8">Sign in to your NGO Connect account</p>

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-600 p-4 mb-6 rounded-xl">
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm font-medium">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-bold text-slate-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="input-premium" placeholder="Enter your email address" required>
                        </div>

                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                            <input type="password" name="password" id="password" class="input-premium"
                                placeholder="Enter your password" required>
                        </div>

                        <button type="submit" class="btn-primary w-full py-3 text-base">
                            <i class="fas fa-sign-in-alt"></i>
                            Sign In
                        </button>
                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-slate-500 text-sm font-medium">
                            Don't have an account?
                        </p>
                        <div class="flex items-center justify-center gap-4 mt-3">
                            <a href="{{ route('register.people') }}"
                                class="text-primary hover:text-primary-hover font-bold text-sm transition-colors">
                                <i class="fas fa-user mr-1"></i> People
                            </a>
                            <span class="text-slate-300">|</span>
                            <a href="{{ route('register.ngo.form') }}"
                                class="text-primary hover:text-primary-hover font-bold text-sm transition-colors">
                                <i class="fas fa-building mr-1"></i> NGO
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
