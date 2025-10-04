<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\ComplaintImage;
use App\Models\ComplaintVote;
use App\Models\ComplaintResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    /**
     * Display listing of complaints
     */
    public function index(Request $request)
    {
        $query = Auth::user()->complaints()->with(['category', 'responses', 'votes']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $complaints = $query->latest()->paginate(10);

        return view('complaints.index', compact('complaints'));
    }

    /**
     * Show create complaint form
     */
    public function create()
    {
        $categories = ComplaintCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('complaints.create', compact('categories'));
    }

    /**
     * Store new complaint
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:complaint_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'location' => 'required|string|max:500',
            'priority' => 'required|in:low,medium,high',
            'is_private' => 'nullable|boolean',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Create complaint
            $complaint = Auth::user()->complaints()->create([
                'ticket_number' => Complaint::generateTicketNumber(),
                'category_id' => $request->category_id,
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'priority' => $request->priority,
                'is_private' => $request->boolean('is_private', false),
                'status' => 'pending',
            ]);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('complaints/' . $complaint->id, 'public');

                    $complaint->images()->create([
                        'image_url' => $path,
                    ]);
                }
            }

            // Create notification
            Auth::user()->notifications()->create([
                'title' => 'Pengaduan Berhasil Dibuat',
                'message' => 'Pengaduan Anda dengan nomor tiket ' . $complaint->ticket_number . ' telah berhasil dibuat.',
                'type' => 'complaint',
                'action_url' => route('complaints.show', $complaint),
            ]);

            DB::commit();

            return redirect()->route('complaints.show', $complaint)
                ->with('success', 'Pengaduan berhasil dibuat dengan nomor tiket: ' . $complaint->ticket_number);
        } catch (\Exception $e) {
            \dd($e);
            DB::rollback();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat membuat pengaduan.');
        }
    }

    /**
     * Display complaint details
     */
    public function show(Complaint $complaint)
    {
        // Check if user can view this complaint
        if ($complaint->is_private && $complaint->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini.');
        }

        $complaint->load(['category', 'user', 'images', 'responses.user', 'votes']);

        return view('complaints.show', compact('complaint'));
    }

    /**
     * Vote on complaint
     */
    public function vote(Request $request, Complaint $complaint)
    {
        // Check if complaint is private
        if ($complaint->is_private) {
            return back()->with('error', 'Tidak dapat memberikan dukungan pada pengaduan privat.');
        }

        // Check existing vote
        $existingVote = $complaint->votes()
            ->where('user_id', Auth::id())
            ->first();

        if ($existingVote) {
            // Remove vote if already voted
            $existingVote->delete();
            $message = 'Dukungan dibatalkan';
        } else {
            // Create new vote
            $complaint->votes()->create([
                'user_id' => Auth::id(),
                'is_upvote' => true,
            ]);
            $message = 'Terima kasih atas dukungan Anda';
        }

        return back()->with('success', $message);
    }

    /**
     * Add response to complaint
     */
    public function addResponse(Request $request, Complaint $complaint)
    {
        $request->validate([
            'response' => 'required|string|min:10',
        ]);

        // Check if user can respond
        if ($complaint->status == 'completed' || $complaint->status == 'rejected') {
            return back()->with('error', 'Tidak dapat menambahkan tanggapan pada pengaduan yang sudah selesai.');
        }

        // Only complaint owner or admin can respond
        if ($complaint->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk memberikan tanggapan.');
        }

        $complaint->responses()->create([
            'user_id' => Auth::id(),
            'response' => $request->response,
        ]);

        // Create notification for complaint owner if admin responded
        if (Auth::user()->isAdmin() && $complaint->user_id !== Auth::id()) {
            $complaint->user->notifications()->create([
                'title' => 'Tanggapan Admin',
                'message' => 'Admin telah memberikan tanggapan pada pengaduan Anda: ' . $complaint->ticket_number,
                'type' => 'complaint_update',
                'action_url' => route('complaints.show', $complaint),
            ]);
        }

        return back()->with('success', 'Tanggapan berhasil ditambahkan.');
    }

    /**
     * Track complaint access (for public viewing)
     */
    public function track(Request $request)
    {
        $request->validate([
            'ticket_number' => 'required|string',
        ]);

        $complaint = Complaint::where('ticket_number', $request->ticket_number)
            ->where('is_private', false)
            ->first();

        if (!$complaint) {
            return back()->with('error', 'Nomor tiket tidak ditemukan atau pengaduan bersifat privat.');
        }

        return redirect()->route('complaints.show', $complaint);
    }
}
