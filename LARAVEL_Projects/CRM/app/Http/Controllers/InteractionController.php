<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Interaction;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    //
    public function store(Request $request, Customer $customer) {
        $validated = $request->validate([
            'type' => 'required|in:CALL,MEETING,EMAIL',
            'notes' => 'nullable|string',
            'duration_seconds' => 'required|integer|min:0',
            'occurred_at' => 'required|date',
        ]);

        $interaction = new Interaction($validated);

        $interaction->user_id = auth()->id();

        $interaction->customer_id = $customer->id;

        $interaction->save();

        return redirect()->back()->with('success', 'Aktivitas Sales berhasil dicatat!');
    }
}
