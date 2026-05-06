<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reminder = $request->user()->reminders()->get();
        return response()->json($reminder);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'note' => 'nullable|string',
            'remind_at' => 'required|date|after:now',
        ]);
        $reminder = $request->user()->reminders()->create($validate);
        return response()->json($reminder, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reminder $reminder, Request $request)
    {
        if ($reminder->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return response()->json($reminder);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $validate = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'note' => 'nullable|string',
            'remind_at' => 'sometimes|required|date|after:now',
        ]);
        $reminder->update($validate);
        return response()->json($reminder);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        Reminder::destroy($reminder->id);
        return response()->json(['message' => 'Reminder deleted']);
    }
}
