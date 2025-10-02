<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\ComplaintResponse;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    /**
     * Display listing of complaints for admin
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['user', 'category', 'responses']);

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Priority filter
        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Get statistics
        $statistics = [
            'pending' => Complaint::where('status', 'pending')->count(),
            'process' => Complaint::where('status', 'process')->count(),
            'completed' => Complaint::where('status', 'completed')->count(),
            'rejected' => Complaint::where('status', 'rejected')->count(),
        ];

        $complaints = $query->latest()->paginate(20);

        return view('admin.complaints.index', compact('complaints', 'statistics'));
    }

    /**
     * Display complaint details for admin
     */
    public function show(Complaint $complaint)
    {
        $complaint->load(['user', 'category', 'images', 'responses.user', 'votes']);

        return view('admin.complaints.show', compact('complaint'));
    }

    /**
     * Show edit form for complaint
     */
    public function edit(Complaint $complaint)
    {
        $categories = ComplaintCategory::where('is_active', true)->get();

        return view('admin.complaints.edit', compact('complaint', 'categories'));
    }

    /**
     * Update complaint status and details
     */
    public function update(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:pending,process,completed,rejected',
            'priority' => 'required|in:low,medium,high',
            'admin_notes' => 'nullable|string',
        ]);

        $oldStatus = $complaint->status;

        // Update complaint
        $complaint->update([
            'status' => $request->status,
            'priority' => $request->priority,
            'admin_notes' => $request->admin_notes,
        ]);

        // Update timestamps based on status
        if ($request->status === 'process' && $oldStatus === 'pending') {
            $complaint->update(['processed_at' => now()]);
        } elseif (in_array($request->status, ['completed', 'rejected'])) {
            $complaint->update(['completed_at' => now()]);
        }

        // Send notification to complaint owner
        $statusText = [
            'pending' => 'menunggu',
            'process' => 'sedang diproses',
            'completed' => 'selesai',
            'rejected' => 'ditolak',
        ];

        $complaint->user->notifications()->create([
            'title' => 'Update Status Pengaduan',
            'message' => 'Pengaduan Anda ' . $complaint->ticket_number . ' telah diubah statusnya menjadi: ' . $statusText[$request->status],
            'type' => 'complaint_update',
            'action_url' => route('complaints.show', $complaint),
        ]);

        return redirect()->route('admin.complaints.show', $complaint)
            ->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    /**
     * Add admin response to complaint
     */
    public function respond(Request $request, Complaint $complaint)
    {
        $request->validate([
            'response' => 'required|string|min:10',
        ]);

        // Create response
        $response = $complaint->responses()->create([
            'user_id' => Auth::id(),
            'response' => $request->response,
        ]);

        // Automatically update status to 'process' if still pending
        if ($complaint->status === 'pending') {
            $complaint->update([
                'status' => 'process',
                'processed_at' => now(),
            ]);
        }

        // Send notification to complaint owner
        $complaint->user->notifications()->create([
            'title' => 'Tanggapan Admin',
            'message' => 'Admin telah memberikan tanggapan pada pengaduan Anda: ' . $complaint->ticket_number,
            'type' => 'complaint_response',
            'action_url' => route('complaints.show', $complaint),
        ]);

        return back()->with('success', 'Tanggapan berhasil ditambahkan.');
    }

    /**
     * Bulk update complaints status
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'complaint_ids' => 'required|array',
            'complaint_ids.*' => 'exists:complaints,id',
            'status' => 'required|in:pending,process,completed,rejected',
        ]);

        Complaint::whereIn('id', $request->complaint_ids)
            ->update([
                'status' => $request->status,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    /**
     * Export complaints to CSV
     */
    public function export(Request $request)
    {
        $filename = 'complaints_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $complaints = Complaint::with(['user', 'category'])->get();

        $callback = function () use ($complaints) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['Ticket', 'User', 'Category', 'Title', 'Status', 'Priority', 'Created At']);

            // Data rows
            foreach ($complaints as $complaint) {
                fputcsv($file, [
                    $complaint->ticket_number,
                    $complaint->user->name,
                    $complaint->category->name,
                    $complaint->title,
                    $complaint->status,
                    $complaint->priority,
                    $complaint->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
