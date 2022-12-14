<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\GroupStoreRequest;
use App\Http\Requests\GroupUpdateRequest;

class GroupController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Group::class);

        $search = $request->get('search', '');

        $groups = Group::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.groups.index', compact('groups', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Group::class);

        $users = User::pluck('name', 'id');

        return view('app.groups.create', compact('users'));
    }

    /**
     * @param \App\Http\Requests\GroupStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupStoreRequest $request)
    {
        $this->authorize('create', Group::class);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $group = Group::create($validated);

        return redirect()
            ->route('groups.edit', $group)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Group $group)
    {
        $this->authorize('view', $group);

        return view('app.groups.show', compact('group'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Group $group)
    {
        $this->authorize('update', $group);

        $users = User::pluck('name', 'id');

        return view('app.groups.edit', compact('group', 'users'));
    }

    /**
     * @param \App\Http\Requests\GroupUpdateRequest $request
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function update(GroupUpdateRequest $request, Group $group)
    {
        $this->authorize('update', $group);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            if ($group->image) {
                Storage::delete($group->image);
            }

            $validated['image'] = $request->file('image')->store('public');
        }

        $group->update($validated);

        return redirect()
            ->route('groups.edit', $group)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Group $group)
    {
        $this->authorize('delete', $group);

        if ($group->image) {
            Storage::delete($group->image);
        }

        $group->delete();

        return redirect()
            ->route('groups.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
