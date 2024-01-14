<?php

namespace App\Http\Controllers;
use App\Models\Threshold;
use Illuminate\Http\Request;

class ThresholdController extends Controller
{
    public function getThreshold()
    {
        $threshold = Threshold::first();
        return response()->json(['threshold' => $threshold->value ?? 20]);
    }

    public function updateThreshold(Request $request)
    {
        $request->validate([
            'value' => 'required|integer',
        ]);

        $threshold = Threshold::firstOrNew();
        $threshold->value = $request->input('value');
        $threshold->save();

        return response()->json(['message' => 'Threshold updated successfully']);
    }
}
