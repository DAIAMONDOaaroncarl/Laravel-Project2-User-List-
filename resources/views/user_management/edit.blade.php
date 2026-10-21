@extends('layouts.app')

@section('content')
<main class="min-h-screen bg-white px-[30px] py-[56px] font-sans text-slate-900">
    <section class="mx-auto w-full max-w-[650px]">
        <header class="mb-7">
            <h1 class="text-2xl font-semibold">Edit account</h1>
            <p class="mt-1 text-base text-slate-500">Update the account details.</p>
        </header>

        <form action="{{ route('user-management.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div>
                    <label for="name" class="mb-2 block font-medium text-slate-700">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                        class="h-[55px] w-full rounded-xl border border-slate-300 px-4 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block font-medium text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                        class="h-[55px] w-full rounded-xl border border-slate-300 px-4 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="mb-2 block font-medium text-slate-700">Phone number</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}"
                        class="h-[55px] w-full rounded-xl border border-slate-300 px-4 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="mb-2 block font-medium text-slate-700">Status</label>
                    <div class="relative">
                        <select id="status" name="status" required
                            class="h-[55px] w-full appearance-none rounded-xl border border-slate-300 bg-white py-0 pl-4 pr-12 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                            <option value="active" @selected(old('status', $user->status) === 'active')>Active</option>
                            <option value="inactive" @selected(old('status', $user->status) === 'inactive')>Inactive</option>
                        </select>
                        <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-700"
                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.09 1.032l-4.25 4.5a.75.75 0 0 1-1.09 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <footer class="mt-6 flex justify-end gap-4 border-t border-slate-200 pt-5">
                <a href="{{ route('user-management.users.add') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 font-medium hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit"
                    class="rounded-xl bg-slate-900 px-5 py-3 font-semibold text-white hover:bg-slate-800">
                    Save changes
                </button>
            </footer>
        </form>
    </section>
</main>
@endsection
