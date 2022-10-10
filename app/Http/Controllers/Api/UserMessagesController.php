<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Http\Resources\MessageCollection;

class UserMessagesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $messages = $user
            ->messages2()
            ->search($search)
            ->latest()
            ->paginate();

        return new MessageCollection($messages);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->authorize('create', Message::class);

        $validated = $request->validate([
            'image' => ['image', 'max:1024', 'nullable'],
            'content' => ['required', 'max:255', 'string'],
            'group_id' => ['nullable', 'exists:groups,id'],
            'message_id' => ['nullable', 'exists:messages,id'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $message = $user->messages2()->create($validated);

        return new MessageResource($message);
    }
}
