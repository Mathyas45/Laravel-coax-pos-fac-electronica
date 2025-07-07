<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Models\Product\Categorie;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function index(Request $request)
    {
        $search = $request->get('search');
        $categories = Categorie::where('name', 'like', "%{$search}%")->orderBy('id', 'desc')->paginate(25);
        
        return response()->json([
            "total" => $categories->total(),
            "pagination" => 25,
            "categories" => $categories->map(function ($categorie) {
                return [
                    'id' => $categorie->id,
                    'name' => $categorie->name,
                    'state' => $categorie->state,
                    'description' => $categorie->description,
                    'image' => env('APP_URL') . '/storage/' . $categorie->image,
                    'created_at' => $categorie->created_at->format('Y-m-d H:i A'),
                ];
            }),
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $existRole = Categorie::where('name', $request->name)->first();
        if($existRole) {
            return response()->json([
                'message' => 'Ya existe una categoria con este nombre', 
                'code' => 409,
            ]);
        }
        $path = '';
        if ($request->hasFile('image')) {
            $path = Storage::putFile('categories', $request->file('image'));
        }

        $categorie = Categorie::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $path, // Assuming image is a URL or path
            'state' => $request->state ?? 1, // Default to active if not provided
        ]);
      
        return response()->json([
            'categorie' => [
                'id' => $categorie->id,
                'name' => $categorie->name,
                'state' => $categorie->state,
                'description' => $categorie->description,
                'image' => env('APP_URL') . '/storage/' . $categorie->image,
                'created_at' => $categorie->created_at->format('Y-m-d H:i A'),
            ],
            "code" => 201,
            "message" => "Categoria creada exitosamente",
        ], );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $existRole = Categorie::where('name', $request->name)
            ->where('id', '!=', $id)
            ->first();

        if($existRole) {
            return response()->json([
                'message' => 'Ya existe una categoria con este nombre', 
                'code' => 409,
            ]);
        }
        $categorie = Categorie::findOrFail($id);

        $path = '';
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($categorie->image) {
                Storage::delete($categorie->image);
            }
            $path = Storage::putFile('categories', $request->file('image'));
            $categorie->update(['image' => $path]);
        }

        $categorie->update([
            'name' => $request->name,
            'description' => $request->description,
            'state' => $request->state ?? $categorie->state, // Keep the old state if not provided
        ]);
      
        return response()->json([
            'categorie' => [
                'id' => $categorie->id,
                'name' => $categorie->name,
                'state' => $categorie->state,
                'description' => $categorie->description,
                'image' => env('APP_URL') . '/storage/' . $categorie->image,
                'created_at' => $categorie->created_at->format('Y-m-d H:i A'),
            ],
            "code" => 201,
            "message" => "Categoria editada exitosamente",
        ], );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->delete();

        return response()->json([
            'message' => 'Categoria eliminada exitosamente',
            'code' => 200,
        ]);
    }
}
