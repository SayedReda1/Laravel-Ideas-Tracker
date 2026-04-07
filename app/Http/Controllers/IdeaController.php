<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\IdeaStatus;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request, Idea $idea): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(IdeaStatus::class)],
            'links' => ['nullable', 'array'],
            'links.*' => ['url'],
        ]);

        Auth::user()->ideas()->create($validated);

        return to_route('idea.index')
            ->with('success', 'Idea created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea): View
    {
        return view('idea.show', ['idea' => $idea]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea): View
    {
        return view('idea.edit', [
            'idea' => $idea,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Idea $idea): RedirectResponse
    {
        return to_route('idea.show', $idea);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea): RedirectResponse
    {
        // authorize
        $idea->delete();

        return to_route('idea.index')
            ->with('success', 'Idea deleted successfully');
    }
}
