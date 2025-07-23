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
            'name' => $this->name,
            'sku' => $this->sku,
            'imagen' => $this->imagen ? env('APP_URL') . '/storage/' . $this->imagen : null,+
            'categorie_id' => $this->categorie_id,
            'categorie' => [
                'id' => $this->categorie->id,
                'name' => $this->categorie ? $this->categorie->name : null,
            ],
            'price_general' => $this->price_general,
            'price_company' => $this->price_company,
            'description' => $this->description,
            'is_discount' => $this->is_discount,
            'max_discount' => $this->max_discount,
            'disponibilidad' => $this->disponibilidad,
            'state' => $this->state,
            'unidad_medida' => $this->unidad_medida,
            'stock' => $this->stock,
            'stock_minimo' => $this->stock_minimo,
            'include_igv' => $this->include_igv,
            'is_icbper' => $this->is_icbper,
            'is_ivap' => $this->is_ivap,
            'porcentaje_isc' => $this->porcentaje_isc,
            'is_especial_nota' => $this->is_especial_nota,
            'fecha_vencimiento' => $this->fecha_vencimiento ? $this->fecha_vencimiento->format('Y-m-d') : null,
        ];
    }
}
