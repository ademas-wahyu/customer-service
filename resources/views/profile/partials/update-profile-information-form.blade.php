<section class="space-y-8">
    <div class="flex flex-col items-center gap-6 text-center md:flex-row md:items-end md:text-left">
        <div class="flex h-24 w-24 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-500 md:h-28 md:w-28">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-12 w-12 md:h-16 md:w-16">
                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.75 20.1a8.25 8.25 0 0116.5 0 .75.75 0 01-.75.9h-15a.75.75 0 01-.75-.9z" clip-rule="evenodd" />
            </svg>
        </div>

        <div class="space-y-1">
            <h2 class="text-2xl font-semibold text-gray-900">{{ __('Profil Pengguna') }}</h2>
            <p class="text-sm text-gray-500">{{ __('Perbarui informasi akun Anda untuk memastikan data tetap akurat.') }}</p>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <label for="access" class="block text-sm font-medium text-gray-700">{{ __('Hak Akses') }}</label>
                <input id="access" type="text" value="{{ $user->getRoleNames()->implode(', ') ?: '-' }}" disabled class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600" />
            </div>

            <div class="space-y-2">
                <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                <input id="status" type="text" value="{{ $user->email_verified_at ? __('Aktif') : __('Belum Aktif') }}" disabled class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600" />
            </div>

            <div class="space-y-2 md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Nama') }}</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name" class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" />
                <x-input-error class="text-sm text-red-500" :messages="$errors->get('name')" />
            </div>

            <div class="space-y-2 md:col-span-2">
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" />
                <x-input-error class="text-sm text-red-500" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-600">
                    <p>{{ __('Alamat email Anda belum terverifikasi.') }}</p>
                    <button form="send-verification" class="mt-2 inline-flex items-center text-sm font-medium text-amber-700 underline hover:text-amber-800 focus:outline-none">
                        {{ __('Kirim ulang tautan verifikasi') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-semibold text-amber-700">{{ __('Tautan verifikasi baru telah dikirimkan ke email Anda.') }}</p>
                    @endif
                </div>
                @endif
            </div>

            <div class="space-y-2 md:col-span-2">
                <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('Tlp') }}</label>
                <input id="phone" type="text" value="{{ $user->phone ?? '-' }}" disabled class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600" />
            </div>
        </div>

        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-end">
            @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2500)"
                class="text-sm text-green-600">{{ __('Perubahan berhasil disimpan.') }}</p>
            @endif

            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                    <path d="M17.75 3a.75.75 0 01.75.75V6h1.25a1 1 0 01.78 1.63l-2.54 3.05a.75.75 0 11-1.14-.97l1.1-1.32H15a.75.75 0 01-.75-.75V3.75A.75.75 0 0115 3h2.75z" />
                    <path d="M6.25 21a.75.75 0 01-.75-.75V18H4.25a1 1 0 01-.78-1.63l2.54-3.05a.75.75 0 011.14.97l-1.1 1.32H9a.75.75 0 01.75.75v4.25a.75.75 0 01-.75.75H6.25z" />
                    <path d="M9.75 5a.75.75 0 01.75-.75h3a.75.75 0 01.53 1.28L12.31 7.25h1.94a.75.75 0 010 1.5h-3a.75.75 0 01-.53-1.28l1.17-1.22H10.5A.75.75 0 019.75 5zm4.5 10.5a.75.75 0 00-.75-.75h-3a.75.75 0 000 1.5h1.94l-1.17 1.22a.75.75 0 00.53 1.28h3a.75.75 0 000-1.5h-1.94l1.17-1.22a.75.75 0 00-.53-1.28z" />
                </svg>
                {{ __('Ubah') }}
            </button>
        </div>
    </form>
</section>