<header class="glass-panel fixed top-0 left-0 right-0 z-50 !rounded-none border-x-0 border-t-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo Section -->
            <div class="flex items-center space-x-3">
                <div class="w-auto">
                    <img src="{{ url('logo-nobg.png') }}" alt="Logo" class="h-10">
                </div>
                <span class="hidden sm:inline-block text-xs font-bold text-white bg-primary px-2 py-0.5 rounded-full uppercase tracking-wider">Admin</span>
            </div>

            <!-- Center Navigation Menu -->
            <div class="lg:block flex-1 max-w-4xl mx-8">
                <div class="flex items-center justify-center space-x-1">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-4 py-2 rounded-xl text-sm font-bold transition-all duration-300
                        {{ request()->routeIs('admin.dashboard') ? 'bg-white shadow-sm text-primary' : 'text-slate-600 hover:bg-white/60 hover:text-primary' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-2 {{ request()->routeIs('admin.dashboard') ? 'bg-red-50' : 'bg-slate-100' }}">
                            <i class="fas fa-chart-pie text-sm {{ request()->routeIs('admin.dashboard') ? 'text-red-500' : 'text-slate-500' }}"></i>
                        </div>
                        <span class="whitespace-nowrap">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.ngos') }}"
                        class="flex items-center px-4 py-2 rounded-xl text-sm font-bold transition-all duration-300
                        {{ request()->routeIs('admin.ngos*') ? 'bg-white shadow-sm text-primary' : 'text-slate-600 hover:bg-white/60 hover:text-primary' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-2 {{ request()->routeIs('admin.ngos*') ? 'bg-emerald-50' : 'bg-slate-100' }}">
                            <i class="fas fa-building text-sm {{ request()->routeIs('admin.ngos*') ? 'text-emerald-500' : 'text-slate-500' }}"></i>
                        </div>
                        <span class="whitespace-nowrap">NGO Management</span>
                    </a>

                    <a href="{{ route('admin.user') }}"
                        class="flex items-center px-4 py-2 rounded-xl text-sm font-bold transition-all duration-300
                        {{ request()->routeIs('admin.user*') ? 'bg-white shadow-sm text-primary' : 'text-slate-600 hover:bg-white/60 hover:text-primary' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-2 {{ request()->routeIs('admin.user*') ? 'bg-red-50' : 'bg-slate-100' }}">
                            <i class="fas fa-users text-sm {{ request()->routeIs('admin.user*') ? 'text-red-500' : 'text-slate-500' }}"></i>
                        </div>
                        <span class="whitespace-nowrap">User Management</span>
                    </a>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <!-- Notification Button -->
                <div class="relative">
                    <button class="w-10 h-10 rounded-xl bg-white/60 hover:bg-white flex items-center justify-center transition-all duration-300 shadow-sm notification-btn">
                        <i class="fas fa-bell text-slate-600"></i>
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold animate-pulse">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </button>

                    <!-- Notification Dropdown -->
                    <div class="notification-dropdown absolute right-0 mt-2 w-80 glass-panel hidden z-50 !rounded-2xl overflow-hidden">
                        <div class="p-4 border-b border-slate-100">
                            <h3 class="text-lg font-black text-slate-900">Notifications</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @forelse (auth()->user()->unreadNotifications as $notification)
                                <div class="p-3 hover:bg-red-50/50 cursor-pointer transition-colors duration-200 {{ $loop->first ? 'bg-red-50/30' : '' }}">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-bell text-red-500 text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-slate-700">
                                                {{ $notification->data['message'] ?? 'Notification' }}
                                            </p>
                                            <p class="text-xs text-slate-400 font-medium mt-1">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center">
                                    <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-bell-slash text-slate-300"></i>
                                    </div>
                                    <p class="text-slate-400 text-sm font-medium">No new notifications</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Admin User Dropdown -->
                <div class="relative">
                    <button id="admin-dropdown-toggle"
                        class="flex items-center px-3 py-2 rounded-xl text-sm font-bold text-slate-600 hover:bg-white/60 hover:text-primary transition-all duration-300">
                        <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-500 rounded-xl flex items-center justify-center mr-2">
                            <i class="fas fa-user-shield text-white text-xs"></i>
                        </div>
                        <span class="hidden sm:inline">{{ Auth::user()->name ?? 'Admin' }}</span>
                        <i id="dropdown-chevron" class="fas fa-chevron-down text-xs ml-2 transition-transform duration-200"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="admin-dropdown-menu"
                        class="hidden absolute right-0 mt-2 w-52 glass-panel z-50 !rounded-2xl overflow-hidden">
                        <div class="p-2">
                            <button id="logout-btn"
                                class="w-full flex items-center px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                Logout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- jQuery Script for Dropdown Functionality -->
<script>
    $(document).ready(function() {
        // Toggle dropdown menu
        $('#admin-dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $menu = $('#admin-dropdown-menu');
            const $chevron = $('#dropdown-chevron');

            $menu.toggleClass('hidden');
            $chevron.toggleClass('rotate-180');
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#admin-dropdown-toggle, #admin-dropdown-menu').length) {
                $('#admin-dropdown-menu').addClass('hidden');
                $('#dropdown-chevron').removeClass('rotate-180');
            }
        });

        // Notification toggle
        $('.notification-btn').on('click', function(e) {
            e.stopPropagation();
            $('.notification-dropdown').toggleClass('hidden');
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.notification-btn, .notification-dropdown').length) {
                $('.notification-dropdown').addClass('hidden');
            }
        });

        // Handle logout with AJAX
        $('#logout-btn').on('click', function(e) {
            e.preventDefault();

            if (confirm('Are you sure you want to logout?')) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route('logout') }}',
                    type: 'POST',
                    success: function(response) {
                        window.location.href = '{{ route('login') }}';
                    },
                    error: function(xhr, status, error) {
                        console.error('Logout failed:', error);
                        $('<form>', {
                            'method': 'POST',
                            'action': '{{ route('logout') }}'
                        }).append($('<input>', {
                            'type': 'hidden',
                            'name': '_token',
                            'value': '{{ csrf_token() }}'
                        })).appendTo('body').submit();
                    }
                });
            }
        });
    });
</script>
