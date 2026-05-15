<?php

namespace App\Http\Controllers\Admin;

use App\Models\AuditLogs;
use App\Models\PostHasReports;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function showLog()
    {
        $totalReports = PostHasReports::count();

        $reportSummary = PostHasReports::query()
            ->select('post_id', DB::raw('COUNT(*) as reports_count'), DB::raw('MAX(created_at) as last_reported_at'))
            ->with([
                'post.user.ngo',
            ])
            ->groupBy('post_id')
            ->orderByDesc('reports_count')
            ->orderByDesc('last_reported_at')
            ->get();

        $recentReports = PostHasReports::query()
            ->with(['post.user.ngo'])
            ->latest()
            ->take(12)
            ->get();

        $auditLogs = AuditLogs::query()
            ->with('admin')
            ->latest()
            ->take(20)
            ->get();

        $reportReasons = PostHasReports::query()
            ->select('reason', DB::raw('COUNT(*) as total'))
            ->groupBy('reason')
            ->orderByDesc('total')
            ->get();

        return view('admin.log', compact('totalReports', 'reportSummary', 'recentReports', 'auditLogs', 'reportReasons'));
    }
}
    
