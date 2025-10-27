<div x-data="akunCsPage(@entangle('showCreateModal').live)" x-cloak>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Daftar Akun CS</h1>
                    <p class="mt-1 text-sm text-gray-500">Kelola akun tim CS dan pantau performanya dalam satu tempat.</p>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <x-primary-button type="button" wire:click="create"
                        class="inline-flex items-center gap-2 bg-navy-700 hover:bg-navy-800 font-medium px-4 py-2">
                        <x-icons.plus class="h-5 w-5" aria-hidden="true" />
                        <span>Tambah Akun</span>
                    </x-primary-button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse ($users as $user)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col" wire:key="user-{{ $user->id }}">

                    <div class="p-6 flex items-center space-x-4">
                        @if ($user->profile_photo_url)
                        <img class="h-16 w-16 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                        @else
                        <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                            <x-icons.person class="h-10 w-10 text-gray-500" />
                        </div>
                        @endif
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->kode_cs }}</p>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-b border-gray-200 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Closing</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $user->closing }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-500">Poin</p>

                            @if ($user->poinDifference > 0)
                            {{-- POIN LEBIH (HIJAU) --}}
                            <p class="text-2xl font-bold text-green-600">{{ number_format($user->poin, 1, ',', '.') }}</p>
                            <span class="text-sm font-medium text-green-600">(+{{ number_format($user->poinDifference, 1, ',', '.') }})</span>

                            @elseif ($user->poinDifference < 0)
                                {{-- POIN KURANG (MERAH) --}}
                                <p class="text-2xl font-bold text-red-600">{{ number_format($user->poin, 1, ',', '.') }}</p>
                                <span class="text-sm font-medium text-red-600">({{ number_format($user->poinDifference, 1, ',', '.') }})</span>

                                @else
                                {{-- POIN PAS (NEUTRAL) --}}
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($user->poin, 1, ',', '.') }}</p>
                                @endif
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex justify-between text-sm font-medium text-gray-500 mb-1">
                            <span>Progress Poin</span>
                            <span>{{ number_format($user->poin, 1, ',', '.') }} / {{ number_format($targetPoin, 0) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full"
                                style="width: {{ $user->poinPercentage }}%; background-color: hsl({{ $user->poinColorHue }}, 90%, 45%);">
                            </div>
                        </div>
                        <div class="p-6 border-t border-gray-200 bg-gray-50">
                            @php
                                $modalUser = [
                                    'id' => $user->id,
                                    'name' => $user->name,
                                    'kode_cs' => $user->kode_cs,
                                    'closing' => $user->closing,
                                    'poin' => $user->poin,
                                    'poinDifference' => $user->poinDifference,
                                    'waitingList' => $user->waitingList,
                                    'profile_photo_url' => $user->profile_photo_url,
                                    'chart' => $user->chart,
                                ];
                            @endphp

                            <button type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-navy-700 text-base font-medium text-white hover:bg-navy-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500 sm:text-sm"
                                @click="openDetail(@js($modalUser))">
                                Detail
                            </button>
                        </div>
                    </div>

                </div>
                @empty
                <div class="lg:col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        Belum ada data Akun CS untuk ditampilkan.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0">
        <div class="fixed inset-0 bg-gray-900/60" @click="closeModal"></div>

        <div x-show="showModal" x-transition class="relative w-full max-w-3xl">
            <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-xl">
                {{-- ... Konten modal detail (anda sudah punya ini) ... --}}
                <div class="relative bg-gradient-to-r from-navy-600 via-navy-700 to-navy-800 px-8 py-10 text-white">
                    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-5">
                            <template x-if="selectedUser?.profile_photo_url">
                                <img class="h-20 w-20 rounded-full border-4 border-white/20 object-cover"
                                    :src="selectedUser.profile_photo_url" :alt="selectedUser.name">
                            </template>
                            <template x-if="!selectedUser?.profile_photo_url">
                                <div class="flex h-20 w-20 items-center justify-center rounded-full border-4 border-white/20 bg-white/10">
                                    <x-icons.person class="h-12 w-12 text-white/70" />
                                </div>
                            </template>

                            <div>
                                <h3 class="text-2xl font-semibold leading-tight" x-text="selectedUser?.name"></h3>
                                <p class="mt-1 text-sm font-medium text-white/70" x-text="selectedUser?.kode_cs"></p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <span class="rounded-full bg-white/15 px-4 py-2 text-sm font-semibold" x-text="'Poin ' + formatNumber(selectedUser?.poin ?? 0, 1)"></span>
                            <span class="rounded-full px-4 py-2 text-sm font-semibold"
                                :class="selectedUser?.poinDifference > 0 ? 'bg-emerald-500/15 text-emerald-300' : (selectedUser?.poinDifference < 0 ? 'bg-rose-500/15 text-rose-300' : 'bg-white/15 text-white/80')"
                                x-text="formattedDifference"></span>
                        </div>
                    </div>

                    <div class="pointer-events-none absolute -bottom-20 -right-16 h-52 w-52 rounded-full bg-white/10 blur-3xl"></div>
                </div>

                <div class="-mt-10 space-y-8 px-8 pb-10">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="rounded-2xl border border-gray-100 bg-white px-5 py-5 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">Total Closingan</p>
                            <p class="mt-3 text-3xl font-semibold text-gray-900" x-text="formatNumber(selectedUser?.closing ?? 0)"></p>
                            <p class="mt-1 text-xs text-gray-400">Deal berhasil minggu ini</p>
                        </div>

                        <div class="rounded-2xl border border-gray-100 bg-white px-5 py-5 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">Total Waiting List</p>
                            <p class="mt-3 text-3xl font-semibold text-gray-900" x-text="formatNumber(selectedUser?.waitingList ?? 0)"></p>
                            <p class="mt-1 text-xs text-gray-400">Prospek menunggu tindak lanjut</p>
                        </div>

                        <div class="rounded-2xl border border-gray-100 bg-white px-5 py-5 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">Poin</p>
                            <p class="mt-3 text-3xl font-semibold text-gray-900" x-text="formatNumber(selectedUser?.poin ?? 0, 1)"></p>
                            <p class="mt-1 text-xs text-gray-400" x-text="'Target {{ number_format($targetPoin, 0) }}'"></p>
                        </div>
                    </div>

                    <template x-if="selectedUser?.chart">
                        <div class="rounded-2xl border border-gray-100 bg-white px-6 py-6 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">Statistics</h4>
                                    <p class="text-sm text-gray-500">Performa closing harian</p>
                                </div>
                                <span class="rounded-full bg-navy-50 px-3 py-1 text-xs font-semibold text-navy-700">Closing</span>
                            </div>

                            <div class="mt-6">
                                <svg :viewBox="'0 0 ' + selectedUser.chart.width + ' ' + selectedUser.chart.height" preserveAspectRatio="xMidYMid meet"
                                    class="h-48 w-full" role="img" aria-label="Grafik performa closing">
                                    <defs>
                                        <linearGradient :id="chartGradientId" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#1d4ed8" stop-opacity="0.45" />
                                            <stop offset="100%" stop-color="#1d4ed8" stop-opacity="0" />
                                        </linearGradient>
                                    </defs>

                                    <path :d="selectedUser.chart.area" :fill="'url(#' + chartGradientId + ')'" />
                                    <path :d="selectedUser.chart.path" fill="none" stroke="#1d4ed8" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round"></path>

                                    <template x-for="point in selectedUser.chart.points" :key="point.x + '-' + point.y">
                                        <g>
                                            <circle :cx="point.x" :cy="point.y" r="5" fill="#1d4ed8"></circle>
                                            <circle :cx="point.x" :cy="point.y" r="10" fill="#1d4ed8" opacity="0.12"></circle>
                                        </g>
                                    </template>
                                </svg>
                            </div>

                            <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                <div class="flex items-center gap-2">
                                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-navy-600"></span>
                                    <span x-text="'Rata-rata ' + formatNumber(selectedUser.chart.average ?? 0, 1)"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-gray-300"></span>
                                    <span x-text="'Tertinggi ' + formatNumber(selectedUser.chart.max ?? 0)"></span>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-between text-xs font-semibold uppercase tracking-wide text-gray-400">
                                <template x-for="label in selectedUser.chart.labels" :key="label">
                                    <span x-text="label"></span>
                                </template>
                            </div>
                        </div>
                    </template>

                    <div class="flex justify-end pt-2">
                        <x-secondary-button @click="closeModal">
                            Tutup
                        </x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- [!!] MODAL CREATE BARU --}}
    <div x-show="showCreateModal" x-cloak x-transition.opacity.duration.200ms
         @keydown.escape.window="$wire.closeCreateModal()"
         class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0">
        <div class="fixed inset-0 bg-gray-900/60" @click="$wire.closeCreateModal()" aria-hidden="true"></div>

        <div x-show="showCreateModal" x-transition.scale.origin-top duration-200
             x-trap.noscroll="showCreateModal"
             class="relative w-full max-w-xl bg-white shadow-xl rounded-2xl overflow-hidden"
             role="dialog" aria-modal="true" aria-labelledby="create-account-title">

            {{-- Form di dalam modal --}}
            <form wire:submit="saveUser" class="flex flex-col" x-ref="createForm">
                <div class="p-6">
                    <h3 id="create-account-title" class="text-lg font-semibold text-gray-900">Tambah Akun CS Baru</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Buat akun baru dan tentukan rolenya sesuai kebutuhan operasional.
                    </p>

                    <div class="mt-6 space-y-4">
                        {{-- Nama --}}
                        <div>
                            <x-input-label for="create_name" value="Nama Lengkap" />
                            <x-text-input x-ref="createName" wire:model.live="form.name" id="create_name" type="text" class="mt-1 block w-full" autocomplete="name" />
                            <x-input-error :messages="$errors->get('form.name')" class="mt-2" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <x-input-label for="create_email" value="Email" />
                            <x-text-input wire:model.live="form.email" id="create_email" type="email" class="mt-1 block w-full" autocomplete="email" />
                            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                        </div>

                        {{-- Role --}}
                        <div>
                            <x-input-label for="create_role" value="Role" />
                            <select wire:model.live="form.role" id="create_role" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm">
                                <option value="" disabled>Pilih role...</option>
                                {{-- Loop data roles dari komponen PHP --}}
                                @foreach($roles as $roleName)
                                    <option value="{{ $roleName }}">{{ $roleName }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('form.role')" class="mt-2" />
                        </div>

                        {{-- Password --}}
                        <div>
                            <x-input-label for="create_password" value="Password" />
                            <x-text-input wire:model.live="form.password" id="create_password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <x-input-label for="create_password_confirmation" value="Konfirmasi Password" />
                            <x-text-input wire:model.live="form.password_confirmation" id="create_password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 bg-gray-50 px-6 py-4 sm:flex-row sm:justify-end">
                    <x-secondary-button type="button" @click="$wire.closeCreateModal()">
                        Batal
                    </x-secondary-button>
                    <x-primary-button type="submit" class="inline-flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="saveUser">
                            Simpan User
                        </span>
                        <span wire:loading wire:target="saveUser" class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>Menyimpan...</span>
                        </span>
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('akunCsPage', (createModalState) => ({
                showModal: false,
                selectedUser: null,
                chartGradientId: null,
                formattedDifference: '',

                showCreateModal: createModalState ?? false,

                init() {
                    this.$watch('showModal', value => this.toggleBodyScroll(value));
                    this.$watch('showCreateModal', value => {
                        this.toggleBodyScroll(value);

                        if (value) {
                            this.$nextTick(() => this.$refs.createName?.focus());
                        }
                    });

                    const registerLivewireListeners = () => {
                        window.Livewire.on('open-create-modal', () => this.openCreateModal());
                        window.Livewire.on('close-create-modal', () => this.closeCreateModal());
                    };

                    if (window.Livewire?.on) {
                        registerLivewireListeners();
                    } else {
                        document.addEventListener('livewire:init', registerLivewireListeners, { once: true });
                    }
                },

                openDetail(user) {
                    this.selectedUser = user;
                    this.chartGradientId = `chartGradient-${user.id}`;
                    this.showModal = true;
                    this.formattedDifference = this.formatDifference(user.poinDifference ?? 0);
                    document.body.classList.add('overflow-y-hidden');
                },
                closeModal() {
                    this.showModal = false;
                    this.selectedUser = null;
                    this.formattedDifference = '';
                    this.toggleBodyScroll(false);
                },
                openCreateModal() {
                    this.showCreateModal = true;
                    this.$nextTick(() => this.$refs.createName?.focus());
                },
                closeCreateModal() {
                    this.showCreateModal = false;
                    this.toggleBodyScroll(false);
                },
                formatNumber(value, decimals = 0) {
                    const formatter = new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals
                    });

                    return formatter.format(value ?? 0);
                },
                formatDifference(value) {
                    const prefix = value > 0 ? '+' : (value < 0 ? '' : ''); // Hapus minus jika tidak diperlukan
                    return prefix + this.formatNumber(value ?? 0, 1);
                },
                toggleBodyScroll(value) {
                    if (value || this.showModal || this.showCreateModal) {
                        document.body.classList.add('overflow-y-hidden');
                        return;
                    }

                    document.body.classList.remove('overflow-y-hidden');
                }
            }));
        });
    </script>
@endonce
