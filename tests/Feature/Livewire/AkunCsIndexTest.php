<?php

namespace Tests\Feature\Livewire;

use App\Livewire\AkunCs\Index as AkunCsIndex;
use App\Models\Closing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AkunCsIndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('Super Admin');
        Role::findOrCreate('Admin');
    }

    public function test_guest_is_redirected_from_akun_cs_route(): void
    {
        $this->get(route('akun-cs.index'))
            ->assertRedirect(route('login'));
    }

    public function test_component_generates_augmented_user_data(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 10, 9));

        try {
            Storage::fake('public');

            $viewer = User::factory()->create();
            $viewer->assignRole('Super Admin');

            $userWithPhoto = User::factory()->create([
                'profile_photo_path' => 'avatars/photo.jpg',
            ]);
            $userWithPhoto->assignRole('Admin');

            $userWithoutPhoto = User::factory()->create([
                'profile_photo_path' => null,
            ]);
            $userWithoutPhoto->assignRole('Admin');

            Storage::disk('public')->put('avatars/photo.jpg', 'avatar');

            Closing::create([
                'user_id' => $userWithPhoto->id,
                'klien' => 'PT Maju',
                'bisnis' => 'Bisnis A',
                'produk' => 'Website',
                'jumlah' => 2000000,
                'poin' => 2.0,
                'status' => 'Selesai',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ]);

            Closing::create([
                'user_id' => $userWithPhoto->id,
                'klien' => 'PT Jaya',
                'bisnis' => 'Bisnis B',
                'produk' => 'SEO',
                'jumlah' => 1500000,
                'poin' => 1.5,
                'status' => 'Selesai',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ]);

            Closing::create([
                'user_id' => $userWithPhoto->id,
                'klien' => 'PT Pending',
                'bisnis' => 'Bisnis C',
                'produk' => 'Compro',
                'jumlah' => 1000000,
                'poin' => 0,
                'status' => 'Pending',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ]);

            $this->actingAs($viewer);

            $component = Livewire::test(AkunCsIndex::class)
                ->assertStatus(200);

            $users = $component->instance()->users;

            $this->assertNotEmpty($users);
            $this->assertTrue($component->instance()->targetPoin > 0);

            $this->assertTrue($users->contains(fn ($user) => $user->id === $userWithPhoto->id));
            $this->assertTrue($users->contains(fn ($user) => $user->id === $userWithoutPhoto->id));

            foreach ($users as $user) {
                $this->assertMatchesRegularExpression('/^CS-\d{3}$/', $user->kode_cs);
                $this->assertIsFloat($user->poin);
                $this->assertIsInt($user->closing);
                $this->assertIsInt($user->waitingList);
                $this->assertGreaterThanOrEqual(0, $user->poinPercentage);
                $this->assertLessThanOrEqual(100, $user->poinPercentage);
                $this->assertIsFloat($user->poinColorHue);
                $this->assertIsFloat($user->poinDifference);
                $this->assertIsArray($user->chart);

                $chart = $user->chart;
                $this->assertSame(['values', 'labels', 'path', 'area', 'points', 'width', 'height', 'max', 'min', 'average'], array_keys($chart));
                $this->assertCount(7, $chart['values']);
                $this->assertCount(7, $chart['labels']);
                $this->assertCount(7, $chart['points']);

                foreach ($chart['points'] as $point) {
                    $this->assertArrayHasKey('x', $point);
                    $this->assertArrayHasKey('y', $point);
                    $this->assertArrayHasKey('value', $point);
                }
            }

            $userWithPhotoData = $users->firstWhere('id', $userWithPhoto->id);
            $this->assertSame('/storage/avatars/photo.jpg', $userWithPhotoData->profile_photo_url);
            $this->assertSame(2, $userWithPhotoData->closing);
            $this->assertSame(1, $userWithPhotoData->waitingList);
            $this->assertSame(3.5, $userWithPhotoData->poin);
            $this->assertSame(array_sum($userWithPhotoData->chart['values']), $userWithPhotoData->closing);

            $userWithoutPhotoData = $users->firstWhere('id', $userWithoutPhoto->id);
            $this->assertNull($userWithoutPhotoData->profile_photo_url);
        } finally {
            Carbon::setTestNow();
        }
    }
}
