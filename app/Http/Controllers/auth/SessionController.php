<?php

declare(strict_types=1);

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(): Factory|View
    {
        return view('auth.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $creds = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        if (! Auth::attempt($creds)) {
            return back()
                ->withErrors(['password' => 'Invalid credentials'])
                ->withInput();
        }

        $request->session()->regenerate();

        return redirect()->intended('/')->with('success', 'Logged in successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): Redirector|RedirectResponse
    {
        Auth::logout();

        return redirect('/login');
    }
}
