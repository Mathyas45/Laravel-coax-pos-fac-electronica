<?php

namespace App\Http\Resources\Client;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'razon_social' => $this->razon_social,
            'razon_social_comercial' => $this->razon_social_comercial,
            'phone' => $this->phone,
            'email' => $this->email,
            'n_document' => $this->n_document,
            'birth_date' => Carbon::parse($this->birth_date)->format('Y-m-d'),
            'address' => $this->address,
            'urbanizacion' => $this->urbanizacion,
            'cod_local' => $this->cod_local,
            'ubigeo_distrito' => $this->ubigeo_distrito,
            'ubigeo_provincia' => $this->ubigeo_provincia,
            'ubigeo_region' => $this->ubigeo_region,
            'distrito' => $this->distrito,
            'provincia' => $this->provincia,
            'region' => $this->region,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i A') : null,
        ];
    }
}
