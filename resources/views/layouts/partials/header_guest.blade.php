<header class="glass-panel sticky top-0 w-full z-50 border-b border-white/20">
    <div class="flex items-center justify-between px-6 py-4 max-w-screen-2xl mx-auto">
        <!-- Left Section: Logo -->
        <div class="flex items-center space-x-2">
            <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                <img src="{{ url('logo-nobg.png') }}" alt="Logo" class="h-9 group-hover:scale-105 transition-all duration-300 drop-shadow-md">
            </a>
        </div>

        <!-- Right Section: Auth Links -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-primary transition-colors px-4 py-2">Log in</a>
            <a href="{{ route('register') }}" class="btn-primary">
                Join Community
            </a>
        </div>
    </div>
</header>
