<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Http\Resources\MessageCollection;

class MessageMessagesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Message $message
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Message $message)
    {
        $this->authorize('view', $message);

        $search = $request->get('search', '');

        $messages = $message
            ->messages()
            ->search($search)
            ->latest()
            ->paginate();

        return new MessageCollection($messages);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Message $message
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Message $message)
    {
        $this->authorize('create', Message::class);

        $validated = $request->validate([
            'image' => ['image', 'max:1024', 'nullable'],
            'content' => ['required', 'max:255', 'string'],
            'sender_id' => ['required', 'exists:users,id'],
            'receiver_id' => ['required', 'exists:users,id'],
            'group_id' => ['nullable', 'exists:groups,id'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $message = $message->messages()->create($validated);

        return new MessageResource($message);
    }
}
