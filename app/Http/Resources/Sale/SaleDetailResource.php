<?php

namespace App\Http\Resources\Sale;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleDetailResource extends JsonResource
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
            'sale_id' => $this->sale_id,
            'product_id' => $this->product_id,
            'product_categorie_id' => $this->product_categorie_id,
            'unidad_medida' => $this->unidad_medida,
            'quantity' => $this->quantity,
            'price_final' => $this->price_final,
            'price_base' => $this->price_base,
            'discount' => $this->discount,
            'subtotal' => $this->subtotal,
            'igv' => $this->igv,
            'description' => $this->description,
            'tip_afe_igv' => $this->tip_afe_igv,
            'per_icbper' => $this->per_icbper,
            'icbper' => $this->icbper,
            'percentage_isc' => $this->percentage_isc,
            'isc' => $this->isc,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
