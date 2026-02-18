<header class="glass-panel sticky top-0 w-full z-50 border-b border-white/20">
    <div class="flex items-center justify-between px-6 py-3 max-w-screen-2xl mx-auto">
        <!-- Left Section: Logo -->
        <div class="flex items-center space-x-2">
            <a href="{{ route('common.feed') }}" class="flex items-center space-x-2 group">
                <img src="{{ url('logo-nobg.png') }}" alt="Logo" class="h-9 group-hover:scale-105 transition-all duration-300 drop-shadow-md">
            </a>
        </div>

        <!-- Center Navigation -->
        <div class="hidden md:flex items-center bg-slate-100/50 p-1 rounded-2xl border border-slate-200/50">
            <a href="{{ route('common.feed') }}"
                class="flex items-center gap-2 px-6 py-2 rounded-xl transition-all duration-300
                {{ request()->routeIs('common.feed') ? 'text-primary bg-white shadow-sm font-semibold' : 'text-slate-500 hover:text-slate-900' }}">
                <i class="fas fa-home text-lg"></i>
                <span class="text-sm">Home</span>
            </a>
            <a href="{{ route('people.ngo.search') }}"
                class="flex items-center gap-2 px-6 py-2 rounded-xl transition-all duration-300
                {{ request()->routeIs('people.ngo.search') ? 'text-primary bg-white shadow-sm font-semibold' : 'text-slate-500 hover:text-slate-900' }}">
                <i class="fas fa-search text-lg"></i>
                <span class="text-sm">Search</span>
            </a>
            <a href="{{ route('common.messages.index') }}"
                class="flex items-center gap-2 px-6 py-2 rounded-xl transition-all duration-300
                {{ request()->routeIs('common.messages.*') ? 'text-primary bg-white shadow-sm font-semibold' : 'text-slate-500 hover:text-slate-900' }}">
                <i class="fas fa-comment-dots text-lg"></i>
                <span class="text-sm">Messages</span>
            </a>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-4 justify-end">
            <!-- Notifications -->
            <div class="relative">
                <button class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl hover:border-primary/50 hover:bg-primary/5 transition-all duration-300 notification-btn relative group">
                    <i class="fas fa-bell text-slate-500 group-hover:text-primary transition-colors"></i>
                    @if (auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute -top-1 -right-1 bg-secondary text-white text-[10px] rounded-full w-5 h-5 flex items-center justify-center font-bold border-2 border-white">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </button>

                <!-- Notification Dropdown -->
                <div class="notification-dropdown absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-slate-200 hidden z-50 overflow-hidden page-enter">
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-base font-bold text-slate-900">Notifications</h3>
                        <span class="text-[10px] font-bold px-2 py-0.5 bg-primary/10 text-primary rounded-full uppercase tracking-wider">Unread</span>
                    </div>
                    <div class="max-h-80 overflow-y-auto scrollbar-hide">
                        @forelse (auth()->user()->unreadNotifications as $notification)
                            <div class="p-4 hover:bg-slate-50 cursor-pointer border-b border-slate-50 transition-colors group">
                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-bell text-primary text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-slate-700 leading-snug">{{ $notification->data['message'] ?? 'Notification' }}</p>
                                        <p class="text-[10px] text-slate-400 mt-1 font-medium">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-bell-slash text-slate-300 text-xl"></i>
                                </div>
                                <p class="text-slate-400 text-sm font-medium">Clear for now!</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="p-3 border-t border-slate-100">
                        <a href="{{ route(auth()->user()->isNgo() ? 'ngo.notifications' : 'people.notifications') }}"
                            class="block w-full text-center text-primary hover:bg-primary/5 font-semibold text-xs py-2 rounded-xl transition-all">
                            View All Activity
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative">
                <button class="flex items-center gap-2 p-1 pr-3 bg-white border border-slate-200 rounded-2xl hover:border-primary/50 transition-all duration-300 profile-btn group">
                    <div class="w-8 h-8 rounded-xl overflow-hidden shadow-sm">
                        @if (auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <i class="fas fa-user text-xs"></i>
                            </div>
                        @endif
                    </div>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400 group-hover:text-primary transition-colors"></i>
                </button>

                <!-- Profile Dropdown -->
                <div class="profile-dropdown absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-2xl border border-slate-200 hidden z-50 overflow-hidden page-enter">
                    @php
                        $user = auth()->user();
                        $url = $user && $user->isNgo() ? route('common.ngo.profile', $user->id) : route('people.profile');
                    @endphp

                    <div class="p-4 border-b border-slate-50">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl overflow-hidden shadow-md">
                                @if (auth()->user()->profile_photo)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-slate-900 text-sm truncate">{{ auth()->user()->name }}</h3>
                                <p class="text-[10px] font-bold text-primary uppercase tracking-wider">{{ auth()->user()->role->name ?? 'User' }}</p>
                            </div>
                        </div>
                        <a href="{{ $url }}" class="flex items-center justify-center gap-2 w-full py-2 bg-slate-50 hover:bg-primary hover:text-white rounded-xl text-xs font-semibold text-slate-600 transition-all">
                            <i class="fas fa-user-circle"></i>
                            My Profile
                        </a>
                    </div>

                    <div class="p-2">
                        @if (auth()->user()->isPeople() && auth()->user()->ownedNgos->count())
                            <div class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Switch Profile</div>
                            @foreach (auth()->user()->ownedNgos as $ngo)
                                <a href="{{ route('switch.to.ngo', $ngo->id) }}"
                                    class="flex items-center space-x-3 p-3 hover:bg-slate-50 rounded-xl transition-all group">
                                    <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center group-hover:bg-amber-100">
                                        <i class="fas fa-exchange-alt text-amber-500 text-xs"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-700">Switch to {{ $ngo->name }}</span>
                                </a>
                            @endforeach
                        @elseif (auth()->user()->isNgo())
                            @if (session('original_user_id'))
                                <a href="{{ route('switch.back') }}"
                                    class="flex items-center space-x-3 p-3 hover:bg-slate-50 rounded-xl transition-all group border-b border-slate-50">
                                    <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center group-hover:bg-red-100">
                                        <i class="fas fa-user text-red-500 text-xs"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-700">Back to Personal</span>
                                </a>
                            @endif
                        @endif

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center space-x-3 p-3 hover:bg-red-50 rounded-xl transition-all w-full text-left group">
                                <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center group-hover:bg-red-100">
                                    <i class="fas fa-sign-out-alt text-red-500 text-xs"></i>
                                </div>
                                <span class="text-xs font-semibold text-slate-700 group-hover:text-red-600">Secure Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
