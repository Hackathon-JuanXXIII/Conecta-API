<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logros extends Model
{
    protected $table = 'logros';
    protected $primaryKey = 'id';
    public $timestamps = false;

    // Campos rellenables
    protected $fillable = [
        'nombre',
        'descripcion',
        'meta',
        'foto_logro',
    ];

    protected $hidden = [];

    protected $casts = [
        'meta' => 'integer',
    ];

    // RELACIONES
    //-------------------------------------------------------
    public function logros_users(){
        return $this->hasMany(LogrosUsers::class);
    }

    // MÉTODOS PROPIOS
    //-------------------------------------------------------
    
    /**
     * Obtiene todos los logros
     * Ruta: /v1/logros
     * 
     * @return \App\Models\Logros|null
     */
    public static function getAllLogros(){
        return self::all();
    }

    /**
     * Obtiene un logro por Id
     * Ruta: /v1/logros/{id}
     * 
     * @param int $id
     * @return \App\Models\Logros|null
     */
    public static function getLogroById($id){
        return self::findOrFail($id);
    }
}
