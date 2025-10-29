<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($statCards as $card)
                <article
                    class="bg-white border border-gray-100 rounded-3xl shadow-sm p-6 flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                    <div class="space-y-3">
                        <div class="text-[16px] font-medium text-gray-500">{{ $card['title'] }}</div>
                        <div class="text-[18px] font-semibold text-gray-900">{{ $card['value'] }}</div>
                        <div class="text-[16px] text-gray-400">{{ $card['subtitle'] }}</div>
                        <div
                            class="flex items-center text-[12px] gap-2 text-sm
                            @switch($card['trend']['direction'])
                                @case('down') text-rose-200 @break
                                @case('flat') text-gray-400 @break
                                @default text-emerald-500
                            @endswitch">
                            @switch($card['trend']['direction'])
                                @case('down')
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 fill-current">
                                        <path
                                            d="M12 3a1 1 0 0 1 1 1v12.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 1 1 1.4-1.42L11 16.59V4a1 1 0 0 1 1-1Z" />
                                    </svg>
                                @break

                                @case('flat')
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 fill-current">
                                        <path d="M4 11h16a1 1 0 1 1 0 2H4a1 1 0 1 1 0-2Z" />
                                    </svg>
                                @break

                                @default
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 fill-current">
                                        <path
                                            d="M12 21a1 1 0 0 1-1-1V7.41L7.7 10.7a1 1 0 0 1-1.4-1.42l5-5a1 1 0 0 1 1.4 0l5 5a1 1 0 0 1-1.4 1.42L13 7.41V20a1 1 0 0 1-1 1Z" />
                                    </svg>
                            @endswitch
                            <span class="font-medium">{{ $card['trend']['label'] }}</span>
                        </div>
                    </div>
                    <div class="self-start md:self-center">
                        @php
                            $accentClasses = $card['accent'];
                        @endphp
                        <span
                            class="inline-flex h-16 w-16 items-center justify-center rounded-2xl {{ $accentClasses }}">
                            @switch($card['icon'])
                                @case('wallet')
                                    <x-icons.wallet class="h-9 w-9" />
                                @break

                                @case('chart')
                                    <x-icons.chart class="h-9 w-9" />
                                @break

                                @case('customers')
                                    <x-icons.customers class="h-9 w-9" />
                                @break

                                @case('support')
                                    <x-icons.support class="h-9 w-9" />
                                @break

                                @default
                                    <x-icons.dashboard class="h-9 w-9" />
                            @endswitch
                        </span>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2">
                <div class="bg-white border border-gray-100 rounded-3xl shadow-sm">
                    <div
                        class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between px-6 py-5 border-b border-gray-100">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Rekapan Closingan Terbaru</h2>
                            <p class="text-sm text-gray-500">Diperbarui {{ $lastUpdatedAt }}</p>
                        </div>
                        <span
                            class="text-xs font-medium text-indigo-500 bg-indigo-50 px-3 py-1 rounded-full">{{ $currentPeriod }}</span>
                    </div>

                    <div class="px-2 pb-6 sm:px-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3">No</th>
                                        <th class="px-4 py-3">Nama</th>
                                        <th class="px-4 py-3">Produk</th>
                                        <th class="px-4 py-3">Jumlah</th>
                                        <th class="px-4 py-3">Tanggal</th>
                                        <th class="px-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                                    @forelse ($closings as $closing)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-4 align-top text-gray-400">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-4 align-top">
                                                <div class="font-semibold text-gray-800">
                                                    {{ optional($closing->user)->name ?? 'Tidak diketahui' }}</div>
                                                <div class="text-xs text-gray-400">{{ $closing->bisnis }}</div>
                                            </td>
                                            <td class="px-4 py-4 align-top">
                                                <div class="font-medium text-gray-700">{{ $closing->produk }}</div>
                                                <div class="text-xs text-gray-400">Paket {{ $closing->paket }}</div>
                                            </td>
                                            <td class="px-4 py-4 align-top font-semibold text-gray-800">
                                                Rp {{ number_format((float) $closing->jumlah, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-4 align-top">
                                                {{ optional($closing->created_at)->locale('id')->isoFormat('D MMMM YYYY') }}
                                            </td>
                                            <td class="px-4 py-4 align-top">
                                                @php
                                                    $statusColors = [
                                                        'Selesai' => 'bg-emerald-100 text-emerald-600',
                                                        'Pending' => 'bg-amber-100 text-amber-600',
                                                        'Gagal' => 'bg-rose-100 text-rose-600',
                                                    ];
                                                    $statusClass =
                                                        $statusColors[$closing->status] ?? 'bg-gray-100 text-gray-500';
                                                @endphp
                                                <span
                                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                                    <span class="h-2 w-2 rounded-full bg-current"></span>
                                                    {{ $closing->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500">
                                                Belum ada data closingan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-white border border-gray-100 rounded-3xl shadow-sm h-full">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-800">Performa CS</h2>
                        <p class="text-sm text-gray-500">Periode {{ $currentPeriod }}</p>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-5">
                            @forelse ($performances as $perf)
                                <li class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 text-indigo-600 font-semibold flex items-center justify-center">
                                            {{ mb_strtoupper(mb_substr($perf->name ?? 'CS', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $perf->name }}</p>
                                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $perf->status === 'Aktif' ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                                    {{ $perf->status ?? 'Aktif' }}
                                                </span>
                                                <span>{{ $perf->total_closing ?? 0 }} closing</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-800">
                                            {{ $perf->total_pendapatan_formatted }}</div>
                                        <div class="text-xs text-gray-400">Rata-rata
                                            {{ number_format($perf->rata_rata_closing, 1, ',', '.') }} / hari</div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center text-sm text-gray-500 py-10">
                                    Belum ada data performa CS untuk periode ini.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
