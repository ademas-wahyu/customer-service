<div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="rounded-2xl bg-white px-6 py-8 shadow-md sm:p-10">
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

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="access" class="block text-sm font-medium text-gray-700">{{ __('Hak Akses') }}</label>
                        <input id="access" type="text" value="{{ $this->roles }}" disabled class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600" />
                    </div>

                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                        <input id="status" type="text" value="{{ $this->isEmailVerified ? __('Aktif') : __('Belum Aktif') }}" disabled class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600" />
                    </div>
                </div>

                <form wire:submit.prevent="updateProfile" class="space-y-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2 md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Nama') }}</label>
                            <input id="name" type="text" wire:model.defer="name" required autocomplete="name" class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" />
                            <x-input-error class="text-sm text-red-500" :messages="$errors->get('name')" />
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                            <input id="email" type="email" wire:model.defer="email" required autocomplete="username" class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" />
                            <x-input-error class="text-sm text-red-500" :messages="$errors->get('email')" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-600">
                                    <p>{{ __('Alamat email Anda belum terverifikasi.') }}</p>
                                    <button type="button" wire:click="sendVerification" class="mt-2 inline-flex items-center text-sm font-medium text-amber-700 underline hover:text-amber-800 focus:outline-none">
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
                                class="text-sm text-green-600"
                            >{{ __('Perubahan berhasil disimpan.') }}</p>
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
        </div>

        <div class="rounded-2xl bg-white px-6 py-8 shadow-md sm:p-10">
            <section>
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Update Password') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Ensure your account is using a long, random password to stay secure.') }}
                    </p>
                </header>

                <form wire:submit.prevent="updatePassword" class="mt-6 space-y-6">
                    <div>
                        <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                        <x-text-input id="update_password_current_password" type="password" wire:model.defer="passwordForm.current_password" class="mt-1 block w-full" autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('passwordForm.current_password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="update_password_password" :value="__('New Password')" />
                        <x-text-input id="update_password_password" type="password" wire:model.defer="passwordForm.password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('passwordForm.password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="update_password_password_confirmation" type="password" wire:model.defer="passwordForm.password_confirmation" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('passwordForm.password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>

                        @if (session('status') === 'password-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600"
                            >{{ __('Saved.') }}</p>
                        @endif
                    </div>
                </form>
            </section>
        </div>

        <div class="rounded-2xl bg-white px-6 py-8 shadow-md sm:p-10">
            <section class="space-y-6">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Delete Account') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                    </p>
                </header>

                <x-danger-button
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                >{{ __('Delete Account') }}</x-danger-button>

                <x-modal name="confirm-user-deletion" :show="$errors->has('deletePassword')" focusable>
                    <form wire:submit.prevent="deleteAccount" class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Are you sure you want to delete your account?') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <div class="mt-6">
                            <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                            <x-text-input
                                id="password"
                                type="password"
                                class="mt-1 block w-3/4"
                                wire:model.defer="deletePassword"
                                placeholder="{{ __('Password') }}"
                            />

                            <x-input-error :messages="$errors->get('deletePassword')" class="mt-2" />
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-danger-button class="ms-3">
                                {{ __('Delete Account') }}
                            </x-danger-button>
                        </div>
                    </form>
                </x-modal>
            </section>
        </div>
    </div>
</div>
