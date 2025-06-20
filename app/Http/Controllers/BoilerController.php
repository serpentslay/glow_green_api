<?php

namespace App\Http\Controllers;

use App\Models\Boiler;
use App\Models\Manufacturer;
use App\Models\FuelType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BoilerController extends Controller
{
    /**
     * Display a listing of boilers with optional filters.
     */
    public function index(Request $request)
    {
        // Optional filters: manufacturer_id, fuel_type_id, name (search)
        $query = Boiler::query();

        // Filter by manufacturer
        if ($request->has('manufacturer_id')) {
            $query->where('boiler_manufacturer_id', $request->query('manufacturer_id'));
        }

        // Filter by fuel type
        if ($request->has('fuel_type_id')) {
            $query->where('fuel_type_id', $request->query('fuel_type_id'));
        }

        // Search by name (partial match)
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->query('name') . '%');
        }

        // Eager load relations
        $boilers = $query->with(['manufacturer', 'fuelType'])->paginate(15);

        return response()->json($boilers);
    }

    /**
     * Display the specified boiler.
     */
    public function show($id)
    {
        $boiler = Boiler::with(['manufacturer', 'fuelType'])->find($id);

        if (!$boiler) {
            return response()->json(['message' => 'Boiler not found'], 404);
        }

        return response()->json($boiler);
    }

    /**
     * Store a newly created boiler in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'sku' => 'nullable|string|max:255',
            'boiler_manufacturer_id' => 'required|exists:manufacturers,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manufacturer_part_number' => 'required|string|max:255',
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'url' => 'nullable|url|max:255',
        ]);

        $boiler = Boiler::create($validated);

        return response()->json($boiler, 201);
    }

    /**
     * Update the specified boiler in storage.
     */
    public function update(Request $request, $id)
    {
        $boiler = Boiler::find($id);

        if (!$boiler) {
            return response()->json(['message' => 'Boiler not found'], 404);
        }

        $validated = $request->validate([
            'sku' => 'nullable|string|max:255',
            'boiler_manufacturer_id' => 'sometimes|required|exists:manufacturers,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'manufacturer_part_number' => 'sometimes|required|string|max:255',
            'fuel_type_id' => 'sometimes|required|exists:fuel_types,id',
            'url' => 'nullable|url|max:255',
        ]);

        $boiler->update($validated);

        return response()->json($boiler);
    }

    /**
     * Soft delete the specified boiler.
     */
    public function destroy($id)
    {
        $boiler = Boiler::find($id);

        if (!$boiler) {
            return response()->json(['message' => 'Boiler not found'], 404);
        }

        $boiler->delete();

        return response()->noContent();
    }
}

