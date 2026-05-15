@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-red-500">Admin Oversight</p>
                <h1 class="mt-2 text-3xl font-bold text-gray-900">Reports & Activity Log</h1>
                <p class="mt-2 text-sm text-gray-500">
                    Review reported content, audit actions, and the latest platform activity from one place.
                </p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-600 transition-colors duration-200 hover:bg-gray-50 hover:text-red-600">
                <i class="fas fa-arrow-left mr-2 text-xs"></i>
                Back to Dashboard
            </a>
        </div>

        <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Reported Posts</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $reportSummary->count() }}</p>
                <p class="mt-1 text-xs text-gray-400">Unique posts currently flagged by users.</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Total Reports</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalReports }}</p>
                <p class="mt-1 text-xs text-gray-400">Recent submitted report entries in this feed.</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Audit Entries</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $auditLogs->count() }}</p>
                <p class="mt-1 text-xs text-gray-400">Latest admin actions recorded in the system.</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Top Reason</p>
                <p class="mt-2 text-sm font-bold text-gray-900">
                    {{ $reportReasons->first()->reason ?? 'No reports yet' }}
                </p>
                <p class="mt-1 text-xs text-gray-400">
                    {{ $reportReasons->first() ? $reportReasons->first()->total . ' submissions' : 'No moderation pressure right now.' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 xl:grid-cols-12">
            <div class="space-y-8 xl:col-span-7">
                <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="text-lg font-bold text-gray-900">Reported Content</h2>
                        <p class="mt-1 text-sm text-gray-400">Posts with the highest number of community reports.</p>
                    </div>

                    <div class="p-5">
                        @if ($reportSummary->isNotEmpty())
                            <div class="space-y-4">
                                @foreach ($reportSummary as $report)
                                    @php
                                        $post = $report->post;
                                        $postOwner = $post?->user;
                                        $ngoName = $postOwner?->ngo?->ngo_name ?? $postOwner?->name ?? 'Unknown author';
                                        $sourceRoute = null;

                                        if ($postOwner?->ngo) {
                                            $sourceRoute = route('admin.ngos.show', $postOwner->ngo->id);
                                        } elseif ($postOwner) {
                                            $sourceRoute = route('admin.user.show', $postOwner->id);
                                        }
                                    @endphp
                                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                            <div class="min-w-0 flex-1">
                                                <div class="mb-2 flex flex-wrap items-center gap-2">
                                                    <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold uppercase tracking-wide text-red-600">
                                                        {{ $report->reports_count }} {{ \Illuminate\Support\Str::plural('Report', $report->reports_count) }}
                                                    </span>
                                                    <span class="text-xs text-gray-400">
                                                        Last flagged {{ \Carbon\Carbon::parse($report->last_reported_at)->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <h3 class="text-sm font-semibold text-gray-900">
                                                    {{ $post?->title ?: 'Untitled post' }}
                                                </h3>
                                                <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-gray-500">
                                                    {{ $post?->description ?: 'No post description available.' }}
                                                </p>
                                                <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-gray-400">
                                                    <span>Author: <span class="font-medium text-gray-600">{{ $postOwner?->name ?? 'Unknown' }}</span></span>
                                                    <span>Context: <span class="font-medium text-gray-600">{{ $ngoName }}</span></span>
                                                </div>
                                            </div>
                                            @if ($sourceRoute)
                                                <a href="{{ $sourceRoute }}"
                                                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-600 transition-colors duration-200 hover:bg-gray-50 hover:text-red-600">
                                                    View Source
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-6 py-12 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-green-500 shadow-sm">
                                    <i class="fas fa-check text-xl"></i>
                                </div>
                                <h3 class="mt-4 text-lg font-semibold text-gray-900">No reported content</h3>
                                <p class="mt-2 text-sm text-gray-400">Nothing needs moderation right now.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="text-lg font-bold text-gray-900">Recent Reports</h2>
                        <p class="mt-1 text-sm text-gray-400">A chronological view of the latest user-submitted reports.</p>
                    </div>

                    <div class="p-5">
                        @if ($recentReports->isNotEmpty())
                            <div class="space-y-3">
                                @foreach ($recentReports as $entry)
                                    <div class="rounded-xl border border-gray-100 bg-white p-4">
                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">
                                                    {{ $entry->reason ?: 'Unspecified reason' }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-400">
                                                    Reporter: {{ $entry->user->name ?? 'Unknown user' }} •
                                                    Post: {{ $entry->post->title ?? 'Untitled post' }}
                                                </p>
                                                @if ($entry->report_description)
                                                    <p class="mt-2 text-sm text-gray-500">{{ $entry->report_description }}</p>
                                                @endif
                                            </div>
                                            <span class="text-xs text-gray-400">{{ $entry->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400">No recent reports available.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-8 xl:col-span-5">
                <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="text-lg font-bold text-gray-900">Report Reasons</h2>
                        <p class="mt-1 text-sm text-gray-400">What users are flagging most often.</p>
                    </div>

                    <div class="p-5">
                        @if ($reportReasons->isNotEmpty())
                            <div class="space-y-3">
                                @foreach ($reportReasons as $reason)
                                    <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3">
                                        <span class="text-sm font-medium text-gray-700">{{ $reason->reason ?: 'Unknown' }}</span>
                                        <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-red-600">
                                            {{ $reason->total }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400">No report reasons recorded yet.</p>
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="text-lg font-bold text-gray-900">Audit Trail</h2>
                        <p class="mt-1 text-sm text-gray-400">Recent actions performed by administrators.</p>
                    </div>

                    <div class="p-5">
                        @if ($auditLogs->isNotEmpty())
                            <div class="space-y-3">
                                @foreach ($auditLogs as $log)
                                    <div class="rounded-xl border border-gray-100 bg-white p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $log->action }}</p>
                                                <p class="mt-1 text-xs text-gray-400">
                                                    Admin: {{ $log->admin->name ?? 'System' }} •
                                                    {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                                </p>
                                                @if ($log->details)
                                                    <p class="mt-2 text-sm text-gray-500">{{ $log->details }}</p>
                                                @endif
                                            </div>
                                            <span class="shrink-0 text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400">No audit entries available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
