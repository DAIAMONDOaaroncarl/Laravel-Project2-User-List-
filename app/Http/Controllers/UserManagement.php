<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserManagement extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email', 'phone', 'status']);

        return view('user_management.index', compact('users'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $user = User::create($validated);

        return response()->json([
            'message' => 'Account saved successfully.',
            'user' => $user->only(['id', 'name', 'email', 'phone', 'status']),
        ], 201);
    }
    public function add()
    {
        return view('user_management.add');
    }
    public function edit(User $user)
    {
        return view('user_management.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $user->update($validated);

        return redirect()
            ->route('user-management.users.index')
            ->with('success', 'Account updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('user-management.users.index')
            ->with('success', 'Account deleted successfully.');
    }

    public function destroySelected(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['required', 'integer', 'distinct', 'exists:users,id'],
        ]);

        $deletedCount = User::whereIn('id', $validated['user_ids'])->delete();

        return redirect()
            ->route('user-management.users.index')
            ->with('success', $deletedCount . ' selected account(s) deleted successfully.');
    }
}
