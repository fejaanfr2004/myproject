<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    // Display activity logs
    public function index(Request $request)
    {
        $query = $this->filteredQuery($request);

        $activities = $query->paginate(15)->withQueryString();

        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('admin.activity-logs.index', compact(
            'activities',
            'actions'
        ));
    }

    // Export activity logs to CSV
    public function export(Request $request)
    {
        $activities = $this->filteredQuery($request)
            ->get();

        $filename = 'activity-logs-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response()->streamDownload(function () use ($activities) {
            $handle = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Date & Time',
                'User Name',
                'User Email',
                'Action',
                'Description',
                'IP Address',
            ]);

            foreach ($activities as $activity) {
                fputcsv($handle, [
                    $activity->created_at?->format('d M Y h:i A'),
                    $activity->user_name ?? 'System',
                    $activity->user_email ?? 'N/A',
                    $activity->action,
                    $activity->description,
                    $activity->ip_address ?? 'N/A',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
// Display individual activity details
public function show($id)
{
    $activity = ActivityLog::with('user')->findOrFail($id);

    return view('admin.activity-logs.show', compact('activity'));
}
    // Reusable search and filter query
    private function filteredQuery(Request $request)
    {
        $query = ActivityLog::query()->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('user_email', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }
}
