@php
    $topPredictions = $churnData['top_predictions'] ?? collect();
@endphp

<div class="glass-panel p-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Volunteer Retention Risk</h2>
            <p class="mt-1 text-sm text-slate-500">Monitor disengagement risk and re-engage early.</p>
        </div>
    </div>

    @if ($topPredictions->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center">
            <p class="text-sm font-medium text-slate-500">No eligible volunteer history yet. Predictions will appear automatically after volunteers register for at least two of your events.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-red-100 bg-red-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-red-500">High Risk</p>
                <p class="mt-2 text-2xl font-bold text-red-600">{{ $churnData['high_count'] }}</p>
            </div>
            <div class="rounded-2xl border border-amber-100 bg-amber-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-500">Medium Risk</p>
                <p class="mt-2 text-2xl font-bold text-amber-600">{{ $churnData['medium_count'] }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-500">Low Risk</p>
                <p class="mt-2 text-2xl font-bold text-emerald-600">{{ $churnData['low_count'] }}</p>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead>
                    <tr class="text-left">
                        <th class="pb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Volunteer Name</th>
                        <th class="pb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Risk Level</th>
                        <th class="pb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Last Active</th>
                        <th class="pb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Events Attended</th>
                        <th class="pb-3 text-xs font-semibold uppercase tracking-wide text-slate-400 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($topPredictions as $prediction)
                        @php
                            $snapshot = $prediction->feature_snapshot ?? [];
                            $riskClasses = match ($prediction->risk_level) {
                                'high' => 'bg-red-50 text-red-600 border border-red-100',
                                'medium' => 'bg-amber-50 text-amber-600 border border-amber-100',
                                default => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                            };
                        @endphp
                        <tr>
                            <td class="py-4 pr-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $prediction->volunteer->name ?? 'Unknown' }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ $prediction->volunteer->email ?? '' }}</p>
                                </div>
                            </td>
                            <td class="py-4 pr-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold uppercase tracking-wide {{ $riskClasses }}">
                                    {{ $prediction->risk_level }}
                                </span>
                            </td>
                            <td class="py-4 pr-4 text-sm text-slate-600">
                                {{ (int) ($snapshot['days_since_last_attendance'] ?? 0) }} days ago
                            </td>
                            <td class="py-4 pr-4 text-sm font-medium text-slate-700">
                                {{ (int) ($snapshot['total_events_attended'] ?? 0) }}
                            </td>
                            <td class="py-4 text-right">
                                <a href="{{ route('common.messages.show', $prediction->volunteer_id) }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition-colors duration-200 hover:border-primary/20 hover:text-primary">
                                    Re-engage
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
