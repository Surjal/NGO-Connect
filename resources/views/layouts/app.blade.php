<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'NGO Connect') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iconify/2.0.0/iconify.min.js"
        integrity="sha512-lYMiwcB608+RcqJmP93CMe7b4i9G9QK1RbixsNu4PzMRJMsqr/bUrkXUuFzCNsRUo3IXNUr5hz98lINURv5CNA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        /* Smooth page transitions */
        .page-enter {
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-50 antialiased selection:bg-primary/10 selection:text-primary {{ request()->routeIs('common.messages.*') ? 'overflow-hidden' : '' }}">
    <!-- Animated Background Layers -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-primary/5 blur-[120px] animate-pulse"></div>
        <div class="absolute top-[20%] -right-[5%] w-[30%] h-[30%] rounded-full bg-secondary/5 blur-[100px] animate-pulse" style="animation-delay: 2s"></div>
    </div>
    <!-- Header -->
    @guest
        @include('layouts.partials.header_guest')
    @else
        @if (!auth()->user()->isAdmin())
            @include('layouts.partials.header')
        @else
            @include('layouts.admin.header')
        @endif
    @endguest



    @auth
        @if (!auth()->user()->isAdmin())
            <div class="flex {{ request()->routeIs('common.messages.*') ? 'pt-0' : 'pt-16' }}">
                <!-- Left Sidebar -->
                @if (auth()->user()->isPeople())
                    @include('layouts.people.left-sidebar')
                @endif
                @if (auth()->user()->isNgo())
                    @include('layouts.ngo.left-sidebar')
                @endif

                <!-- Main Content -->
                <div class="{{ request()->routeIs('common.messages.*') 
                    ? 'flex-1 w-full h-[calc(100vh-64px)] pb-24 lg:pb-0' 
                    : 'flex-1 w-full lg:ml-80 lg:mr-80 px-4 py-6 pb-24 lg:pb-6 transition-all duration-300' }}">
                    
                    <div class="{{ request()->routeIs('common.messages.*') ? 'h-full' : 'max-w-5xl mx-auto' }}">
                        @if (session('success'))
                            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                                {{ session('error') }}
                            </div>
                        @endif
                        @yield('content')
                    </div>
                </div>

                <!-- Right Sidebar -->
                @if(!request()->routeIs('common.ngo.profile') && !request()->routeIs('common.messages.*'))
                    @include('layouts.people.right-sidebar')
                @endif
            </div>
        @else
            <div class="flex pt-16">
                <div class="flex-1  px-4 py-6">
                    <div class="max-w-screen-2xl mx-auto">
                        @if (session('success'))
                            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                                {{ session('error') }}
                            </div>
                        @endif
                        @yield('content')
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="pt-16">
            @yield('content')
        </div>
    @endauth

    <script>
        $(document).ready(function() {
            $('.notification-btn').on('click', function() {
                $('.notification-dropdown').toggleClass('hidden');
                $('.profile-dropdown').addClass('hidden');
            });

            $('.profile-btn').on('click', function() {
                $('.profile-dropdown').toggleClass('hidden');
                $('.notification-dropdown').addClass('hidden');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.notification-btn, .notification-dropdown').length) {
                    $('.notification-dropdown').addClass('hidden');
                }
                if (!$(e.target).closest('.profile-btn, .profile-dropdown').length) {
                    $('.profile-dropdown').addClass('hidden');
                }
            });
        });
    </script>
    @stack('modals')
    @stack('scripts')
</body>

</html>
