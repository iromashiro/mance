<?php

namespace App\Http\Controllers;

use App\Services\DesaApi;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;

class TourController extends Controller
{
    /**
     * Daftar wisata (tours) dari Desa API dengan pagination.
     */
    public function index(Request $request, DesaApi $desa)
    {
        $page = max(1, (int) $request->query('page', 1));

        $res   = $desa->toursPaginated($page);
        $items = collect($res['items'] ?? []);
        $meta  = $res['meta'] ?? [];

        $perPage = (int) ($meta['per_page'] ?? 10);
        $total   = (int) ($meta['total'] ?? $items->count());
        $current = (int) ($meta['current_page'] ?? $page);

        // Bungkus ke paginator Laravel agar Blade ->links() bisa dipakai
        $tours = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $current,
            ['path' => URL::current(), 'query' => $request->query()]
        );

        return view('tours.index', compact('tours'));
    }

    /**
     * Detail wisata + related.
     */
    public function show($id, DesaApi $desa)
    {
        $tour = $desa->tour($id);
        if (!$tour) {
            abort(404);
        }

        $mapsKey = env('GOOGLE_MAPS_KEY');

        // Related: ambil 4 item pertama selain yang sedang dibuka
        $related = collect($desa->toursPaginated(1)['items'] ?? [])
            ->filter(fn($t) => (string) ($t->id ?? '') !== (string) $id)
            ->take(4)
            ->values();

        return view('tours.show', [
            'tour'    => $tour,
            'related' => $related,
            'mapsKey' => $mapsKey,
        ]);
    }
}
