<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Message;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserMessagesTest extends TestCase
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
    public function it_gets_user_messages()
    {
        $user = User::factory()->create();
        $messages = Message::factory()
            ->count(2)
            ->create([
                'receiver_id' => $user->id,
            ]);

        $response = $this->getJson(route('api.users.messages.index', $user));

        $response->assertOk()->assertSee($messages[0]->content);
    }

    /**
     * @test
     */
    public function it_stores_the_user_messages()
    {
        $user = User::factory()->create();
        $data = Message::factory()
            ->make([
                'receiver_id' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.messages.store', $user),
            $data
        );

        $this->assertDatabaseHas('messages', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $message = Message::latest('id')->first();

        $this->assertEquals($user->id, $message->receiver_id);
    }
}
