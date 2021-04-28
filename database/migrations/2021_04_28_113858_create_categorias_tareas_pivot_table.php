<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCategoriasTareasPivotTable
 */
class CreateCategoriasTareasPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias_tareas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('tarea_id');
            $table->foreign('categoria_id', 'categorias_categorias_tareas')->references('id')->on('categorias');
            $table->foreign('tarea_id', 'tareas_categorias_tareas')->references('id')->on('tareas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorias_tareas');
    }
}
