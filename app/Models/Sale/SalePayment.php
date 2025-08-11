<?php

namespace App\Models\Sale;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
    protected $fillable = [
    "sale_id",
    "method_payment",
    "amount",
    "date_payment"
    ];

    public function setCreatedAtAttribute($value)
    {
        date_default_timezone_set('America/Lima');
        $this->attributes["created_at"]= Carbon::now();
    }

    public function setUpdatedAtAttribute($value)
    {
        date_default_timezone_set("America/Lima");
        $this->attributes["updated_at"]= Carbon::now();
    }
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
