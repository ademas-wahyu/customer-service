<?php

namespace App\Livewire\Rekapan;

use App\Models\Closing;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Create extends Component
{
    public string $klien = '';

    public string $bisnis = '';

    public string $paket = '';

    public string $produk = '';

    public string $jumlah = '';

    public string $status = '';

    /**
     * Daftar status yang tersedia untuk pilihan form.
     *
     * @var array<int, string>
     */
    public array $availableStatuses = [
        'Selesai',
        'Pending',
        'Gagal',
    ];

    protected function rules(): array
    {
        return [
            'klien' => ['required', 'string', 'max:255'],
            'bisnis' => ['required', 'string', 'max:255'],
            'paket' => ['required', 'string', 'max:255'],
            'produk' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        Closing::create([
            'user_id' => Auth::id(),
            'klien' => $validated['klien'],
            'bisnis' => $validated['bisnis'],
            'paket' => $validated['paket'],
            'produk' => $validated['produk'],
            'jumlah' => $validated['jumlah'],
            'status' => $validated['status'],
        ]);

        session()->flash('status', 'Data closing berhasil ditambahkan.');

        $this->redirectRoute('rekapan.index');
    }

    public function render()
    {
        return view('livewire.rekapan.create');
    }
}
