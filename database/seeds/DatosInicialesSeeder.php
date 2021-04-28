<?php

use App\Models\Categoria;
use App\Models\Tarea;
use Illuminate\Database\Seeder;

/**
 * Class DatosInicialesSeeder
 * @package Database\Seeders
 */
class DatosInicialesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Creación de tareas
        $tarea_1 = Tarea::create([
            'nombre' => 'Actualización a PHP 8.0'
        ]);
        $tarea_2 = Tarea::create([
            'nombre' => 'Uso de Typescript'
        ]);
        $tarea_3 = Tarea::create([
            'nombre' => 'Investigación sobre Bootstrap VS Flex'
        ]);

        //Creación de categorías
        $categoria_1 = Categoria::create(['nombre' => 'PHP']);
        $categoria_2 = Categoria::create(['nombre' => 'Javascript']);
        $categoria_3 = Categoria::create(['nombre' => 'CSS']);

        //Metemos la relación pivot
        $tarea_1->categorias()->sync($categoria_1);
        $tarea_2->categorias()->sync($categoria_2);
        $tarea_3->categorias()->sync($categoria_3);
    }
}
