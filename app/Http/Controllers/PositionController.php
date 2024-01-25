<?php

namespace App\Http\Controllers;

use App\Models\positionType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PositionController extends Controller
{
    public function getPositions($department)
    {
        // Fetch positions based on the selected department
        $positions = positionType::where('department', $department)->get();

        // Return positions as JSON
        return response()->json($positions);
    }
}