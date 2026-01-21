<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    // Fetch all states with cities
    public function statesWithCities()
    {
        $states = State::with('cities')->get();

        return response()->json([
            'states' => $states
        ]);
    }

    // Fetch cities by state_id
    public function citiesByState($state_id)
    {
        $state = State::with('cities')->find($state_id);

        if (!$state) {
            return response()->json(['message' => 'State not found'], 404);
        }

        return response()->json([
            'state' => $state
        ]);
    }
}
