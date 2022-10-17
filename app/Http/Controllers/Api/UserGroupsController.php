<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupCollection;

class UserGroupsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        // $this->authorize('view', $user);

        $search = $request->get('search', '');

        $groups = $user
            ->groups()
            ->search($search)
            ->latest()
            ->paginate();

        return new GroupCollection($groups);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        // $this->authorize('create', Group::class);

        $validated = $request->validate([
            'image' => ['image', 'max:1024'],
            'name' => ['required', 'max:255', 'string'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $group = $user->groups()->create($validated);

        return new GroupResource($group);
    }
}
