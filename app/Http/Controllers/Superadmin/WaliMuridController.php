<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\WaliMurid;
use Illuminate\Http\Request;

class WaliMuridController extends Controller
{
    /**
     * Display a listing of wali santri.
     */
    public function index(Request $request)
    {
        $query = WaliMurid::with(['user', 'siswa']);

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $waliMurid = $query->latest()->paginate(10)->withQueryString();

        return view('superadmin.wali-murid.index', compact('waliMurid'));
    }

    /**
     * Display the specified wali santri.
     */
    public function show(WaliMurid $waliMurid)
    {
        $waliMurid->load(['user', 'siswa.tagihan']);
        
        return view('superadmin.wali-murid.show', compact('waliMurid'));
    }
}
