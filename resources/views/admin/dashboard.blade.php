@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-500 mt-2">Welcome back, <span class="font-bold text-gray-900">{{ Auth::user()->name }}</span>!
                Here's what's happening today.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- NGOs -->
            <div class="glass-panel p-6 flex items-center justify-between group hover:shadow-lg transition-all duration-300">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">NGOs</p>
                    <p class="text-3xl font-black text-gray-900 mt-2">{{ $ngoCount }}</p>
                </div>
                <div
                    class="w-14 h-14 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-building"></i>
                </div>
            </div>

            <!-- Users -->
            <div
                class="glass-panel p-6 flex items-center justify-between group hover:shadow-lg transition-all duration-300">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Users</p>
                    <p class="text-3xl font-black text-gray-900 mt-2">{{ $userCount }}</p>
                </div>
                <div
                    class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-users"></i>
                </div>
            </div>

            {{-- <!-- Donations -->
            <div
                class="glass-panel p-6 flex items-center justify-between group hover:shadow-lg transition-all duration-300">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Donations</p>
                    <p class="text-3xl font-black text-gray-900 mt-2">${{ number_format($totalDonations) }}</p>
                </div>
                <div
                    class="w-14 h-14 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
            </div> --}}

            <!-- Pending Approvals -->
            <div
                class="glass-panel p-6 flex items-center justify-between relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                <div class="relative z-10">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending NGO Requests</p>
                    <p class="text-3xl font-black text-gray-900 mt-2">{{ $pendingNgoApprovals }}</p>
                </div>
                <div
                    class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl relative z-10 group-hover:scale-110 transition-transform">
                    <i class="fas fa-clock"></i>
                </div>
                @if ($pendingNgoApprovals > 0)
                    <div class="absolute inset-0 bg-amber-500/5 animate-pulse"></div>
                @endif
            </div>
        </div>

        <!-- Action Required & Quick Links -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column: Content Moderation -->
            <div class="lg:col-span-2 space-y-8">
                <div class="glass-panel p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-500">
                                <i class="fas fa-shield-alt text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Content Moderation</h3>
                                <p class="text-sm text-gray-500">Items requiring your attention</p>
                            </div>
                        </div>
                        <span
                            class="px-3 py-1 bg-red-50 text-red-600 text-xs font-bold rounded-full uppercase tracking-wide border border-red-100">
                            {{ $reportedPosts }} Reports
                        </span>
                    </div>

                    @if ($reportedPosts > 0)
                        <div class="bg-red-50 rounded-xl p-4 border border-red-100 mb-4 flex items-start gap-3">
                            <i class="fas fa-exclamation-circle text-red-500 mt-1"></i>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Action Needed</h4>
                                <p class="text-xs text-gray-600 mt-1">There are {{ $reportedPosts }} posts flagged by the
                                    community. Please review them to maintain community standards.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.log') }}"
                            class="inline-flex items-center justify-center w-full px-4 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition-colors shadow-lg shadow-red-200">
                            Review Reported Content <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-check text-green-500 text-2xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900">All Clear!</h4>
                            <p class="text-sm text-gray-500 mt-1">No reported content at this time.</p>
                        </div>
                    @endif
                </div>

                <!-- Recent Activity Placeholder (could be added later) -->
                <div class="glass-panel p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Recent System Activity</h3>
                        <a href="{{ route('admin.log') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">
                            View Log
                        </a>
                    </div>
                    <div class="space-y-4">
                        <!-- Placeholder items -->
                        <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500 text-xs">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">New User Registered</p>
                                    <p class="text-xs text-gray-500">2 minutes ago</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-emerald-500">+1 User</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500 text-xs">
                                    <i class="fas fa-hand-holding-heart"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Donation Processed</p>
                                    <p class="text-xs text-gray-500">45 minutes ago</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-gray-500">View</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Quick Links -->
            <div class="space-y-8">
                <div class="glass-panel p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('admin.ngos') }}"
                            class="flex flex-col items-center justify-center p-4 rounded-2xl border border-gray-100 hover:border-red-200 hover:bg-red-50 transition-all group bg-white shadow-sm">
                            <i
                                class="fas fa-search text-gray-400 group-hover:text-red-500 text-xl mb-2 transition-colors"></i>
                            <span class="text-sm font-bold text-gray-600 group-hover:text-red-600 transition-colors">Find
                                NGO</span>
                        </a>
                        <a href="{{ route('admin.user.register') }}"
                            class="flex flex-col items-center justify-center p-4 rounded-2xl border border-gray-100 hover:border-green-200 hover:bg-green-50 transition-all group bg-white shadow-sm">
                            <i
                                class="fas fa-user-plus text-gray-400 group-hover:text-green-500 text-xl mb-2 transition-colors"></i>
                            <span class="text-sm font-bold text-gray-600 group-hover:text-green-600 transition-colors">Add
                                User</span>
                        </a>
                        <a href="{{ route('register.ngo.form') }}"
                            class="flex flex-col items-center justify-center p-4 rounded-2xl border border-gray-100 hover:border-red-200 hover:bg-red-50 transition-all group bg-white shadow-sm">
                            <i
                                class="fas fa-building text-gray-400 group-hover:text-red-500 text-xl mb-2 transition-colors"></i>
                            <span class="text-sm font-bold text-gray-600 group-hover:text-red-600 transition-colors">Add
                                NGO</span>
                        </a>
                        <a href="{{ route('admin.log') }}"
                            class="flex flex-col items-center justify-center p-4 rounded-2xl border border-gray-100 hover:border-gray-300 hover:bg-gray-50 transition-all group bg-white shadow-sm">
                            <i
                                class="fas fa-list-alt text-gray-400 group-hover:text-gray-600 text-xl mb-2 transition-colors"></i>
                            <span class="text-sm font-bold text-gray-600 group-hover:text-gray-800 transition-colors">View
                                Logs</span>
                        </a>
                    </div>
                </div>

                <!-- Platform Health -->
                <div class="glass-panel p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">System Health</h3>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-xs font-bold text-green-600">Operational</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-xs font-bold text-gray-500 mb-1">
                                <span>Storage Usage</span>
                                <span>45%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold text-gray-500 mb-1">
                                <span>Server Load</span>
                                <span>12%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 12%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
