<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
     public function index(Request $request)
    {
        $search = $request->input('search');
        $categorie_id = $request->input('categorie_id');
        $state = $request->input('state');
        $unidad_medida = $request->input('unidad_medida');

        $products = Product::filterAdvance(
            $search,
            $categorie_id,
            $state,
            $unidad_medida
        )->orderBy('id', 'desc')->paginate(25);
        return response()->json([
            "total" => $products->total(),
            "pagination" => 25,
            "products" => ProductCollection::make($products),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $is_exists = User::where('name', $request->name)->first();
        if ($is_exists) {
            return response()->json([
                "code" => 409,
                "message" => "Un  producto ya existe con ese nombre"
            ]); // Return a 400 Bad Request response
        }
        $exist_sku = Product::where('sku', $request->sku)->first();
        if ($exist_sku) {
            return response()->json([
                "code" => 409,
                "message" => "Un producto ya existe con ese sku"
            ]);
        }

        if($request ->hasFile("image")){
            $path = Storage::putFile("products", $request->file("image"));
            $request->merge(['imagen' => $path]);
        }

        $product = Product::create($request->all());

        return response()->json([
            "code" => 201,
            "message" => "Producto creado correctamente",
        ]);
    }
    
    public function config(){
        $categories = Categorie::where('state', 1)->get();
        return response()->json([
            "categories" => $categories->map(function ($categorie) {
                return [
                    'id' => $categorie->id,
                    'name' => $categorie->name,
                ];
            }),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
           "product" =>ProductResource::make($product),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
          $is_exists = User::where("id", "<>", $id)->where('name', $request->name)->first();
        if ($is_exists) {
            return response()->json([
                "code" => 409,
                "message" => "Un  producto ya existe con ese nombre"
            ]); // Return a 400 Bad Request response
        }
        $exist_sku = Product::where("id", "<>", $id)->where('sku', $request->sku)->first();
        if ($exist_sku) {
            return response()->json([
                "code" => 409,
                "message" => "Un producto ya existe con ese sku"
            ]);
        }
        $product_image_exist = Product::findOrFail($id);
        if($request ->hasFile("image")){
            if($product_image_exist -> imagen) {
                Storage::delete($product_image_exist->imagen);
            }
            $path = Storage::putFile("products", $request->file("image"));
            $request->merge(['imagen' => $path]);
        }

        $product = Product::update($request->all());

        return response()->json([
            "code" => 201,
            "message" => "Producto actualizado correctamente",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        if ($product->imagen) {
            Storage::delete($product->imagen);
        }
        $product->delete();
        return response()->json([
            "code" => 200,
            "message" => "Producto eliminado correctamente"
        ]);
    }
}
