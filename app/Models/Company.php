<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
