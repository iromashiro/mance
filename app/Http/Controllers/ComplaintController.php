<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\ComplaintImage;
use App\Models\ComplaintVote;
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
        $user = Auth::user();

        // Get user's complaints
        $myComplaints = $user->complaints()
            ->with(['category', 'images'])
            ->latest()
            ->paginate(10);

        // Get public complaints
        $publicComplaints = Complaint::public()
            ->with(['category', 'user', 'images', 'votes'])
            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->category, function ($query) use ($request) {
                return $query->where('category_id', $request->category);
            })
            ->latest()
            ->paginate(20);

        // Get categories for filter
        $categories = ComplaintCategory::active()->get();

        return view('complaints.index', compact(
            'myComplaints',
            'publicComplaints',
            'categories'
        ));
    }

    /**
     * Show create complaint form
     */
    public function create()
    {
        $categories = ComplaintCategory::active()
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
            'title' => 'required|string|max:500',
            'description' => 'required|string',
            'location_address' => 'nullable|string',
            'location_lat' => 'nullable|numeric|between:-90,90',
            'location_lng' => 'nullable|numeric|between:-180,180',
            'is_public' => 'boolean',
            'images' => 'nullable|array|max:3',
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
                'location_address' => $request->location_address,
                'location_lat' => $request->location_lat,
                'location_lng' => $request->location_lng,
                'is_public' => $request->boolean('is_public', true),
                'status' => 'pending',
                'priority' => 'normal',
            ]);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('complaints/' . $complaint->id, 'public');

                    ComplaintImage::create([
                        'complaint_id' => $complaint->id,
                        'image_path' => $path,
                        'upload_order' => $index + 1,
                    ]);
                }
            }

            // Log activity
            Auth::user()->activities()->create([
                'action' => 'create_complaint',
                'entity_type' => 'complaint',
                'entity_id' => $complaint->id,
                'ip_address' => $request->ip(),
                'metadata' => json_encode([
                    'ticket_number' => $complaint->ticket_number,
                    'category' => $complaint->category->name,
                ]),
            ]);

            // Create notification for user
            Auth::user()->notifications()->create([
                'title' => 'Pengaduan Berhasil Dibuat',
                'message' => 'Pengaduan Anda dengan nomor tiket ' . $complaint->ticket_number . ' telah berhasil dibuat dan akan segera diproses.',
                'type' => 'complaint',
                'data' => json_encode([
                    'complaint_id' => $complaint->id,
                    'ticket_number' => $complaint->ticket_number,
                ]),
            ]);

            DB::commit();

            return redirect()->route('complaints.show', $complaint->ticket_number)
                ->with('success', 'Pengaduan berhasil dibuat dengan nomor tiket: ' . $complaint->ticket_number);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat membuat pengaduan.');
        }
    }

    /**
     * Display complaint details
     */
    public function show($ticket_number)
    {
        $complaint = Complaint::where('ticket_number', $ticket_number)
            ->with(['category', 'user', 'images', 'responses.admin', 'votes'])
            ->firstOrFail();

        // Check if user can view this complaint
        if (!$complaint->is_public && $complaint->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Check if user has voted
        $userVote = null;
        if (Auth::check()) {
            $userVote = $complaint->votes()
                ->where('user_id', Auth::id())
                ->first();
        }

        return view('complaints.show', compact('complaint', 'userVote'));
    }

    /**
     * Vote on complaint
     */
    public function vote(Request $request, $id)
    {
        $request->validate([
            'vote_type' => 'required|in:up,down',
        ]);

        $complaint = Complaint::findOrFail($id);

        // Check if complaint is public
        if (!$complaint->is_public) {
            return response()->json(['error' => 'Cannot vote on private complaints'], 403);
        }

        // Check existing vote
        $existingVote = ComplaintVote::where('complaint_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingVote) {
            if ($existingVote->vote_type === $request->vote_type) {
                // Remove vote if same type
                $existingVote->delete();
                $message = 'Vote removed';
            } else {
                // Update vote type
                $existingVote->update(['vote_type' => $request->vote_type]);
                $message = 'Vote updated';
            }
        } else {
            // Create new vote
            ComplaintVote::create([
                'complaint_id' => $id,
                'user_id' => Auth::id(),
                'vote_type' => $request->vote_type,
            ]);
            $message = 'Vote recorded';
        }

        // Recalculate priority (optional)
        // $this->recalculatePriority($complaint);

        return response()->json([
            'message' => $message,
            'upvotes' => $complaint->fresh()->upvotes,
            'downvotes' => $complaint->fresh()->downvotes,
        ]);
    }

    /**
     * Rate complaint resolution
     */
    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        $complaint = Complaint::where('user_id', Auth::id())
            ->where('status', 'resolved')
            ->findOrFail($id);

        $complaint->update([
            'satisfaction_rating' => $request->rating,
        ]);

        return back()->with('success', 'Terima kasih atas penilaian Anda.');
    }
}
