<?php

namespace App\Http\Controllers\Api;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Http\Resources\MessageCollection;

class GroupMessagesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Group $group)
    {
        // $this->authorize('view', $group);

        $search = $request->get('search', '');

        $messages = $group
            ->messages()
            ->search($search)
            ->latest()
            ->paginate();

        return new MessageCollection($messages);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $group)
    {
        // $this->authorize('create', Message::class);

        $validated = $request->validate([
            'image' => ['image', 'max:1024', 'nullable'],
            'content' => ['required', 'max:255', 'string'],
            'sender_id' => ['required', 'exists:users,id'],
            'receiver_id' => ['required', 'exists:users,id'],
            'message_id' => ['nullable', 'exists:messages,id'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $message = $group->messages()->create($validated);

        return new MessageResource($message);
    }
}
