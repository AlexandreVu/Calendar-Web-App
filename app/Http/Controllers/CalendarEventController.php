<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Http\Resources\CalendarEventResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CalendarEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CalendarEventResource::collection(CalendarEvent::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $new_calendar = CalendarEvent::create($request->all());
        return response()->json([
            'data' => new CalendarEventResource($new_calendar),
            'message' => 'Successfully added new event!',
            'status' => Response::HTTP_CREATED
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(CalendarEvent $calendarEvent)
    {
        return response($calendarEvent, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        $calendarEvent->update($request->all());
        return response()->json([
            'data' => new CalendarEventResource($calendarEvent),
            'message' => 'Successfully updated event!',
            'status' => Response::HTTP_ACCEPTED
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CalendarEvent $calendarEvent)
    {
        $calendarEvent->delete();
        return response('Event removed successfully!', Response::HTTP_NO_CONTENT);
    }
}
