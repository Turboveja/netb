<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tarea
 * @package App\Models
 */
class Tarea extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    //Relations

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categorias_tareas', 'tarea_id', 'categoria_id')->withTimestamps();
    }

    //Methods

    /**
     * Obtener el nombre de las categorias
     *
     * @return string
     */
    public function getNombresCategorias($separator = '|')
    {
        $array_categorias = count($this->categorias) ? $this->categorias->implode('nombre', " $separator ") : '';
        return $array_categorias;
    }

    /**
     * @param $query
     * @param null $busqueda
     */
    public function scopeBusqueda($query, $busqueda = null)
    {
        if ($busqueda) {
            $query->where('nombre', 'like', "%$busqueda%");
        }
    }

    /**
     * @param $query
     * @param array $categorias
     */
    public function scopeByCategoria($query, $categorias = [])
    {
        //Comprobamos que nos han enviado el parámetro y filtramos
        if ($categorias && count($categorias)) {
            $query->whereHas('categorias', function ($f) use ($categorias) {
                $f->whereIn('categorias.id', $categorias);
            });
        }
    }

    /**
     * Para devolver en AJAX al gestionar el elemento
     *
     * @return array
     */
    public function formatJson()
    {
        $array_json = [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'categorias' => $this->getNombresCategorias(),
            'fecha_creacion' => $this->created_at->toDateTimeString(),
        ];

        return $array_json;
    }
}
