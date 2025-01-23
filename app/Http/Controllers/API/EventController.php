<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $events = Event::latest()->get();

            return response()->json(['events' => $events], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch events', 'error' => $e->getMessage()], 500);
        }
    }

    public function yourEvent()
    {
        try {
            $events = Event::where('user_id', Auth::user()->id)->latest()->get();

            return response()->json(['events' => $events], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch events', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'event_date' => 'required|date',
                'location' => 'required|string|max:255',
            ]);

            $event = new Event;
            $event->name = $validated['name'];
            $event->description = $validated['description'];
            $event->event_date = $validated['event_date'];
            $event->location = $validated['location'];
            $event->user_id = Auth::user()->id;
            $event->save();

            return response()->json(['message' => 'Event created successfully', 'event' => $event], 201);
        } catch (ValidationException $e) {

            $errors = $e->errors();
            $firstError = array_values($errors)[0][0];

            return response()->json(['message' => 'Validation failed', 'error' => $firstError], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create event', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $event = Event::findOrFail($id);

            return response()->json($event, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Event not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch event', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'event_date' => 'required|date|after_or_equal:today',
                'location' => 'required|string|max:255',
            ]);
            $event->name = $validatedData['name'];
            $event->description = $validatedData['description'];
            $event->event_date = $validatedData['event_date'];
            $event->location = $validatedData['location'];
            $event->user_id = Auth::user()->id;
            $event->save();

            return response()->json(['message' => 'Event updated successfully', 'event' => $event], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $firstError = array_values($errors)[0][0];

            return response()->json(['message' => 'Validation error', 'error' => $firstError], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update event', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $event = Event::find($id);
            if (! $event) {
                return response()->json(['message' => 'Event not found'], 404);
            }
            $event->delete();

            return response()->json(['message' => 'Event deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete event', 'error' => $e->getMessage()], 500);
        }
    }
}
