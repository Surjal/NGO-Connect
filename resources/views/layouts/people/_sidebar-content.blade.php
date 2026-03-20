{{-- User Profile Section --}}
<div class="px-6 pb-6 mb-2">
    <a href="{{ route('people.profile') }}" class="flex items-center space-x-4 p-3 rounded-2xl bg-slate-50 hover:bg-white hover:shadow-lg transition-all duration-300 group">
        <div class="w-12 h-12 rounded-xl border-2 border-white shadow-sm overflow-hidden flex-shrink-0 group-hover:scale-110 transition-transform">
            @if (auth()->user()->profile_photo)
                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile"
                    class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
                    <i class="fas fa-user"></i>
                </div>
            @endif
        </div>
        <div class="min-w-0">
            <h3 class="font-bold text-slate-900 text-sm truncate group-hover:text-primary transition-colors">{{ auth()->user()->name }}</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">My Account</p>
        </div>
    </a>
</div>

{{-- Navigation Menu --}}
<nav class="px-3 pb-8">
    <div class="px-4 mb-3 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Explore</div>
    <ul class="space-y-1">
        <li>
            <a href="{{ route('people.ngo.search') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-white hover:text-primary hover:shadow-sm transition-all duration-300 group
                {{ request()->routeIs('people.ngo.search') ? 'bg-white shadow-sm text-primary border border-slate-100' : '' }}">
                <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center group-hover:bg-amber-100 group-hover:rotate-6 transition-all">
                    <i class="fas fa-compass text-amber-500 text-sm"></i>
                </div>
                <span class="font-bold text-sm">Discover NGOs</span>
            </a>
        </li>
        <li>
            <a href="{{ route('people.recommendations') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-white hover:text-primary hover:shadow-sm transition-all duration-300 group
                {{ request()->routeIs('people.recommendations') ? 'bg-white shadow-sm text-primary border border-slate-100' : '' }}">
                <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center group-hover:bg-purple-100 group-hover:rotate-6 transition-all">
                    <i class="fas fa-sparkles text-purple-500 text-sm"></i>
                </div>
                <span class="font-bold text-sm bg-clip-text group-hover:text-transparent group-hover:bg-gradient-to-r group-hover:from-purple-600 group-hover:to-pink-500 transition-all">For You (AI)</span>
            </a>
        </li>
        <li>
            <a href="{{ route('people.volunteer.opportunities') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-white hover:text-primary hover:shadow-sm transition-all duration-300 group
                {{ request()->routeIs('people.volunteer.opportunities') ? 'bg-white shadow-sm text-primary border border-slate-100' : '' }}">
                <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center group-hover:bg-red-100 group-hover:rotate-6 transition-all">
                    <i class="fas fa-calendar-star text-red-500 text-sm"></i>
                </div>
                <span class="font-bold text-sm">Volunteer Events</span>
            </a>
        </li>
        <li>
            <a href="{{ route('people.donations') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-white hover:text-primary hover:shadow-sm transition-all duration-300 group
                {{ request()->routeIs('people.donations') ? 'bg-white shadow-sm text-primary border border-slate-100' : '' }}">
                <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center group-hover:bg-red-100 group-hover:rotate-6 transition-all">
                    <i class="fas fa-heart-pulse text-red-500 text-sm"></i>
                </div>
                <span class="font-bold text-sm">Support & Donate</span>
            </a>
        </li>
        <li>
            <a href="{{ route('people.notifications') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-white hover:text-primary hover:shadow-sm transition-all duration-300 group
                {{ request()->routeIs('people.notifications') ? 'bg-white shadow-sm text-primary border border-slate-100' : '' }}">
                <div class="w-9 h-9 bg-sky-50 rounded-xl flex items-center justify-center group-hover:bg-sky-100 group-hover:rotate-6 transition-all">
                    <i class="fas fa-bell text-sky-500 text-sm"></i>
                </div>
                <span class="font-bold text-sm">Updates</span>
                @if (auth()->user()->unreadNotifications->count() > 0)
                    <span class="ml-auto bg-secondary text-white text-[10px] font-bold rounded-full min-w-[22px] h-5.5 flex items-center justify-center px-1.5 shadow-sm shadow-secondary/20">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
        </li>
    </ul>
</nav>

{{-- Footer --}}
<div class="px-8 mt-auto pb-8">
    <div class="p-4 bg-slate-900 rounded-2xl relative overflow-hidden group">
        <div class="absolute -top-4 -right-4 w-12 h-12 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Impact Made</p>
        <p class="text-white text-xs font-medium leading-relaxed opacity-80">
            Helping communities connect since {{ date('Y') }}.
        </p>
    </div>
</div>
