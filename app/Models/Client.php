<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'full_name',
        'phone',
        'email',
        'type_client',
        'type_document',
        'n_document',
        'gender',
        'birth_date',
        'user_id',
        'address',
        'ubigeo_distrito',
        'ubigeo_provincia',
        'ubigeo_region',
        'distrito',
        'provincia',
        'region',
        'state',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'datetime',
        'type_client' => 'integer',
        'state' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * Scope para filtrar clientes activos
     */
    public function scopeActive($query)
    {
        return $query->where('state', 1);
    }

    /**
     * Scope para filtrar por tipo de cliente
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type_client', $type);
    }

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor para obtener el nombre completo
     */
    public function getFullNameAttribute()
    {
        if ($this->attributes['full_name']) {
            return $this->attributes['full_name'];
        }
        
        return trim($this->name . ' ' . $this->surname);
    }

    /**
     * Accessor para obtener el tipo de cliente en texto
     */
    public function getTypeClientTextAttribute()
    {
        return $this->type_client == 1 ? 'Cliente Normal' : 'Empresa';
    }

    /**
     * Accessor para obtener el género en texto
     */
    public function getGenderTextAttribute()
    {
        switch ($this->gender) {
            case 'M':
                return 'Masculino';
            case 'F':
                return 'Femenino';
            default:
                return 'No especificado';
        }
    }
}
