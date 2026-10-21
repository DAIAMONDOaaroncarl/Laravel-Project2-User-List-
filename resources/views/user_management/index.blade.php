@extends('layouts.app')

@section('content')
<main class="min-h-screen bg-[#fbfaf8] px-4 py-6 font-sans text-[#102b4e] sm:px-8">
    <div class="mx-auto max-w-[1440px]">
        <header class="mb-5 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold">Users</h1>
                <p class="mt-1 text-sm text-slate-500">Manage the accounts you have added.</p>
            </div>
            <a href="{{ url('/user-management') }}"
                class="rounded-lg border border-[#d5deea] bg-white px-5 py-3 font-semibold hover:bg-slate-50">
                Add account
            </a>
        </header>

        @if (session('success'))
            <p class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800" role="status">
                {{ session('success') }}
            </p>
        @endif

        <div class="mb-4 flex flex-wrap items-center justify-between gap-4 rounded-[20px] border border-[#e5ddd3] bg-[#f5f2ed] px-5 py-3">
            <label class="flex min-h-12 cursor-pointer items-center gap-4">
                <input id="select-all-users" type="checkbox" class="h-5 w-5 accent-[#102b4e]" aria-label="Select all users on this page">
                <span class="text-lg font-medium">Select this page</span>
            </label>
            <form id="bulk-delete-form" action="{{ route('user-management.users.destroy-selected') }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete the selected accounts?')">
                @csrf
                @method('DELETE')
                <button id="delete-selected" type="submit" disabled
                    class="inline-flex min-h-12 items-center justify-center rounded-lg border border-red-300 bg-red-50 px-5 font-semibold text-red-700 hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-50">
                    Delete selected (0)
                </button>
            </form>
        </div>

        <div class="space-y-4">
            @forelse ($users as $user)
                <article class="flex flex-col gap-5 rounded-[20px] border border-[#e5ddd3] bg-white p-5 sm:p-7 lg:flex-row lg:items-center">
                    <div class="flex min-w-0 flex-1 items-center gap-4">
                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" form="bulk-delete-form"
                            class="user-selection h-5 w-5 shrink-0 accent-[#102b4e]" aria-label="Select {{ $user->name }}">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#102b4e] text-xl font-semibold text-white">
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h2 class="truncate text-lg font-semibold text-[#102b4e]">{{ $user->name }}</h2>
                            <p class="truncate text-base text-slate-500">{{ $user->email }}</p>
                            <p class="mt-2 text-sm text-slate-500">Account ID: {{ $user->id }}</p>
                            @if ($user->phone)
                                <p class="text-sm text-slate-500">Phone: {{ $user->phone }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                        <span class="min-w-16 text-sm font-semibold text-slate-700">{{ ucfirst($user->status) }}</span>
                        <a href="{{ route('user-management.users.edit', $user) }}"
                            class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg border border-[#d5deea] bg-white px-5 font-semibold hover:bg-slate-50">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Zm17.71-10.04a1 1 0 0 0 0-1.42l-2.5-2.5a1 1 0 0 0-1.42 0l-1.83 1.83 3.75 3.75 2-1.66Z"/>
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('user-management.users.destroy', $user) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this account?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg border border-red-300 bg-red-50 px-5 font-semibold text-red-700 hover:bg-red-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12ZM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4Z"/>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-[20px] border border-[#e5ddd3] bg-white px-6 py-12 text-center text-slate-500">
                    No users have been added yet.
                </div>
            @endforelse
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.querySelector('#select-all-users');
        const userSelections = document.querySelectorAll('.user-selection');
        const deleteSelected = document.querySelector('#delete-selected');

        if (!selectAll) return;

        function updateSelection() {
            const selectedCount = [...userSelections].filter(function (checkbox) {
                return checkbox.checked;
            }).length;

            selectAll.checked = userSelections.length > 0 && selectedCount === userSelections.length;
            selectAll.indeterminate = selectedCount > 0 && selectedCount < userSelections.length;
            deleteSelected.disabled = selectedCount === 0;
            deleteSelected.textContent = 'Delete selected (' + selectedCount + ')';
        }

        selectAll.addEventListener('change', function () {
            userSelections.forEach(function (checkbox) {
                checkbox.checked = selectAll.checked;
            });
            updateSelection();
        });

        userSelections.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                updateSelection();
            });
        });
    });
</script>
@endsection
