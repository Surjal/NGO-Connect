{{-- NGO Profile Section --}}
<div class="px-6 pb-6 mb-2">
    <a href="{{ route('common.ngo.profile', auth()->user()->id) }}" class="flex items-center space-x-4 p-3 rounded-2xl bg-slate-50 hover:bg-white hover:shadow-lg transition-all duration-300 group">
        <div class="w-12 h-12 rounded-xl border-2 border-white shadow-sm overflow-hidden flex-shrink-0 group-hover:scale-110 transition-transform">
            @if (auth()->user()->ngo && auth()->user()->ngo->logo)
                <img src="{{ asset('storage/' . auth()->user()->ngo->logo) }}" alt="NGO Logo"
                    class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-primary/10 flex items-center justify-center text-primary">
                    <i class="fas fa-building"></i>
                </div>
            @endif
        </div>
        <div class="min-w-0">
            <h3 class="font-bold text-slate-900 text-sm truncate group-hover:text-primary transition-colors">
                {{ auth()->user()->isNgo() ? auth()->user()->name : auth()->user()->ngo->name }}
            </h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Organization</p>
        </div>
    </a>
</div>

{{-- Navigation Menu --}}
<nav class="px-3 pb-8">
    <div class="px-4 mb-3 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Manage</div>
    <ul class="space-y-1">
        {{-- Dashboard --}}
        <li>
            <a href="{{ route('dashboard') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-white hover:text-primary hover:shadow-sm transition-all duration-300 group
                {{ request()->routeIs('dashboard') ? 'bg-white shadow-sm text-primary border border-slate-100' : '' }}">
                <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center group-hover:bg-red-100 group-hover:rotate-6 transition-all">
                    <i class="fas fa-chart-pie text-red-500 text-sm"></i>
                </div>
                <span class="font-bold text-sm">Dashboard</span>
            </a>
        </li>
        {{-- Search NGOs --}}
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

        {{-- Events --}}
        <li>
            <a href="{{ route('ngo.events') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-white hover:text-primary hover:shadow-sm transition-all duration-300 group
                {{ request()->routeIs('ngo.events*') ? 'bg-white shadow-sm text-primary border border-slate-100' : '' }}">
                <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center group-hover:bg-red-100 group-hover:rotate-6 transition-all">
                    <i class="fas fa-calendar-alt text-red-500 text-sm"></i>
                </div>
                <span class="font-bold text-sm">Events</span>
            </a>
        </li>

        {{-- Volunteers --}}
        <li>
            <a href="{{ route('ngo.volunteers') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-white hover:text-primary hover:shadow-sm transition-all duration-300 group
                {{ request()->routeIs('ngo.volunteers*') ? 'bg-white shadow-sm text-primary border border-slate-100' : '' }}">
                <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center group-hover:bg-emerald-100 group-hover:rotate-6 transition-all">
                    <i class="fas fa-hands-helping text-emerald-500 text-sm"></i>
                </div>
                <span class="font-bold text-sm">Volunteers</span>
            </a>
        </li>

        {{-- Donations --}}
        <li>
            <a href="{{ route('ngo.donations') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-white hover:text-primary hover:shadow-sm transition-all duration-300 group
                {{ request()->routeIs('ngo.donations*') ? 'bg-white shadow-sm text-primary border border-slate-100' : '' }}">
                <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center group-hover:bg-red-100 group-hover:rotate-6 transition-all">
                    <i class="fas fa-heart text-red-500 text-sm"></i>
                </div>
                <span class="font-bold text-sm">Donations</span>
            </a>
        </li>

        {{-- Followers --}}
        <li>
            <a href="{{ route('ngo.followers') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-white hover:text-primary hover:shadow-sm transition-all duration-300 group
                {{ request()->routeIs('ngo.followers') ? 'bg-white shadow-sm text-primary border border-slate-100' : '' }}">
                <div class="w-9 h-9 bg-sky-50 rounded-xl flex items-center justify-center group-hover:bg-sky-100 group-hover:rotate-6 transition-all">
                    <i class="fas fa-users text-sky-500 text-sm"></i>
                </div>
                <span class="font-bold text-sm">Followers</span>
            </a>
        </li>

        {{-- Notifications --}}
        <li>
            <a href="{{ route('ngo.notifications') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-white hover:text-primary hover:shadow-sm transition-all duration-300 group
                {{ request()->routeIs('ngo.notifications*') ? 'bg-white shadow-sm text-primary border border-slate-100' : '' }}">
                <div class="w-9 h-9 bg-orange-50 rounded-xl flex items-center justify-center group-hover:bg-orange-100 group-hover:rotate-6 transition-all">
                    <i class="fas fa-bell text-orange-500 text-sm"></i>
                </div>
                <span class="font-bold text-sm">Notifications</span>
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
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Organization Hub</p>
        <p class="text-white text-xs font-medium leading-relaxed opacity-80">
            Managing impact since {{ date('Y') }}.
        </p>
    </div>
</div>
