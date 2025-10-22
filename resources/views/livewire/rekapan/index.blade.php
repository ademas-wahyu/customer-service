<div>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
         
        </h2>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-medium">Halaman Rekapan</h3>
                    <p class="mt-2 text-sm text-gray-600">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-8">
    
    <div class="p-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Detail Closingan</h2>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 px-4 py-2 bg-indigo-900 text-white rounded-lg text-sm font-medium hover:bg-indigo-800 transition">
                <i class="bi bi-sliders"></i>
                <span>Filter</span>
            </button>
            <button class="flex items-center gap-2 px-4 py-2 bg-indigo-900 text-white rounded-lg text-sm font-medium hover:bg-indigo-800 transition">
                <i class="bi bi-file-earmark-pdf-fill"></i>
                <span>Export</span>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($closings as $closing)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $closing['klien'] }}</div>
                            <div class="text-sm text-gray-500">({{ $closing['bisnis'] }})</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $closing['produk'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $closing['jumlah'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $closing['waktu'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = '';
                                if ($closing['status'] == 'Selesai') {
                                    $statusClass = 'bg-green-100 text-green-800';
                                } elseif ($closing['status'] == 'Gagal') {
                                    $statusClass = 'bg-red-100 text-red-800';
                                } elseif ($closing['status'] == 'Pending') {
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                }
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $closing['status'] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Tidak ada data.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-200">
        {{ $closings->links() }}
    </div>
</div>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>