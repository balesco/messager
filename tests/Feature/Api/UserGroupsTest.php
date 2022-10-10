<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Group;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserGroupsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_user_groups()
    {
        $user = User::factory()->create();
        $groups = Group::factory()
            ->count(2)
            ->create([
                'admin_id' => $user->id,
            ]);

        $response = $this->getJson(route('api.users.groups.index', $user));

        $response->assertOk()->assertSee($groups[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_user_groups()
    {
        $user = User::factory()->create();
        $data = Group::factory()
            ->make([
                'admin_id' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.groups.store', $user),
            $data
        );

        $this->assertDatabaseHas('groups', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $group = Group::latest('id')->first();

        $this->assertEquals($user->id, $group->admin_id);
    }
}
