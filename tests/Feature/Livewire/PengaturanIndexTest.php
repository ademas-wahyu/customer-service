<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Pengaturan\Index as PengaturanIndex;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PengaturanIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_pengaturan_route(): void
    {
        $this->get(route('pengaturan.index'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_render_pengaturan_component(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get(route('pengaturan.index'))
            ->assertOk()
            ->assertSee('Manajemen Data');

        Livewire::test(PengaturanIndex::class)
            ->assertStatus(200)
            ->assertViewIs('livewire.pengaturan.index');
    }
}
