<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Tarea;
use Illuminate\Http\Request;

/**
 * Class TareaController
 * @package App\Http\Controllers
 */
class TareaController extends Controller
{
    /**
     * @var string
     */
    private $vistas = 'frontend/tareas';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tareas = Tarea::busqueda($request->nombre)->byCategoria($request->categorias)->with('categorias')->get();
        $categorias = Categoria::get(); //Elementos para poder filtrar

        return view($this->vistas . '.index', compact('tareas', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        return view($this->vistas . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validamos los datos
        $this->validate($request, [
            'nombre' => 'required|string|max:100',
            'categorias' => 'nullable|array',
            'categorias.*' => 'exists:categorias,id',
        ]);

        try {
            //Añadimos la tarea
            $tarea = Tarea::create([
                'nombre' => $request->nombre,
            ]);

            //Añadimos las categorias si hay
            if ($request->categorias && count($request->categorias)) {
                $tarea->categorias()->sync($request->categorias);
            }

            if ($request->ajax()) {
                return response()->json($tarea->formatJson());
            } else {
//                return redirect()->with(); //Redirección al form con mensaje
            }
        } catch (\Exception $ex) {
            \Log::error('Error on Create Tarea', [
                'request' => $request->all(),
                'exception' => $ex
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        try {
//            Tarea::withTrashed()->findOrFail($id);
//
//            return view($this->vistas . '.show', compact('tarea'));
//        } catch (\Exception $ex) {
//            abort(404);
//        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            Tarea::findOrFail($id);

            return view($this->vistas . '.edit', compact('tarea'));
        } catch (\Exception $ex) {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        //validamos los datos
//        try {
//            Tarea::find($id)->update([
//
//            ]);
//        } catch (\Exception $ex) {
//            \Log::error('Error on Create Tarea', [
//                'request' => $request->all(),
//                'exception' => $ex
//            ]);
//        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                //Buscamos la tarea y la eliminamos
                $tarea = Tarea::findOrFail($id);
                $tarea->delete();

                return response()->json();
            } catch (\Exception $ex) {
                \Log::error('Error on Ajax Delete Tarea', [
                    'request' => request()->all(),
                    'id' => $id,
                    'exception' => $ex
                ]);
                return response()->json([], 400);
            }
        }
    }
}
