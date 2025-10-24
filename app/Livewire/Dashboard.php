<?php

namespace App\Livewire;

use App\Models\Closing;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;


class Dashboard extends Component
{

    public function render()
    {
        // Card Stats
        $closingHarian = Closing::whereDate('created_at', Carbon::today())->count();
        $closingBulanan = Closing::whereMonth('created_at', Carbon::now()->month)->count();
        $rekapitulasi = Closing::sum('omset'); // Asumsi ada kolom 'omset'
        $totalCs = User::whereHas('roles', function ($query) {
            $query->where('name', 'cs');
        })->count();

        // Tabel Rekapan Terbaru
        $closings = Closing::with('user') // Eager load user
            ->latest()
            ->take(5)
            ->get();

        // Tabel Performa CS
        $performances = User::whereHas('roles', function ($query) {
            $query->where('name', 'cs');
        })
            ->withCount('closings') // Menghitung total closingan
            ->get()
            ->map(function ($user) {
                $daysSinceRegistration = Carbon::parse($user->created_at)->diffInDays(Carbon::now()) + 1;
                $user->rata_rata_closing = round($user->closings_count / $daysSinceRegistration, 2);
                return $user;
            });


        return view('dashboard.index', [
            'closingHarian' => $closingHarian,
            'closingBulanan' => $closingBulanan,
            'rekapitulasi' => number_format($rekapitulasi, 0, ',', '.'),
            'totalCs' => $totalCs,
            'closings' => $closings,
            'performances' => $performances,
        ])
            ->layout('layouts.app');
    }
}
