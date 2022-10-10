<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Message;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageMessagesTest extends TestCase
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
    public function it_gets_message_messages()
    {
        $message = Message::factory()->create();
        $messages = Message::factory()
            ->count(2)
            ->create([
                'message_id' => $message->id,
            ]);

        $response = $this->getJson(
            route('api.messages.messages.index', $message)
        );

        $response->assertOk()->assertSee($messages[0]->content);
    }

    /**
     * @test
     */
    public function it_stores_the_message_messages()
    {
        $message = Message::factory()->create();
        $data = Message::factory()
            ->make([
                'message_id' => $message->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.messages.messages.store', $message),
            $data
        );

        $this->assertDatabaseHas('messages', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $message = Message::latest('id')->first();

        $this->assertEquals($message->id, $message->message_id);
    }
}
