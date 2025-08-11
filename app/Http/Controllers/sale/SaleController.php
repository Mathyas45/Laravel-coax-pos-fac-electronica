<?php

namespace App\Http\Controllers\sale;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\ClientResource;
use App\Http\Resources\Product\ProductResource;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function config()
    {
        try {
            date_default_timezone_set('America/Lima');
            $today = now();
            $n_transactions = 1000;

            $clients = Client::where('state', 1)->orderBy('full_name', 'desc')->get();
            $products = Product::where('state', 1)->orderBy('is_especial_nota', 'desc')->get();

            return response()->json([
                'today' => $today,
                'n_transactions' => str_pad($n_transactions, 8, '0', STR_PAD_LEFT),
                'clients' => ClientResource::collection($clients),
                'products' => ProductResource::collection($products),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener la configuración de ventas',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
