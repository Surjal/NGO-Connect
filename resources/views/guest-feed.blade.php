<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'NGO Connect') }} — Community Feed</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 antialiased font-body text-slate-900 selection:bg-primary/10 selection:text-primary">
        <!-- Animated Background -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-primary/5 blur-[120px] animate-pulse"></div>
            <div class="absolute top-[20%] -right-[5%] w-[30%] h-[30%] rounded-full bg-secondary/5 blur-[100px] animate-pulse" style="animation-delay: 2s"></div>
        </div>

        <!-- Header -->
        <header class="glass-panel sticky top-0 w-full z-50 border-b border-white/20">
            <div class="flex items-center justify-between px-6 py-3 max-w-screen-2xl mx-auto">
                <!-- Left: Logo -->
                <div class="flex items-center space-x-2">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                        <img src="{{ url('logo-nobg.png') }}" alt="Logo" class="h-9 group-hover:scale-105 transition-all duration-300 drop-shadow-md">
                    </a>
                </div>

                <!-- Center: Navigation -->
                <div class="hidden md:flex items-center bg-slate-100/50 p-1 rounded-2xl border border-slate-200/50">
                    <a href="{{ url('/') }}" class="flex items-center gap-2 px-6 py-2 rounded-xl transition-all duration-300 text-primary bg-white shadow-sm font-semibold">
                        <i class="fas fa-home text-lg"></i>
                        <span class="text-sm">Home</span>
                    </a>
                    <a href="{{ route('login') }}" class="flex items-center gap-2 px-6 py-2 rounded-xl transition-all duration-300 text-slate-500 hover:text-slate-900">
                        <i class="fas fa-search text-lg"></i>
                        <span class="text-sm">Search</span>
                    </a>
                </div>

                <!-- Right: Auth -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-primary transition-colors px-4 py-2">Log in</a>
                    <a href="{{ route('register.people') }}" class="btn-primary">
                        Join Community
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="pt-6 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left Sidebar -->
                <div class="hidden lg:block lg:col-span-3">
                    <div class="sticky top-24 space-y-6">
                        <div class="glass-panel p-5">
                            <div class="px-1 mb-3 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Explore</div>
                            <div class="space-y-1">
                                <a href="{{ url('/') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl bg-white shadow-sm text-primary border border-slate-100 transition-all group">
                                    <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center group-hover:rotate-6 transition-all">
                                        <i class="fas fa-home text-red-500 text-sm"></i>
                                    </div>
                                    <span class="font-bold text-sm">Home</span>
                                </a>
                                <a href="{{ route('login') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-slate-600 hover:bg-white hover:shadow-sm transition-all group">
                                    <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center group-hover:rotate-6 transition-all">
                                        <i class="fas fa-compass text-amber-500 text-sm"></i>
                                    </div>
                                    <span class="font-bold text-sm">NGOs</span>
                                </a>
                                <a href="{{ route('login') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-slate-600 hover:bg-white hover:shadow-sm transition-all group">
                                    <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center group-hover:rotate-6 transition-all">
                                        <i class="fas fa-calendar-alt text-red-500 text-sm"></i>
                                    </div>
                                    <span class="font-bold text-sm">Events</span>
                                </a>
                            </div>
                        </div>

                        <div class="glass-panel p-5">
                            <div class="px-1 mb-3 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Resources</div>
                            <div class="space-y-2">
                                <a href="#" class="block text-slate-500 hover:text-primary text-xs font-bold transition-colors">About Us</a>
                                <a href="#" class="block text-slate-500 hover:text-primary text-xs font-bold transition-colors">Privacy Policy</a>
                                <a href="#" class="block text-slate-500 hover:text-primary text-xs font-bold transition-colors">Terms of Service</a>
                            </div>
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">© {{ date('Y') }} NGO Connect</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle Feed -->
                <div class="lg:col-span-6">
                    <!-- Create Post Placeholder (Guest) -->
                    <div class="glass-panel p-6 mb-6">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-11 h-11 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="flex-1">
                                <div class="input-premium cursor-pointer hover:bg-white/80 transition-colors" onclick="window.location.href='{{ route('login') }}'">
                                    <span class="text-slate-400 text-sm">What's on your mind?</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <button class="flex items-center space-x-2 flex-1 justify-center py-2 rounded-xl hover:bg-slate-50 transition-all" onclick="window.location.href='{{ route('login') }}'">
                                <div class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center">
                                    <i class="fas fa-video text-red-500 text-xs"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-500">Live</span>
                            </button>
                            <button class="flex items-center space-x-2 flex-1 justify-center py-2 rounded-xl hover:bg-slate-50 transition-all" onclick="window.location.href='{{ route('login') }}'">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center">
                                    <i class="fas fa-images text-emerald-500 text-xs"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-500">Photo</span>
                            </button>
                            <button class="flex items-center space-x-2 flex-1 justify-center py-2 rounded-xl hover:bg-slate-50 transition-all" onclick="window.location.href='{{ route('login') }}'">
                                <div class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center">
                                    <i class="far fa-smile text-amber-500 text-xs"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-500">Activity</span>
                            </button>
                        </div>
                    </div>

                    <!-- Posts -->
                    <div class="space-y-5">
                        @include('common.feed.partials.post')
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="hidden lg:block lg:col-span-3">
                    <div class="sticky top-24 space-y-6">
                        <!-- Suggested NGOs -->
                        <div class="glass-panel p-5">
                            <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Suggested NGOs</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between group">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center text-red-500 font-black text-xs group-hover:scale-110 transition-transform">RC</div>
                                        <span class="font-bold text-sm text-slate-700">Red Cross</span>
                                    </div>
                                    <button class="text-primary text-xs font-black hover:underline" onclick="window.location.href='{{ route('login') }}'">Follow</button>
                                </div>
                                <div class="flex items-center justify-between group">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 bg-sky-50 rounded-xl flex items-center justify-center text-sky-500 font-black text-xs group-hover:scale-110 transition-transform">UN</div>
                                        <span class="font-bold text-sm text-slate-700">UNICEF</span>
                                    </div>
                                    <button class="text-primary text-xs font-black hover:underline" onclick="window.location.href='{{ route('login') }}'">Follow</button>
                                </div>
                            </div>
                        </div>

                        <!-- CTA -->
                        <div class="p-5 bg-slate-900 rounded-2xl relative overflow-hidden group">
                            <div class="absolute -top-4 -right-4 w-16 h-16 bg-primary/20 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
                            <div class="relative">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Join Us Today</p>
                                <p class="text-white text-sm font-bold leading-relaxed mb-4">Connect with NGOs and make a real difference.</p>
                                <a href="{{ route('register.people') }}" class="btn-primary w-full py-2.5 text-sm">
                                    <i class="fas fa-rocket"></i>
                                    Get Started
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('common.feed.partials.modal')
        
        <!-- Stack for pushed scripts from partials -->
        @stack('scripts')

        <script>
            // Global handler for 401 Unauthorized errors
            $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
                if (jqxhr.status == 401) {
                    window.location.href = "{{ route('login') }}";
                }
            });

            @guest
                $(window).on('load', function() {
                     setTimeout(function() {
                         $('.like-button, .comment-button, .follow-button, .report-post-btn').off('click').on('click', function(e) {
                            e.preventDefault();
                            e.stopImmediatePropagation();
                            window.location.href = "{{ route('login') }}";
                        });
                     }, 100);
                });
            @endguest
        </script>
    </body>
</html>
