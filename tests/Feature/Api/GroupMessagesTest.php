<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Group;
use App\Models\Message;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupMessagesTest extends TestCase
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
    public function it_gets_group_messages()
    {
        $group = Group::factory()->create();
        $messages = Message::factory()
            ->count(2)
            ->create([
                'group_id' => $group->id,
            ]);

        $response = $this->getJson(route('api.groups.messages.index', $group));

        $response->assertOk()->assertSee($messages[0]->content);
    }

    /**
     * @test
     */
    public function it_stores_the_group_messages()
    {
        $group = Group::factory()->create();
        $data = Message::factory()
            ->make([
                'group_id' => $group->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.groups.messages.store', $group),
            $data
        );

        $this->assertDatabaseHas('messages', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $message = Message::latest('id')->first();

        $this->assertEquals($group->id, $message->group_id);
    }
}
