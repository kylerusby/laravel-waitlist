<?php

namespace KyleRusby\LaravelWaitlist\Http\Controllers;

use Illuminate\Routing\Controller;
use KyleRusby\LaravelWaitlist\Http\Requests\StoreWaitlistRequest;
use KyleRusby\LaravelWaitlist\Models\Waitlist;

class WaitlistController extends Controller
{
    /**
     * Store a new waitlist entry.
     */
    public function store(StoreWaitlistRequest $request)
    {
        Waitlist::create([
            'email' => $request->validated('email'),
        ]);

        return back()->with('success', 'Added to waitlist!');
    }

    /**
     * Display the waitlist page.
     */
    public function index()
    {
        return view('waitlist::waitlist');
    }
}

