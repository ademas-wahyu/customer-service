<?php

namespace App\Livewire\AkunCs;

use App\Models\Closing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    public float $targetPoin = 14.0;
    public Collection $users;

    /**
     * @var array<int, string>
     */
    protected array $chartLabels = [];

    protected Collection $chartPeriod;

    public bool $showCreateModal = false;
    public array $form = [];
    public array $roles = [];

    /**
     * Menyiapkan data awal untuk komponen.
     */
    public function mount(): void
    {
        $this->form = $this->initForm();
        $this->roles = Role::whereIn("name", [
            "Head Admin",
            "Super Admin",
            "Admin",
        ])
            ->pluck("name", "name")
            ->all();

        $current = now();
        $periodStart = $current->copy()->subDays(6)->startOfDay();
        $periodEnd = $current->copy()->endOfDay();

        $this->chartPeriod = collect(range(6, 0))->map(
            fn(int $day) => $current->copy()->subDays($day)->startOfDay(),
        );

        $this->chartLabels = $this->chartPeriod
            ->map(fn(Carbon $date) => $this->formatDayLabel($date))
            ->toArray();

        $this->users = User::role(["Super Admin", "Admin"])
            ->withCount([
                "closings as closing_total" => fn($query) => $query
                    ->where("status", "Selesai")
                    ->whereBetween("created_at", [$periodStart, $periodEnd]),
                "closings as waiting_total" => fn($query) => $query->where(
                    "status",
                    "Pending",
                ),
            ])
            ->withSum(
                [
                    "closings as poin_total" => fn($query) => $query
                        ->where("status", "Selesai")
                        ->whereBetween("created_at", [
                            $periodStart,
                            $periodEnd,
                        ]),
                ],
                "poin",
            )
            ->get();

        $historyByUser = Closing::query()
            ->selectRaw(
                "user_id, DATE(created_at) as closing_date, " .
                    "COUNT(*) as closing_total, COALESCE(SUM(poin), 0) as poin_total",
            )
            ->where("status", "Selesai")
            ->whereBetween("created_at", [$periodStart, $periodEnd])
            ->whereIn("user_id", $this->users->pluck("id"))
            ->groupBy("user_id", "closing_date")
            ->orderBy("closing_date")
            ->get()
            ->groupBy("user_id");

        $this->users = $this->users->map(function (User $user) use (
            $historyByUser,
        ) {
            $history = $historyByUser->get($user->id, collect());

            $chartValues = $this->generateChartValues(
                $this->chartPeriod,
                $history,
            );
            $chartMeta = $this->buildChartMeta($chartValues);

            $closing = array_sum($chartValues);
            $poin = round((float) ($user->poin_total ?? 0), 1);
            $waitingList = (int) ($user->waiting_total ?? 0);

            $user->kode_cs = "CS-" . str_pad($user->id, 3, "0", STR_PAD_LEFT);
            $user->closing = $closing;
            $user->poin = $poin;
            $user->waitingList = $waitingList;

            $percentage =
                $this->targetPoin > 0
                    ? max(0, min(100, ($user->poin / $this->targetPoin) * 100))
                    : 0;

            $user->poinPercentage = $percentage;
            $user->poinColorHue = $percentage * 1.2;
            $user->poinDifference = $user->poin - $this->targetPoin;
            $user->chart = $chartMeta;

            return $user;
        });
    }

    public function render(): View
    {
        return view("livewire.akun-cs.index")->layout("layouts.app");
    }

    protected function initForm(): array
    {
        return [
            "name" => "",
            "email" => "",
            "role" => "Admin", // Role default
            "password" => "",
            "password_confirmation" => "",
        ];
    }

    public function create(): void
    {
        $this->form = $this->initForm();
        $this->resetErrorBag();
        $this->dispatch('open-create-modal');
    }

    public function saveUser(): void
    {
        $this->validate([
            "form.name" => ["required", "string", "max:255"],
            "form.email" => [
                "required",
                "string",
                "email",
                "max:255",
                "unique:" . User::class . ",email",
            ],
            "form.role" => [
                "required",
                "string",
                "exists:" . Role::class . ",name",
            ],
            "form.password" => [
                "required",
                "string",
                Rules\Password::defaults(),
                "confirmed",
            ],
        ]);

        $user = User::create([
            "name" => $this->form["name"],
            "email" => $this->form["email"],
            "password" => Hash::make($this->form["password"]),
            "is_active" => true,
        ]);

        $user->assignRole($this->form["role"]);

        $this->closeCreateModal();

        $this->redirect(self::class, navigate: true);
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->form = $this->initForm();
        $this->resetErrorBag();
        $this->dispatch('close-create-modal');
    }

    protected function generateChartValues(
        Collection $period,
        Collection $history,
    ): array {
        $historyTotals = $history->mapWithKeys(function ($row) {
            $dateString = $row->closing_date ?? null;

            if (!$dateString) {
                return [];
            }

            $date = Carbon::parse($dateString);

            return [$date->toDateString() => (int) ($row->closing_total ?? 0)];
        });

        return $period
            ->map(
                fn(Carbon $date) => $historyTotals->get(
                    $date->toDateString(),
                    0,
                ),
            )
            ->toArray();
    }

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
        $stepX =
            $pointCount > 1 ? ($width - $paddingX * 2) / ($pointCount - 1) : 0;

        $points = [];

        foreach ($values as $index => $value) {
            $x = $paddingX + $stepX * $index;
            $normalized = ($value - $min) / $range;
            $y = $height - $paddingY - $normalized * ($height - $paddingY * 2);

            $points[] = [
                "x" => round($x, 2),
                "y" => round($y, 2),
                "value" => $value,
            ];
        }

        $firstPoint = $points[0] ?? [
            "x" => $paddingX,
            "y" => $height - $paddingY,
        ];
        $lastPoint = $points[count($points) - 1] ?? $firstPoint;

        $segments = array_map(
            fn(array $point) => $point["x"] . "," . $point["y"],
            $points,
        );

        $path = "M " . implode(" L ", $segments);
        $area =
            $path .
            " L " .
            $lastPoint["x"] .
            "," .
            ($height - $paddingY) .
            " L " .
            $firstPoint["x"] .
            "," .
            ($height - $paddingY) .
            " Z";

        return [
            "values" => $values,
            "labels" => $this->chartLabels,
            "path" => $path,
            "area" => $area,
            "points" => $points,
            "width" => $width,
            "height" => $height,
            "max" => $max,
            "min" => $min,
            "average" => round(array_sum($values) / max(1, count($values)), 1),
        ];
    }

    protected function formatDayLabel(Carbon $date): string
    {
        $map = [
            "Mon" => "Sen",
            "Tue" => "Sel",
            "Wed" => "Rab",
            "Thu" => "Kam",
            "Fri" => "Jum",
            "Sat" => "Sab",
            "Sun" => "Min",
        ];

        $abbr = $date->format("D");

        return $map[$abbr] ?? $abbr;
    }
}
