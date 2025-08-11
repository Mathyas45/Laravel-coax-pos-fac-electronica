<?php

namespace App\Http\Resources\Sale;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'serie' => $this->serie,
            'correlativo' => $this->correlativo,
            'n_operacion' => $this->n_operacion,
            'user_id' => $this->user_id,
            'client_id' => $this->client_id,
            'type_client' => $this->type_client,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'igv' => $this->igv,
            'state_sale' => $this->state_sale,
            'state_payment' => $this->state_payment,
            'type_payment' => $this->type_payment,
            'debt' => $this->debt,
            'paid_out' => $this->paid_out,
            'description' => $this->description,
            'discount' => $this->discount,
            'retencion_igv' => $this->retencion_igv,
            'discount_global' => $this->discount_global,
            'igv_discount_general' => $this->igv_discount_general,
            'n_comprobante_anticipo' => $this->n_comprobante_anticipo,
            'amount_anticipo' => $this->amount_anticipo,
            'cdr' => $this->cdr,
            'xml' => $this->xml,
            'is_exportacion' => $this->is_exportacion,
            'currency' => $this->currency,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sale_details' => $this->sale_details->map(fn($detail) => new SaleDetailResource($detail)),
            'payments' => $this->payments->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'sale_id' => $payment->sale_id,
                    'method_payment' => $payment->method_payment,
                    'amount' => $payment->amount,
                    'date_payment' => $payment->date_payment? Carbon::parse($payment->date_payment)->format('Y-m-d') : null,
                    'created_at' => $payment->created_at,
                    'updated_at' => $payment->updated_at,
                ];
            }),
        ];
    }
}
