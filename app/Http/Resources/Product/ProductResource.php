<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'imagen' => $this->imagen ? env('APP_URL') . '/storage/' . $this->imagen : null,
            'categorie_id' => $this->categorie_id,
            'categorie' => [
                'id' => $this->categorie ? $this->categorie->id : null,
                'name' => $this->categorie ? $this->categorie->name : null,
            ],
            'price' => (float) $this->price_general,
            'price_general' => (float) $this->price_general,
            'price_company' => (float) $this->price_company,
            'description' => $this->description,
            'is_discount' => $this->is_discount,
            'max_discount' => (float) $this->max_discount,
            'disponibilidad' => $this->disponibilidad,
            'state' => $this->state,
            'unit' => $this->unidad_medida,
            'unidad_medida' => $this->unidad_medida,
            'stock' => (int) $this->stock,
            'stock_minimo' => (int) $this->stock_minimo,
            'include_igv' => $this->include_igv,
            'is_icbper' => $this->is_icbper,
            'is_ivap' => $this->is_ivap,
            'is_isc' => $this->is_isc,
            'percentage_isc' => (float) $this->percentage_isc,
            'is_especial_nota' => $this->is_especial_nota,
            'fecha_vencimiento' => $this->fecha_vencimiento ? $this->fecha_vencimiento->format('Y-m-d') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
