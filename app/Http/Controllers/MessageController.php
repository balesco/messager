<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\MessageStoreRequest;
use App\Http\Requests\MessageUpdateRequest;

class MessageController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Message::class);

        $search = $request->get('search', '');

        $messages = Message::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.messages.index', compact('messages', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Message::class);

        $users = User::pluck('name', 'id');
        $groups = Group::pluck('name', 'id');
        $messages = Message::pluck('content', 'id');

        return view(
            'app.messages.create',
            compact('users', 'users', 'groups', 'messages')
        );
    }

    /**
     * @param \App\Http\Requests\MessageStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(MessageStoreRequest $request)
    {
        $this->authorize('create', Message::class);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $message = Message::create($validated);

        return redirect()
            ->route('messages.edit', $message)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Message $message
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Message $message)
    {
        $this->authorize('view', $message);

        return view('app.messages.show', compact('message'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Message $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Message $message)
    {
        $this->authorize('update', $message);

        $users = User::pluck('name', 'id');
        $groups = Group::pluck('name', 'id');
        $messages = Message::pluck('content', 'id');

        return view(
            'app.messages.edit',
            compact('message', 'users', 'users', 'groups', 'messages')
        );
    }

    /**
     * @param \App\Http\Requests\MessageUpdateRequest $request
     * @param \App\Models\Message $message
     * @return \Illuminate\Http\Response
     */
    public function update(MessageUpdateRequest $request, Message $message)
    {
        $this->authorize('update', $message);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            if ($message->image) {
                Storage::delete($message->image);
            }

            $validated['image'] = $request->file('image')->store('public');
        }

        $message->update($validated);

        return redirect()
            ->route('messages.edit', $message)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Message $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Message $message)
    {
        $this->authorize('delete', $message);

        if ($message->image) {
            Storage::delete($message->image);
        }

        $message->delete();

        return redirect()
            ->route('messages.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
