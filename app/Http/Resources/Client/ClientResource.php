<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'surname' => $this->surname,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'type_client' => $this->type_client,
            'type_client_text' => $this->type_client_text,
            'type_document' => $this->type_document,
            'n_document' => $this->n_document,
            'gender' => $this->gender,
            'gender_text' => $this->gender_text,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'age' => $this->birth_date ? $this->birth_date->age : null,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user'),
            'address' => $this->address,
            'ubigeo_distrito' => $this->ubigeo_distrito,
            'ubigeo_provincia' => $this->ubigeo_provincia,
            'ubigeo_region' => $this->ubigeo_region,
            'distrito' => $this->distrito,
            'provincia' => $this->provincia,
            'region' => $this->region,
            'state' => $this->state,
            'state_text' => $this->state == 1 ? 'Activo' : 'Inactivo',
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
