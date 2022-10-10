<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Message;

use App\Models\Group;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
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
    public function it_gets_messages_list()
    {
        $messages = Message::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.messages.index'));

        $response->assertOk()->assertSee($messages[0]->content);
    }

    /**
     * @test
     */
    public function it_stores_the_message()
    {
        $data = Message::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.messages.store'), $data);

        $this->assertDatabaseHas('messages', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_message()
    {
        $message = Message::factory()->create();

        $user = User::factory()->create();
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $message = Message::factory()->create();

        $data = [
            'content' => $this->faker->text,
            'sender_id' => $user->id,
            'receiver_id' => $user->id,
            'group_id' => $group->id,
            'message_id' => $message->id,
        ];

        $response = $this->putJson(
            route('api.messages.update', $message),
            $data
        );

        $data['id'] = $message->id;

        $this->assertDatabaseHas('messages', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_message()
    {
        $message = Message::factory()->create();

        $response = $this->deleteJson(route('api.messages.destroy', $message));

        $this->assertModelMissing($message);

        $response->assertNoContent();
    }
}
