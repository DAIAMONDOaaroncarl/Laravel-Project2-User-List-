@extends('layouts.app')

@section('content')
<main class="min-h-screen bg-white px-[30px] py-[56px] font-sans text-slate-900">
    <section class="mx-auto w-full max-w-[650px]">
        <header class="mb-7">
            <h1 class="text-2xl font-semibold">Add account</h1>
            <p class="mt-1 text-base text-slate-500">
                Enter the new account details.
            </p>
        </header>

        <form id="account-form" action="{{ route('user-management.users.store') }}" method="POST">
            @csrf
            <div id="form-message" class="mb-4 hidden rounded-xl px-4 py-3" role="status" aria-live="polite"></div>

            <div class="space-y-5">
            <div>
                <label for="name" class="mb-2 block font-medium text-slate-700">Name</label>
                <input id="name" name="name" type="text" required
                    class="h-[55px] w-full rounded-xl border border-slate-300 px-4 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                <p data-error-for="name" class="mt-1 text-sm text-red-600"></p>
            </div>

            <div>
                <label for="email" class="mb-2 block font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" required
                    class="h-[55px] w-full rounded-xl border border-slate-300 px-4 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                <p data-error-for="email" class="mt-1 text-sm text-red-600"></p>
            </div>

            <div>
                <label for="phone" class="mb-2 block font-medium text-slate-700">Phone number</label>
                <input id="phone" name="phone" type="tel"
                    class="h-[55px] w-full rounded-xl border border-slate-300 px-4 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                <p data-error-for="phone" class="mt-1 text-sm text-red-600"></p>
            </div>

            <div>
                <label for="status" class="mb-2 block font-medium text-slate-700">Status</label>
                <div class="relative">
                    <select id="status" name="status" required
                        class="h-[55px] w-full appearance-none rounded-xl border border-slate-300 bg-white py-0 pl-4 pr-12 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-700"
                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.09 1.032l-4.25 4.5a.75.75 0 0 1-1.09 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p data-error-for="status" class="mt-1 text-sm text-red-600"></p>
            </div>
            </div>

            <footer class="mt-6 flex justify-end gap-4 border-t border-slate-200 pt-5">
                <a href="{{ route('user-management.users.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 font-medium hover:bg-slate-50">
                    Saved accounts
                </a>
                <button id="save-account" type="submit"
                    class="rounded-xl bg-slate-900 px-5 py-3 font-semibold text-white hover:bg-slate-800">
                    Save account
                </button>
            </footer>
        </form>
    </section>
</main>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('#account-form');
        if (!form) return;

        const message = document.querySelector('#form-message');
        const saveButton = document.querySelector('#save-account');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function clearFeedback() {
            message.textContent = '';
            message.classList.add('hidden');
            message.classList.remove('bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
            form.querySelectorAll('[data-error-for]').forEach(function (error) {
                error.textContent = '';
            });
        }

        function showMessage(text, isSuccess) {
            message.textContent = text;
            message.classList.remove('hidden');
            message.classList.add(isSuccess ? 'bg-green-100' : 'bg-red-100');
            message.classList.add(isSuccess ? 'text-green-800' : 'text-red-800');
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            clearFeedback();
            saveButton.disabled = true;
            saveButton.textContent = 'Saving...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: new FormData(form)
                });
                const result = await response.json();

                if (response.status === 422 && result.errors) {
                    Object.entries(result.errors).forEach(function ([field, errors]) {
                        const error = form.querySelector('[data-error-for="' + field + '"]');
                        if (error) error.textContent = errors[0];
                    });
                    showMessage('Please correct the highlighted fields.', false);
                    return;
                }

                if (!response.ok) {
                    throw new Error(result.message || 'The account could not be saved.');
                }

                form.reset();
                showMessage(result.message, true);
            } catch (error) {
                showMessage(error.message || 'Unable to save the account. Please try again.', false);
            } finally {
                saveButton.disabled = false;
                saveButton.textContent = 'Save account';
            }
        });
    });
</script>
@endpush
