<?php

namespace App\Livewire;

use App\Models\Closing;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public $searchPerformance = '';

    public function render()
    {
        // Card Stats
        $closingHarian = Closing::whereDate('created_at', Carbon::today())->count();
        $closingBulanan = Closing::whereMonth('created_at', Carbon::now()->month)->count();
        $rekapitulasi = Closing::sum('omset'); // Asumsi ada kolom 'omset'

        // Tabel Rekapan Terbaru
        $closings = Closing::with('user') // Eager load user
            ->latest()
            ->paginate(5, ['*'], 'closingPage');

        // Tabel Performa CS
        $performances = User::whereHas('roles', function ($query) {
            $query->where('name', 'cs');
        })
            ->withCount('closings') // Menghitung total closingan
            ->when($this->searchPerformance, function ($query) {
                $query->where('name', 'like', '%' . $this->searchPerformance . '%');
            })
            ->paginate(5, ['*'], 'performancePage');


        return view('dashboard.index', [
            'closingHarian' => $closingHarian,
            'closingBulanan' => $closingBulanan,
            'rekapitulasi' => number_format($rekapitulasi, 0, ',', '.'),
            'closings' => $closings,
            'performances' => $performances,
        ])
            ->layout('layouts.app');
    }
}
