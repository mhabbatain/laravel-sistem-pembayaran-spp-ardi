<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $waliMurid = $user->waliMurid;
        return view('wali.profile.index', compact('user', 'waliMurid'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $waliMurid = $user->waliMurid;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        // Update user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Update wali murid
        $waliMurid->update([
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
        ]);

        return redirect()->route('wali.profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
