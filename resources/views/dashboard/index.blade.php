<div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div>
                        {{-- <h1 class="text-3xl font-bold text-indigo-900 mb-6">Dashboard</h1> --}}

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Closing Harian</span>
                                    <p class="text-3xl font-bold text-gray-800">{{ $closingHarian }}</p>
                                </div>
                                <div class="w-14 h-14 rounded-full flex items-center justify-center bg-yellow-100">
                                    <i class="bi bi-cart3 text-2xl text-yellow-600"></i>
                                </div>
                            </div>
                            {{-- Card 2: Closing Bulanan --}}
                            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Closing Bulanan</span>
                                    <p class="text-3xl font-bold text-gray-800">{{ $closingBulanan }}</p>
                                </div>
                                <div class="w-14 h-14 rounded-full flex items-center justify-center bg-indigo-100">
                                    <i class="bi bi-calendar-event-fill text-2xl text-indigo-600"></i>
                                </div>
                            </div>
                            {{-- Card 3: Rekapitulasi --}}
                            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Rekapitulasi</span>
                                    <p class="text-3xl font-bold text-green-600">Rp. {{ $rekapitulasi }}</p>
                                </div>
                                <div class="w-14 h-14 rounded-full flex items-center justify-center bg-green-100">
                                    <i class="bi bi-currency-dollar text-2xl text-green-600"></i>
                                </div>
                            </div>
                            {{-- Card 4: Total CS --}}
                            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Total CS</span>
                                    <p class="text-3xl font-bold text-gray-800">{{ $totalCs }}</p>
                                </div>
                                <div class="w-14 h-14 rounded-full flex items-center justify-center bg-blue-100">
                                    <i class="bi bi-people-fill text-2xl text-blue-600"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-6">
                            <div class="w-3/4">
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
                                    {{-- Tambah margin-bottom --}}
                                    <div class="p-6 flex justify-between items-center">
                                                                                <h2 class="text-xl font-semibold text-gray-800">Rekapan Closingan Terbaru</h2>
                                                                            </div>
                                                                            {{-- Tabel Closingan --}}
                                                                            <div class="overflow-x-auto">
                                                                                <table class="w-full min-w-full divide-y divide-gray-200">
                                                                                    <thead class="bg-gray-50">
                                                                                        <tr>
                                                                                            <th scope="col"
                                                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                                                NamaCs</th>
                                                                                            <th scope="col"
                                                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                                                Produk</th>
                                                                                            <th scope="col"
                                                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                                                Paket</th>
                                                                                            <th scope="col"
                                                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                                                Tanggal</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                                                        @forelse ($closings as $closing)
                                                                                            <tr>
                                                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                                                    <div class="text-sm font-medium text-gray-900">
                                                                                                        {{ $closing->user->name }}</div>
                                                                                                    <div class="text-sm text-gray-500">
                                                                                                        ({{ $closing->user->bisnis }})
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td
                                                                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                                                                    {{ $closing->produk }}</td>
                                                                                                <td
                                                                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                                                                    {{ $closing->paket }}</td>
                                                                                                <td
                                                                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                                                                    {{ $closing->created_at->format('d/m/Y') }}</td>
                                                                                            </tr>
                                                                                        @empty
                                                                                            <tr>
                                                                                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">Tidak
                                                                                                    ada data closingan.</td>
                                                                                            </tr>
                                                                                        @endforelse
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                </div>
                            </div>
                            <div class="w-1/4">
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                    {{-- Header Tabel Performa CS (Judul & Search) --}}
                                    <div
                                        class="p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                                        <h2
                                            class="text-xl font-semibold text-gray-800 w-full md:w-auto mb-4 md:mb-0 text-center md:text-left">
                                            Performa CS</h2>

                                    </div>

                                    {{-- Tabel Performa CS Wrapper --}}
                                    <div class="overflow-x-auto">
                                        <table class="w-full min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    {{-- Header Kolom Diubah Sesuai Gambar --}}
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Nama</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Closingan</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Rata-rata</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Status</th>
                                                    {{-- Kolom Aksi tidak ada di tabel Performa CS pada gambar --}}
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                {{-- Loop data $performances --}}
                                                @forelse ($performances as $perf)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $perf->name }}
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            {{ $perf->closings_count }}</td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            {{ $perf->rata_rata_closing }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            @php
                                                                // Logika warna status pill
                                                                $statusClass = $perf->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                                            @endphp
                                                            <span
                                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                                {{ $perf->status }}
                                                            </span>
                                                        </td>
                                                        {{-- Kolom Aksi tidak ada --}}
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        {{-- colspan diubah jadi 4 karena kolom Aksi hilang --}}
                                                        <td colspan="4"
                                                            class="px-6 py-12 text-center text-sm text-gray-500">
                                                                Tidak ada data Performa CS.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
