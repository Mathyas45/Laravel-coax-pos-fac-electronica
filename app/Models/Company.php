<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'razon_social',
        'razon_social_comercial',
        'phone',
        'email',
        'n_document',
        'birth_date',
        'address',
        'urbanizacion',
        'cod_local',
        'ubigeo_distrito',
        'ubigeo_provincia',
        'ubigeo_region',
        'distrito',
        'provincia',
        'region',
    ];

    public function setCreatedAtAttribute($value)
    {
        date_default_timezone_set('America/Lima');
        $this->attributes['created_at'] = Carbon::now();
    }
    public function setUpdatedAtAttribute($value)
    {
        date_default_timezone_set('America/Lima');
        $this->attributes['updated_at'] = Carbon::now();
    }
}
