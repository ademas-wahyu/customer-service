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
        Carbon::setLocale('id');

        $now = Carbon::now();
        $currentStart = $now->copy()->startOfMonth();
        $currentEnd = $now->copy()->endOfDay();
        $previousStart = $currentStart->copy()->subMonth();
        $previousEnd = $previousStart->copy()->endOfMonth();

        $closingBaseQuery = Closing::query()->where('status', 'Selesai');

        $closingCurrentMonth = (clone $closingBaseQuery)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();
        $closingPreviousMonth = (clone $closingBaseQuery)
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->count();

        $pendapatanCurrentMonth = (clone $closingBaseQuery)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->sum('jumlah');
        $pendapatanPreviousMonth = (clone $closingBaseQuery)
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->sum('jumlah');

        $customerCurrentMonth = (clone $closingBaseQuery)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->distinct('klien')
            ->count('klien');
        $customerPreviousMonth = (clone $closingBaseQuery)
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->distinct('klien')
            ->count('klien');

        $csAktifCurrent = Closing::query()
            ->where('status', 'Selesai')
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->distinct('user_id')
            ->count('user_id');
        $csAktifPrevious = Closing::query()
            ->where('status', 'Selesai')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->distinct('user_id')
            ->count('user_id');

        $statCards = [
            [
                'title' => 'Total Closingan',
                'value' => number_format($closingCurrentMonth, 0, ',', '.'),
                'trend' => $this->buildTrend($closingCurrentMonth, $closingPreviousMonth),
                'subtitle' => 'Periode ' . $currentStart->isoFormat('MMMM YYYY'),
                'icon' => 'chart',
                'accent' => 'bg-indigo-500/10 text-indigo-600',
            ],
            [
                'title' => 'Total Pendapatan',
                'value' => $this->formatCurrency($pendapatanCurrentMonth),
                'trend' => $this->buildTrend($pendapatanCurrentMonth, $pendapatanPreviousMonth),
                'subtitle' => 'Transaksi selesai bulan ini',
                'icon' => 'wallet',
                'accent' => 'bg-emerald-500/10 text-emerald-600',
            ],
            [
                'title' => 'Customer Baru',
                'value' => number_format($customerCurrentMonth, 0, ',', '.'),
                'trend' => $this->buildTrend($customerCurrentMonth, $customerPreviousMonth),
                'subtitle' => 'Klien unik bulan ini',
                'icon' => 'users',
                'accent' => 'bg-amber-500/10 text-amber-600',
            ],
            [
                'title' => 'CS Aktif',
                'value' => number_format($csAktifCurrent, 0, ',', '.'),
                'trend' => $this->buildTrend($csAktifCurrent, $csAktifPrevious),
                'subtitle' => 'Berpartisipasi bulan ini',
                'icon' => 'support',
                'accent' => 'bg-rose-500/10 text-rose-600',
            ],
        ];

        $closings = Closing::with('user')
            ->latest()
            ->take(6)
            ->get();

        $periodLength = max(1, $currentStart->copy()->diffInDays($currentEnd) + 1);

        $performances = User::query()
            ->whereHas('closings', function ($query) use ($currentStart, $currentEnd) {
                $query->where('status', 'Selesai')
                    ->whereBetween('created_at', [$currentStart, $currentEnd]);
            })
            ->withCount([
                'closings as total_closing' => function ($query) use ($currentStart, $currentEnd) {
                    $query->where('status', 'Selesai')
                        ->whereBetween('created_at', [$currentStart, $currentEnd]);
                },
            ])
            ->withSum([
                'closings as total_pendapatan' => function ($query) use ($currentStart, $currentEnd) {
                    $query->where('status', 'Selesai')
                        ->whereBetween('created_at', [$currentStart, $currentEnd]);
                },
            ], 'jumlah')
            ->orderByDesc('total_pendapatan')
            ->take(6)
            ->get()
            ->map(function (User $user) use ($periodLength) {
                $totalClosing = (int) ($user->total_closing ?? 0);
                $totalPendapatan = (float) ($user->total_pendapatan ?? 0);

                $user->rata_rata_closing = round($totalClosing / $periodLength, 1);
                $user->total_pendapatan_formatted = $this->formatCurrency($totalPendapatan);

                return $user;
            });

        return view('dashboard.index', [
            'statCards' => $statCards,
            'closings' => $closings,
            'performances' => $performances,
            'currentPeriod' => $currentStart->isoFormat('MMMM YYYY'),
            'lastUpdatedAt' => $now->isoFormat('D MMMM YYYY, HH:mm'),
        ])->layout('layouts.app');
    }

    /**
     * @return array{direction: string, percentage: float, label: string}
     */
    private function buildTrend(float $current, float $previous): array
    {
        if ($current === $previous) {
            return [
                'direction' => 'flat',
                'percentage' => 0.0,
                'label' => 'Tetap stabil dibanding bulan lalu',
            ];
        }

        if ($previous <= 0.0) {
            return [
                'direction' => $current > 0 ? 'up' : 'flat',
                'percentage' => 100.0,
                'label' => $current > 0
                    ? 'Naik 100% dari bulan lalu'
                    : 'Belum ada aktivitas bulan ini',
            ];
        }

        $difference = $current - $previous;
        $percentage = ($difference / $previous) * 100;
        $direction = $difference >= 0 ? 'up' : 'down';

        return [
            'direction' => $direction,
            'percentage' => round(abs($percentage), 1),
            'label' => sprintf(
                '%s %s%% dari bulan lalu',
                $direction === 'up' ? 'Naik' : 'Turun',
                number_format(abs($percentage), 1, ',', '.')
            ),
        ];
    }

    private function formatCurrency(float $amount): string
    {
        if ($amount >= 1_000_000_000) {
            return 'Rp ' . number_format($amount / 1_000_000_000, 2, ',', '.') . ' M';
        }

        if ($amount >= 1_000_000) {
            return 'Rp ' . number_format($amount / 1_000_000, 2, ',', '.') . ' JT';
        }

        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
