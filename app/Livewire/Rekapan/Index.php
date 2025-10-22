<?php

namespace App\Livewire\Rekapan;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination; // 1. Tambahkan use WithPagination
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

#[Layout('layouts.app')]
class Index extends Component
{
    // 2. Gunakan trait WithPagination
    use WithPagination;

    // --- Data untuk Kartu ---
    public $closingHarian = 5;
    public $closingBulanan = 5;
    public $rekapitulasi = "15,4 jt";

    // --- Data untuk Tabel ---
    public $allData = [];

    /**
     * mount() dijalankan saat komponen dimuat.
     * Kita isi datanya di sini.
     */
    public function mount()
    {
        $this->allData = [
            // Ini adalah 7 data yang terlihat di gambar
            ['klien' => 'PT Alabama', 'bisnis' => 'Bisnis', 'produk' => 'Website', 'jumlah' => 'Rp. 1.500.000', 'waktu' => '17 Maret 2025', 'status' => 'Selesai'],
            ['klien' => 'PT Surya', 'bisnis' => 'Bisnis', 'produk' => 'Compro', 'jumlah' => 'Rp. 1.500.000', 'waktu' => '17 Maret 2025', 'status' => 'Gagal'],
            ['klien' => 'PT Lorem', 'bisnis' => 'Bisnis', 'produk' => 'SEO', 'jumlah' => 'Rp. 1.500.000', 'waktu' => '17 Maret 2025', 'status' => 'Selesai'],
            ['klien' => 'PT Ipsum', 'bisnis' => 'Bisnis', 'produk' => 'Compro', 'jumlah' => 'Rp. 1.500.000', 'waktu' => '17 Maret 2025', 'status' => 'Pending'],
            ['klien' => 'PT Dolor', 'bisnis' => 'Bisnis', 'produk' => 'Custom', 'jumlah' => 'Rp. 1.500.000', 'waktu' => '17 Maret 2025', 'status' => 'Selesai'],
            ['klien' => 'PT Sit Amet', 'bisnis' => 'Bisnis', 'produk' => 'Website', 'jumlah' => 'Rp. 1.500.000', 'waktu' => '17 Maret 2025', 'status' => 'Selesai'],
            ['klien' => 'PT Alabama', 'bisnis' => 'Bisnis', 'produk' => 'Compro', 'jumlah' => 'Rp. 1.500.000', 'waktu' => '17 Maret 2025', 'status' => 'Selesai'],

            // 3. Tambahkan data ekstra agar paginasi 2 dan 3 muncul (seperti di gambar)
            ['klien' => 'PT Data 8', 'bisnis' => 'Bisnis', 'produk' => 'SEO', 'jumlah' => 'Rp. 1.000.000', 'waktu' => '18 Maret 2025', 'status' => 'Selesai'],
            ['klien' => 'PT Data 9', 'bisnis' => 'Bisnis', 'produk' => 'Website', 'jumlah' => 'Rp. 2.500.000', 'waktu' => '18 Maret 2025', 'status' => 'Pending'],
            ['klien' => 'PT Data 10', 'bisnis' => 'Bisnis', 'produk' => 'Custom', 'jumlah' => 'Rp. 500.000', 'waktu' => '18 Maret 2025', 'status' => 'Gagal'],
             ['klien' => 'PT Data 11', 'bisnis' => 'Bisnis', 'produk' => 'Compro', 'jumlah' => 'Rp. 1.500.000', 'waktu' => '18 Maret 2025', 'status' => 'Selesai'],
            ['klien' => 'PT Data 12', 'bisnis' => 'Bisnis', 'produk' => 'SEO', 'jumlah' => 'Rp. 1.500.000', 'waktu' => '18 Maret 2025', 'status' => 'Selesai'],
            ['klien' => 'PT Data 13', 'bisnis' => 'Bisnis', 'produk' => 'Custom', 'jumlah' => 'Rp. 1.500.000', 'waktu' => '18 Maret 2025', 'status' => 'Gagal'],
            ['klien' => 'PT Data 14', 'bisnis' => 'Bisnis', 'produk' => 'Website', 'jumlah' => 'Rp. 1.500.000', 'waktu' => '18 Maret 2025', 'status' => 'Pending'],
        ];
    }

    /**
     * Fungsi helper untuk paginasi manual dari sebuah array
     */
    public function paginate(Collection $items, $perPage = 7, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    /**
     * Fungsi render() akan menampilkan view
     */
    public function render()
    {
        // 4. Paginate data, 7 item per halaman
        $closings = $this->paginate(collect($this->allData), 7);

        // 5. Kirim data $closings ke view
        //    Data kartu ($closingHarian, dll) otomatis terkirim karena public
        return view('livewire.rekapan.index', [
            'closings' => $closings
        ]);
    }
}