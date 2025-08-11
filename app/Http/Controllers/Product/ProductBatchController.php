<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductBatch;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBatchController extends Controller
{
    /**
     * Display a listing of batches for a specific product.
     */
    public function index(Request $request, $productId)
    {
        try {
            $batches = ProductBatch::where('product_id', $productId)
                ->orderBy('expiration_date', 'asc')
                ->get();

            return response()->json([
                "code" => 200,
                "batches" => $batches,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "code" => 500,
                "message" => "Error al obtener los lotes: " . $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created batch.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validar que el product_id existe y es válido
            if (!$request->product_id || !is_numeric($request->product_id)) {
                return response()->json([
                    "code" => 400,
                    "message" => "ID de producto inválido"
                ]);
            }

            // Validar que el producto existe
            $product = Product::find($request->product_id);
            if (!$product) {
                return response()->json([
                    "code" => 404,
                    "message" => "El producto especificado no existe"
                ]);
            }

            // Generar código de lote único si no se proporciona
            $batchCode = $request->batch_code;
            if (!$batchCode) {
                $batchCode = 'LOT-' . $product->id . '-' . date('YmdHis');
            }

            // Verificar que el código de lote no exista
            $existingBatch = ProductBatch::where('batch_code', $batchCode)->first();
            if ($existingBatch) {
                return response()->json([
                    "code" => 409,
                    "message" => "Ya existe un lote con ese código"
                ]);
            }

            $batch = ProductBatch::create([
                'product_id' => $request->product_id,
                'batch_code' => $batchCode,
                'initial_stock' => $request->initial_stock ?? 0,
                'current_stock' => $request->initial_stock ?? 0,
                'expiration_date' => $request->expiration_date,
                'manufacture_date' => $request->manufacture_date,
                'cost_price' => $request->cost_price,
                'notes' => $request->notes,
                'is_active' => true
            ]);

            // Actualizar el stock total del producto
            $totalStock = ProductBatch::where('product_id', $request->product_id)
                ->where('is_active', true)
                ->sum('current_stock');
            
            $product->update(['stock' => $totalStock]);

            DB::commit();

            return response()->json([
                "code" => 201,
                "message" => "Lote creado correctamente",
                "batch" => $batch
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "code" => 500,
                "message" => "Error al crear el lote: " . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified batch.
     */
    public function show(string $id)
    {
        try {
            $batch = ProductBatch::with('product')->findOrFail($id);
            
            return response()->json([
                "code" => 200,
                "batch" => $batch,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "code" => 404,
                "message" => "Lote no encontrado"
            ]);
        }
    }

    /**
     * Update the specified batch.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $batch = ProductBatch::findOrFail($id);
            $oldStock = $batch->current_stock;

            // Verificar código de lote único (excluyendo el actual)
            if ($request->batch_code && $request->batch_code !== $batch->batch_code) {
                $existingBatch = ProductBatch::where('batch_code', $request->batch_code)
                    ->where('id', '!=', $id)
                    ->first();
                if ($existingBatch) {
                    return response()->json([
                        "code" => 409,
                        "message" => "Ya existe un lote con ese código"
                    ]);
                }
            }

            $batch->update($request->only([
                'batch_code',
                'initial_stock', 
                'current_stock',
                'expiration_date',
                'manufacture_date',
                'cost_price',
                'notes',
                'is_active'
            ]));

            // Actualizar el stock total del producto
            $totalStock = ProductBatch::where('product_id', $batch->product_id)
                ->where('is_active', true)
                ->sum('current_stock');
            
            $batch->product()->update(['stock' => $totalStock]);

            DB::commit();

            return response()->json([
                "code" => 200,
                "message" => "Lote actualizado correctamente",
                "batch" => $batch
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "code" => 500,
                "message" => "Error al actualizar el lote: " . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified batch.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $batch = ProductBatch::findOrFail($id);
            $productId = $batch->product_id;

            $batch->delete();

            // Actualizar el stock total del producto
            $totalStock = ProductBatch::where('product_id', $productId)
                ->where('is_active', true)
                ->sum('current_stock');
            
            Product::where('id', $productId)->update(['stock' => $totalStock]);

            DB::commit();

            return response()->json([
                "code" => 200,
                "message" => "Lote eliminado correctamente"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "code" => 500,
                "message" => "Error al eliminar el lote: " . $e->getMessage()
            ]);
        }
    }

    /**
     * Get batches that are expiring soon
     */
    public function expiringSoon(Request $request)
    {
        try {
            $days = $request->input('days', 30); // Por defecto 30 días
            $expiringDate = now()->addDays($days);

            $batches = ProductBatch::with('product')
                ->where('is_active', true)
                ->where('expiration_date', '<=', $expiringDate)
                ->where('current_stock', '>', 0)
                ->orderBy('expiration_date', 'asc')
                ->get();

            return response()->json([
                "code" => 200,
                "batches" => $batches,
                "expiring_in_days" => $days
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "code" => 500,
                "message" => "Error al obtener lotes por vencer: " . $e->getMessage()
            ]);
        }
    }
}
