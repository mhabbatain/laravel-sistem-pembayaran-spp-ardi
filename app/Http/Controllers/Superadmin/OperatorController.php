<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OperatorController extends Controller
{
    /**
     * Display a listing of operators.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'admin');

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $operators = $query->latest()->paginate(10)->withQueryString();

        return view('superadmin.operator.index', compact('operators'));
    }

    /**
     * Show the form for creating a new operator.
     */
    public function create()
    {
        return view('superadmin.operator.create');
    }

    /**
     * Store a newly created operator.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'no_hp' => $validated['no_hp'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('superadmin.operator.index')
            ->with('success', 'Operator berhasil ditambahkan');
    }

    /**
     * Display the specified operator.
     */
    public function show(User $operator)
    {
        if ($operator->role !== 'admin') {
            abort(404);
        }

        return view('superadmin.operator.show', compact('operator'));
    }

    /**
     * Show the form for editing the specified operator.
     */
    public function edit(User $operator)
    {
        if ($operator->role !== 'admin') {
            abort(404);
        }

        return view('superadmin.operator.edit', compact('operator'));
    }

    /**
     * Update the specified operator.
     */
    public function update(Request $request, User $operator)
    {
        if ($operator->role !== 'admin') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($operator->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $operator->update($data);

        return redirect()->route('superadmin.operator.index')
            ->with('success', 'Operator berhasil diupdate');
    }

    /**
     * Remove the specified operator.
     */
    public function destroy(User $operator)
    {
        if ($operator->role !== 'admin') {
            abort(404);
        }

        // Prevent self-deletion
        if ($operator->id === auth()->id()) {
            return redirect()->route('superadmin.operator.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $operator->delete();

        return redirect()->route('superadmin.operator.index')
            ->with('success', 'Operator berhasil dihapus');
    }
}
