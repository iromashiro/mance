<?php

namespace App\Http\Controllers;

use App\Models\UserFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Tampilkan daftar layanan favorit user
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $favorites = UserFavorite::with(['application', 'application.categories'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }
}
