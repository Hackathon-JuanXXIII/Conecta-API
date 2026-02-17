<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use phpDocumentor\Reflection\Types\Boolean;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'nombre',
        'apellido',
        'telefono',
        'es_empresa',
        'es_familiar',
        'fecha_nacimiento',
        'porcentaje_discapacidad',
        'rol',
        'email',
        'password',
        'activo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'username' => 'string',
        'email_verified_at' => 'datetime',
        'fecha_nacimiento' => 'date',
        'es_empresa' => 'boolean',
        'es_familiar' => 'boolean',
        'porcentaje_discapacidad' => 'decimal:2',
        'password' => 'hashed',
        'activo' => 'boolean',
    ];

    // RELACIONES
    //-------------------------------------------------------
    public function rol(){
        return $this->belongsTo(Roles::class, 'rol', 'id');
    }

    public function eventosCreados(){
        return $this->hasMany(Eventos::class);
    }

    public function eventosDondeAsiste(){
        return $this->belongsToMany(Eventos::class, 'eventos_users', 'id_user', 'id_evento');
    }

    public function logrosUsers(){
        return $this->hasMany(LogrosUsers::class);
    }


    // MÉTODOS PROPIOS
    //-------------------------------------------------------


    /**
     * Devuelve el nombre completo del usuario concatenando nombre y apellidos.
     */
    public function nombre_completo(): string{
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Comprueba si el usuario es una empresa
     */
    public function es_empresa():bool{
        return $this->es_empresa;
    }

    /**
     * Comprueba si el usuario es un familiar
     */
    public function es_familiar():bool{
        return $this->es_familiar;
    }

    /**
     * Devuelve la edad del usuario (usa el método age de Carbon)
     */
    public function edad(): ?int
    {
        return $this->fecha_nacimiento ? $this->fecha_nacimiento->age : null;
    }

    /**
     * Comprueba si es un admin de la aplicación
     */
    public function esAdmin(): bool{
        return $this->rol === 1;
    }

    /**
     * Comprueba si es un usuario común de la aplicación
     */
    public function esDeveloper(): bool{
        return $this->rol === 2;
    }

    /**
     * Comprueba si es un usuario común de la aplicación
     */
    public function esUsuario(): bool{
        return $this->rol === 3;
    }

    /**
     * Comprueba si el usuario está activo en la base de datos
     */
    public function esActivo(): bool{
        return $this->activo;
    }

    // SCOPES
    //-------------------------------------------------------

    /**
     * Recoger los usuarios según si están activos o inactivos actualmente
     *
     * @param Builder $query
     * @param bool $state Boolean que dice si el usuario está activo o no
     * @return Builder
     */
    public function scopeActivo(Builder $query, bool $state): Builder{
        // return $query->where('activo', $state);
        return $query->where('activo', $state ? 1 : 0);
    }

    /**
     * Recoge los usuarios según si son o no empresas
     * 
     * @param Builder $query
     * @param bool $state Boolean que dice si es una empresa o no
     * @return Builder
     */
    public function scopeEmpresas(Builder $query, bool $state): Builder{
        return $query->where('es_empresa', $state);
    }
    
    /**
     * Recoge los usuarios según si son familiares o no
     * 
     * @param Builder $query
     * @param bool $state Boolean que dice si es familiar o no
     * @return Builder
     */
    public function scopeFamiliar(Builder $query , bool $state): Builder{
        return $query->where('es_familiar', $state);
    }

    /**
     * Recoge los usuarios según el rol que tienen
     * 
     * @param Builder $query
     * @param int $rol ID del rol que se quiere buscar
     * @return Builder
     */
    public function scopePorRol(Builder $query, int $rol): Builder{
        // Comprobar que el rol existe
        if (!Roles::where('id', $rol)->exists()){
            throw new ModelNotFoundException("El rol {$rol} no existe");
        }

        return $query->where('rol', $rol);
    }

    /**
     * Recoge un usuario buscando por su Username único
     * 
     * @param Builder $query
     * @param string $username Username único del usuario que se quiere buscar
     * @return Builder
     */
    public function scopeUsername(Builder $query, string $username): Builder{
        return $query->where('username', 'LIKE', $username.'%');
    }

    /**
     * Recoge un usuario buscando por su nombre
     * 
     * @param Builder $query
     * @param string $nombre Nombre del usuario que se quiere buscar
     * @return Builder
     */
    public function scopeNombre(Builder $query, string $nombre): Builder{
        return $query->where('nombre', 'LIKE', $nombre.'%');
    }

    /**
     * Recoge un usuario buscando por sus apellidos
     * 
     * @param Builder $query
     * @param string $apellido Apellidos del usuario que se quiere buscar
     * @return Builder
     */
    public function scopeApellido(Builder $query, string $apellido): Builder{
        return $query->where('apellido', 'LIKE', $apellido.'%');
    }

    /**
     * Comprueba que la búsqueda es coincidente con el nombre o el apellido del
     * usuario. 
     * 
     * @param Builder $query
     * @param string $busqueda Búsqueda que se quiere hacer en nombres y apellidos
     * @return Builder
     */
    public function scopeNombreCompleto(Builder $query, string $busqueda): Builder{
        return $query->where('nombre', 'LIKE', $busqueda.'%')->orWhere('apellido', 'LIKE', $busqueda.'%')->orWhere('username', 'LIKE', $busqueda.'%');
    }

    /**
     * Recoge un listado de eventos en los que el usuario participa o ha participado
     * 
     * @param Builder $query
     * @param string $user ID del usuario creador
     * @return Builder
     */
    public function scopeEventos(Builder $query, int $user):Builder{
        return $query->where('id', $user)->with(['eventosDondeAsiste' => function($q) {
            $q->with(
                ['categoria:id,nombre',
                'creador:id,username',
                'entidad:id,nombre',
                'aplicacion:id,nombre_app'
                ]);
        }]);
    }
}
