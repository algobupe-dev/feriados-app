<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Holiday::orderBy('date', 'asc')->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date|unique:holidays,date',
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'inalienable' => 'boolean',
            'extra' => 'nullable|string',
        ]);

        $holiday = Holiday::create($data);

        return response()->json($holiday, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Holiday $holiday)
    {
        return response()->json($holiday);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Holiday $holiday)
    {
        $data = $request->validate([
            'date' => 'required|date|unique:holidays,date,' . $holiday->id,
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'inalienable' => 'boolean',
            'extra' => 'nullable|string',
        ]);

        $holiday->update($data);

        return response()->json($holiday);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return response()->json(null, 204);
    }
}