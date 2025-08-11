<?php

namespace App\Http\Controllers\Zone;

use App\Http\Controllers\Controller;
use App\Models\Zone\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $zones = Zone::where('name', 'like', "%{$search}%")
            ->orWhere('codigo', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->orderBy('id', 'desc')
            ->paginate(25);
        
        return response()->json([
            "total" => $zones->total(),
            "pagination" => 25,
            "zones" => $zones->map(function ($zone) {
                return [
                    'id' => $zone->id,
                    'name' => $zone->name,
                    'codigo' => $zone->codigo,
                    'description' => $zone->description,
                    'created_at' => $zone->created_at->format('Y-m-d H:i A'),
                    'updated_at' => $zone->updated_at->format('Y-m-d H:i A'),
                ];
            }),
        ], 200);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'codigo' => 'required|string|max:255|unique:zone,codigo',
            'description' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'code' => 422,
            ], 422);
        }

        try {
            $zone = Zone::create([
                'name' => $request->name,
                'codigo' => $request->codigo,
                'description' => $request->description,
            ]);

            return response()->json([
                'message' => 'Zona creada exitosamente',
                'zone' => [
                    'id' => $zone->id,
                    'name' => $zone->name,
                    'codigo' => $zone->codigo,
                    'description' => $zone->description,
                    'created_at' => $zone->created_at->format('Y-m-d H:i A'),
                ],
                'code' => 201,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la zona',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $zone = Zone::findOrFail($id);

            return response()->json([
                'zone' => [
                    'id' => $zone->id,
                    'name' => $zone->name,
                    'codigo' => $zone->codigo,
                    'description' => $zone->description,
                    'created_at' => $zone->created_at->format('Y-m-d H:i A'),
                    'updated_at' => $zone->updated_at->format('Y-m-d H:i A'),
                ],
                'code' => 200,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Zona no encontrada',
                'code' => 404,
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $zone = Zone::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'codigo' => 'required|string|max:255|unique:zone,codigo,' . $id,
                'description' => 'required|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors(),
                    'code' => 422,
                ], 422);
            }

            $zone->update([
                'name' => $request->name,
                'codigo' => $request->codigo,
                'description' => $request->description,
            ]);

            return response()->json([
                'message' => 'Zona actualizada exitosamente',
                'zone' => [
                    'id' => $zone->id,
                    'name' => $zone->name,
                    'codigo' => $zone->codigo,
                    'description' => $zone->description,
                    'updated_at' => $zone->updated_at->format('Y-m-d H:i A'),
                ],
                'code' => 200,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la zona',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $zone = Zone::findOrFail($id);
            $zone->delete(); // Soft delete

            return response()->json([
                'message' => 'Zona eliminada exitosamente',
                'code' => 200,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la zona',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }

    /**
     * Restore a soft deleted zone.
     */
    public function restore(string $id)
    {
        try {
            $zone = Zone::onlyTrashed()->findOrFail($id);
            $zone->restore();

            return response()->json([
                'message' => 'Zona restaurada exitosamente',
                'zone' => [
                    'id' => $zone->id,
                    'name' => $zone->name,
                    'codigo' => $zone->codigo,
                    'description' => $zone->description,
                ],
                'code' => 200,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al restaurar la zona',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }

    /**
     * Get all trashed zones.
     */
    public function trashed()
    {
        $zones = Zone::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(25);

        return response()->json([
            "total" => $zones->total(),
            "pagination" => 25,
            "zones" => $zones->map(function ($zone) {
                return [
                    'id' => $zone->id,
                    'name' => $zone->name,
                    'codigo' => $zone->codigo,
                    'description' => $zone->description,
                    'deleted_at' => $zone->deleted_at->format('Y-m-d H:i A'),
                ];
            }),
        ], 200);
    }
}
