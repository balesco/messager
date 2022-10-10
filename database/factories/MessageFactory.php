<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => $this->faker->text,
            'sender_id' => \App\Models\User::factory(),
            'receiver_id' => \App\Models\User::factory(),
            'group_id' => \App\Models\Group::factory(),
            'message_id' => function () {
                return \App\Models\Message::factory()->create([
                    'message_id' => null,
                ])->id;
            },
        ];
    }
}
