<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IdeaStoreRequest;
use App\Http\Requests\IdeaUpdateRequest;
use App\IdeaStatus;
use App\Models\Idea;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        if (! in_array($status, IdeaStatus::values())) {
            $status = null;
        }

        $ideas = Auth::user()->ideas()
            ->when($status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->get();

        return view('idea.index', [
            'ideas' => $ideas,
            'counts' => Idea::statusCounts(Auth::user()),
        ]);
    }

    /**
     * Show create idea view.
     */
    public function create(): View
    {
        return view('idea.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IdeaStoreRequest $request): RedirectResponse
    {
        // Store idea
        $idea = Auth::user()->ideas()->create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'links' => $request->has('links') ? $request->links : [],
            'image_path' => $request->has('image') ? $request->image->store('ideas', 'public') : null,
        ]);

        // Store steps
        if ($idea->has('steps')) {
            $idea->steps()->createMany(
                collect($request->steps)->map(fn ($step) => ['description' => $step])
            );
        }

        return to_route('idea.index')
            ->with('success', 'Idea created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea): View
    {
        Gate::authorize('access', $idea);
        return view('idea.show', ['idea' => $idea]);
    }

    /**
     * views the edit page
     */
    public function edit(Idea $idea): View
    {
        Gate::authorize('access', $idea);
        return view('idea.edit', ['idea' => $idea]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IdeaUpdateRequest $request, Idea $idea): RedirectResponse
    {
        Gate::authorize('access', $idea);

        // Update idea
        $attributes = [
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'links' => $request->has('links') ? $request->links : [],
        ];

        if ($request->hasFile('image')) {
            // Delete old image if present
            if ($idea->image_path) {
                Storage::disk('public')->delete($idea->image_path);
            }
            $attributes['image_path'] = $request->file('image')->store('ideas', 'public');
        } elseif ($request->input('remove_image') === '1' && $idea->image_path) {
            Storage::disk('public')->delete($idea->image_path);
            $attributes['image_path'] = null;
        }
        
        $idea->update($attributes);

        // Update steps
        $idea->steps()->delete();

        if ($request->has('steps')) {
            $idea->steps()->createMany(
                collect($request->steps)->map(fn ($step) => ['description' => $step])
            );
        }

        return to_route('idea.show', $idea);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea): RedirectResponse
    {
        Gate::authorize('access', $idea);

        $idea->delete();

        return to_route('idea.index')
            ->with('success', 'Idea deleted successfully');
    }
}
