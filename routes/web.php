<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Dashboard;
use App\Livewire\AkunCs\Index as AkunCsIndex;
use App\Livewire\Pengaturan\Index as PengaturanIndex;
use App\Livewire\Rekapan\Create as RekapanCreate;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->middleware(['auth'])->name('dashboard');

Route::get('/akun-cs', AkunCsIndex::class)->middleware(['auth'])->name('akun-cs.index');

Route::get('/pengaturan', PengaturanIndex::class)->middleware(['auth'])->name('pengaturan.index');

Route::get('/rekapan', App\Livewire\Rekapan\Index::class)->middleware(['auth'])->name('rekapan.index');
Route::get('/rekapan/create', RekapanCreate::class)->middleware('auth')->name('rekapan.create');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
