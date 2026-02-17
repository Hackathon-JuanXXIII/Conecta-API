<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogrosUsers extends Model
{
    protected $table = 'logros_users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    // Campos rellenables
    protected $fillable = [
        'id_logro',
        'id_user',
        'progreso',
    ];

    protected $hidden = [];

    protected $casts = [
        'progreso' => 'integer',
    ];

    // RELACIONES
    //-------------------------------------------------------
    public function logro(){
        return $this->belongsTo(Logros::class, 'id_logro');
    }
    
    public function usuario(){
        return $this->belongsTo(User::class, 'id_user');
    }


    // MÉTODOS PROPIOS
    //-------------------------------------------------------
    
    /**
     * Obtiene todos los logros de los usuarios
     * Ruta: /v1/logros/usuarios
     * 
     * @return \App\Models\LogrosUsers|null
     */
    public static function getAllLogrosUsers(){
        return self::all();
    }

    /**
     * Obtiene un logro por Id
     * Ruta: /v1/logros/usuarios/{id}
     * 
     * @param int $id
     * @return \App\Models\LogrosUsers|null
     */
    public static function getLogroUserById($id){
        return self::findOrFail($id);
    }

    public static function getLogrosByUserId($userId){
        return self::where('id_user', $userId)->with('logro')->get();
    }
}
