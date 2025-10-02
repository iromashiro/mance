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
     * Display listing of complaints
     */
    public function index(Request $request)
    {
        $complaints = Complaint::with(['user', 'category'])
            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->priority, function ($query) use ($request) {
                return $query->where('priority', $request->priority);
            })
            ->when($request->category, function ($query) use ($request) {
                return $query->where('category_id', $request->category);
            })
            ->when($request->search, function ($query) use ($request) {
                return $query->where(function ($q) use ($request) {
                    $q->where('ticket_number', 'like', '%' . $request->search . '%')
                        ->orWhere('title', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(20);

        // Get categories for filter
        $categories = ComplaintCategory::active()->get();

        // Get stats
        $stats = [
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'rejected' => Complaint::where('status', 'rejected')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'categories', 'stats'));
    }

    /**
     * Display complaint detail
     */
    public function show($id)
    {
        $complaint = Complaint::with([
            'user',
            'category',
            'images',
            'responses.admin',
            'votes'
        ])->findOrFail($id);

        // Calculate priority score
        $priorityScore = $this->calculatePriority($complaint);

        // Get response time if resolved
        $responseTime = null;
        if ($complaint->resolved_at) {
            $responseTime = $complaint->created_at->diffForHumans($complaint->resolved_at, true);
        }

        return view('admin.complaints.show', compact('complaint', 'priorityScore', 'responseTime'));
    }

    /**
     * Update complaint status
     */
    public function updateStatus(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,rejected',
            'priority' => 'nullable|in:low,normal,high,urgent',
            'resolution_note' => 'nullable|string',
        ]);

        // Check if status transition is allowed
        if (!$complaint->canTransitionTo($request->status)) {
            return back()->with('error', 'Status transition tidak diperbolehkan.');
        }

        DB::beginTransaction();

        try {
            $oldStatus = $complaint->status;

            $updateData = [
                'status' => $request->status,
            ];

            if ($request->priority) {
                $updateData['priority'] = $request->priority;
            }

            if ($request->status === 'resolved') {
                $updateData['resolved_at'] = now();
                $updateData['resolution_note'] = $request->resolution_note;
            }

            $complaint->update($updateData);

            // Create response record
            ComplaintResponse::create([
                'complaint_id' => $complaint->id,
                'admin_id' => Auth::id(),
                'message' => "Status diubah dari {$oldStatus} menjadi {$request->status}" .
                    ($request->resolution_note ? ". Catatan: {$request->resolution_note}" : ''),
                'status_changed_to' => $request->status,
                'is_public' => true,
            ]);

            // Send notification to user
            $statusText = [
                'pending' => 'Menunggu',
                'in_progress' => 'Sedang Diproses',
                'resolved' => 'Selesai',
                'rejected' => 'Ditolak',
            ];

            Notification::create([
                'user_id' => $complaint->user_id,
                'title' => 'Update Status Pengaduan',
                'message' => "Pengaduan Anda #{$complaint->ticket_number} telah diubah statusnya menjadi: {$statusText[$request->status]}",
                'type' => 'complaint',
                'data' => json_encode([
                    'complaint_id' => $complaint->id,
                    'ticket_number' => $complaint->ticket_number,
                    'new_status' => $request->status,
                    'old_status' => $oldStatus,
                ]),
            ]);

            DB::commit();

            return back()->with('success', 'Status pengaduan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Add response to complaint
     */
    public function respond(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        $request->validate([
            'message' => 'required|string',
            'is_public' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            // Create response
            ComplaintResponse::create([
                'complaint_id' => $complaint->id,
                'admin_id' => Auth::id(),
                'message' => $request->message,
                'is_public' => $request->boolean('is_public', true),
            ]);

            // Send notification to user
            if ($request->boolean('is_public', true)) {
                Notification::create([
                    'user_id' => $complaint->user_id,
                    'title' => 'Respon Pengaduan',
                    'message' => "Ada respon baru untuk pengaduan Anda #{$complaint->ticket_number}",
                    'type' => 'complaint',
                    'data' => json_encode([
                        'complaint_id' => $complaint->id,
                        'ticket_number' => $complaint->ticket_number,
                        'admin_name' => Auth::user()->name,
                    ]),
                ]);
            }

            DB::commit();

            return back()->with('success', 'Respon berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Calculate priority score
     */
    private function calculatePriority($complaint)
    {
        $score = 0;

        // Category weight
        $categoryWeights = [
            'keamanan' => 30,
            'kesehatan' => 25,
            'infrastruktur' => 20,
            'kebersihan' => 15,
        ];

        $score += $categoryWeights[$complaint->category->slug] ?? 10;

        // Vote score
        $score += $complaint->upvotes * 2;
        $score -= $complaint->downvotes;

        // Age factor (older = higher priority)
        $daysOld = $complaint->created_at->diffInDays(now());
        $score += min($daysOld * 3, 30);

        // Current priority weight
        $priorityWeights = [
            'urgent' => 40,
            'high' => 25,
            'normal' => 10,
            'low' => 0,
        ];

        $score += $priorityWeights[$complaint->priority] ?? 10;

        return $score;
    }
}
