<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Factory|View
    {
        $ideas = Auth::user()->idea()
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->get();

        return view('idea.index', [
            'ideas' => $ideas,
            'counts' => Idea::statusCounts(Auth::user()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Idea $idea): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Idea $idea): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea): void
    {
        //
    }
}
