<?php

namespace App\Livewire\AkunCs;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\View\View;

class Index extends Component
{
    // PASTIKAN BARIS INI ADA DAN BERNILAI BUKAN 0
    public float $targetPoin = 14.0;
    public Collection $users;

    protected array $chartLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

    public function mount(): void
    {
        $this->users = User::role(['Super Admin', 'Admin'])->get();

        $this->users = $this->users->map(function ($user) {
            // Data bohongan untuk statistik
            $poin = rand(5, 20) + (rand(0, 10) / 10);
            $closing = rand(10, 50);
            $waitingList = rand(4, 20);

            $chartValues = $this->generateChartValues($closing);
            $chartMeta = $this->buildChartMeta($chartValues);

            $user->kode_cs = 'CS-' . str_pad($user->id, 3, '0', STR_PAD_LEFT);
            $user->closing = $closing;
            $user->poin = $poin;
            $user->waitingList = $waitingList;

            if ($user->profile_photo_path) {
                $user->profile_photo_url = Storage::url($user->profile_photo_path);
            } else {
                $user->profile_photo_url = null;
            }

            $percentage = ($this->targetPoin > 0)
                ? max(0, min(100, ($user->poin / $this->targetPoin) * 100))
                : 0;

            $hue = $percentage * 1.2;

            $difference = $user->poin - $this->targetPoin;

            $user->poinPercentage = $percentage;
            $user->poinColorHue = $hue;
            $user->poinDifference = $difference;
            $user->chart = $chartMeta;

            return $user;
        });
    }

    public function render(): View
    {
        return view('livewire.akun-cs.index')
            ->layout('layouts.app');
    }

    /**
     * Membuat nilai acak untuk grafik performa closing.
     *
     * @param  int  $baseline
     * @return array<int, int>
     */
    protected function generateChartValues(int $baseline): array
    {
        $baseline = max(8, $baseline);

        return collect($this->chartLabels)
            ->map(function () use ($baseline) {
                return max(0, $baseline + rand(-8, 12));
            })
            ->toArray();
    }

    /**
     * Menghitung data pendukung untuk menggambar grafik di tampilan.
     *
     * @param  array<int, int|float>  $values
     * @return array<string, mixed>
     */
    protected function buildChartMeta(array $values): array
    {
        $width = 360;
        $height = 160;
        $paddingX = 24;
        $paddingY = 18;

        $max = max($values);
        $min = min($values);

        if ($max === $min) {
            $max += 1;
            $min = max(0, $min - 1);
        }

        $range = max(1, $max - $min);
        $pointCount = count($values);
        $stepX = $pointCount > 1 ? ($width - ($paddingX * 2)) / ($pointCount - 1) : 0;

        $points = [];

        foreach ($values as $index => $value) {
            $x = $paddingX + ($stepX * $index);
            $normalized = ($value - $min) / $range;
            $y = ($height - $paddingY) - ($normalized * ($height - ($paddingY * 2)));

            $points[] = [
                'x' => round($x, 2),
                'y' => round($y, 2),
                'value' => $value,
            ];
        }

        $firstPoint = $points[0] ?? ['x' => $paddingX, 'y' => $height - $paddingY];
        $lastPoint = $points[count($points) - 1] ?? $firstPoint;

        $segments = array_map(
            fn (array $point) => $point['x'] . ',' . $point['y'],
            $points
        );

        $path = 'M ' . implode(' L ', $segments);
        $area = $path
            . ' L ' . $lastPoint['x'] . ',' . ($height - $paddingY)
            . ' L ' . $firstPoint['x'] . ',' . ($height - $paddingY)
            . ' Z';

        return [
            'values' => $values,
            'labels' => $this->chartLabels,
            'path' => $path,
            'area' => $area,
            'points' => $points,
            'width' => $width,
            'height' => $height,
            'max' => $max,
            'min' => $min,
            'average' => round(array_sum($values) / max(1, count($values)), 1),
        ];
    }
}
