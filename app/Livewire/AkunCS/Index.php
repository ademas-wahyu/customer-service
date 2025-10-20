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

    public bool $showDetailModal = false;
    public ?User $selectedUser = null;

    public function mount(): void
    {
        $this->users = User::role(['Super Admin', 'Admin'])->get();

        $this->users = $this->users->map(function ($user) {
            // Data bohongan untuk statistik
            $poin = rand(5, 20) + (rand(0, 10) / 10);
            $closing = rand(10, 50);

            $user->kode_cs = 'CS-' . str_pad($user->id, 3, '0', STR_PAD_LEFT);
            $user->closing = $closing;
            $user->poin = $poin;

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

            return $user;
        });
    }

    public function showDetail(int $userId): void
    {
        $this->selectedUser = $this->users->firstWhere('id', $userId);
        $this->showDetailModal = true;
    }

    public function updatingShowDetailModal($value): void
    {
        if ($value === false) {
            $this->reset('selectedUser');
        }
    }

    public function render(): View
    {
        return view('livewire.akun-cs.index')
            ->layout('layouts.app');
    }
}
