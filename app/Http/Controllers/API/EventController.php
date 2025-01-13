<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

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

    public function yourEvent()  {
        try {
            $events = Event::where('user_id', Auth::user()->id)->latest()->get();
            if ($events->count() > 0) {
                return response()->json(['events' => $events], 200);
            } else{
                return response()->json(['message' => 'event not found'], 404);
            }
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
            $event = new Event;
            $event->name = $request->name;
            $event->description = $request->description;
            $event->date = $request->date;
            $event->location = $request->location;
            $event->user_id = $request->user_id;
            $event->save();

            return response()->json(['message' => 'Event created successfully', 'event' => $event], 201);
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
            $event->name = $request->name;
            $event->description = $request->description;
            $event->date = $request->date;
            $event->location = $request->location;
            $event->user_id = $request->user_id;
            $event->save();

            return response()->json(['message' => 'Event updated successfully', 'event' => $event], 200);
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
