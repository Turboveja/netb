@extends('adminlte::page')

@section('title', trans('strings.tareas'))

@section('content_header')
    <h1>@lang('strings.tareas')</h1>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card-header">


                        {!! Form::open(["id" => "form-principal", "url" => route('tareas.index'), "method" => "GET", "class" => "floating-labels"]) !!}
                        @csrf

                        <fieldset>
                            <div class="row">
                                <div class="col-3">
                                    {!! Form::text('nombre', request('nombre'), ['label' => trans('strings.nombre'), 'class' => 'form-control', 'autocomplete' => 'off']) !!}
                                    @csrf
                                </div>

                                <div class="col-3">
                                    @foreach($categorias as $row)
                                        <input id="categoria_{{ $row->id }}" name="categorias[]"
                                               type="checkbox"
                                               value="{{ $row->id }}"/>
                                        <label for="categoria_{{ $row->id }}" class="checkbox-inline">
                                            {{ ucfirst($row->nombre) }}
                                        </label>
                                    @endforeach
                                </div>

                                <div class="col-3">
                                    <button type="submit" class="btn waves-effect waves-light btn-filtrar"
                                            style="margin-left: 5px;">
                                        <i class="fa fa-search"></i> @lang('strings.filtrar')
                                    </button>

                                    <button type="button"
                                            id="btn-anadir"
                                            class="btn waves-effect waves-light btn-anadir"
                                            data-url-tab="{{ route('tareas.store') }}"
                                            style="margin-left: 5px;">
                                        <i class="fa fa-plus-circle"></i>
                                        @lang('strings.anadir')
                                        <i id="spinner-load-add" class="fa fa-spinner fa-spin"
                                           style="display:none;"></i>
                                    </button>
                                </div>
                            </div>
                        </fieldset>

                        {!! Form::close() !!}
                    </div>

                    <div class="card">
                        <div class="card-header"></div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6"></div>
                                    <div class="col-sm-12 col-md-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="example2"
                                               class="table table-bordered table-hover dataTable dtr-inline" role="grid"
                                               aria-describedby="example2_info">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example2"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Rendering engine: activate to sort column descending">
                                                    #
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="Browser: activate to sort column ascending">
                                                    @lang('strings.nombre')
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                    colspan="1"
                                                    aria-label="Engine version: activate to sort column ascending">
                                                    @lang('strings.categorias')
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                    colspan="1"
                                                    aria-label="Platform(s): activate to sort column ascending">
                                                    @lang('strings.created_at')
                                                </th>

                                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                    colspan="1"
                                                    aria-label="CSS grade: activate to sort column ascending">
                                                    @lang('strings.acciones')
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody id="table-body-elements">
                                            @forelse($tareas as $row)
                                                <tr id="tr-element-{{ $row->id }}" class="odd">
                                                    <td class="dtr-control sorting_1" tabindex="0">{{ $row->id }}</td>
                                                    <td>{{ $row->nombre }}</td>
                                                    <td>{{ $row->getNombresCategorias() }}</td>
                                                    <td>{{ $row->created_at }}</td>
                                                    <td>
                                                        <button type="button"
                                                                class="btn waves-effect waves-light btn-delete"
                                                                data-delete-element="{{ $row->id }}"
                                                                style="margin-left: 5px;">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>No hay registros</tr>
                                            @endforelse
                                            </tbody>
                                            <tfoot>

                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin_custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script type="application/javascript">
        let $add_button = $('#btn-anadir');

        $('.btn-delete').click(function () {
            deleteElement($(this).data('delete-element'));
        });

        /**
         * Función para eliminar elementos
         * @param element
         */
        function deleteElement(element) {
            let $form_params = $('#form-principal').serialize();
            let element_to_delete = element;

            $.ajax({
                url: 'tareas/' + element_to_delete,
                type: 'POST',
                data: {_method: 'DELETE', id: element_to_delete},
                success: function (data) {
                    //Toast success
                    toastr.success('Tarea eliminada correctamente.');

                    //Eliminamos la fila
                    let elemento_delete_html = 'tr-element-' + element;
                    $('#' + elemento_delete_html).remove();
                },
                error: function (error, status) {
                    toastr.error('Error eliminando tarea');
                }
            });
        }

        $add_button.click(function () {
            //Formamos la llamada AJAX para crear una nueva tarea
            let $form_params = $('#form-principal').serialize();
            let url = $add_button.data('url-tab');

            $.ajax({
                url: url,
                type: 'POST',
                data: $form_params,
                beforeSend: function () {
                    //Spinner abierto
                    $('#spinner-load-add').show(); //Mostramos el spinner
                },
                success: function (response) {
                    //Toast success
                    toastr.success('Tarea creada correctamente.');

                    //Añadimos el elemento a la tabla
                    $('#table-body-elements').append(
                        "<tr id='tr-element-" + response.id + "'><td>" + response.id +
                        "</td><td>" + response.nombre +
                        "</td><td>" + response.categorias +
                        "</td><td>" + response.fecha_creacion +
                        "</td><td>" + "<button " +
                        "type='button'" +
                        "class='btn waves-effect waves-light btn-delete'" +
                        "onclick='deleteElement(" + response.id + ")'" +
                        "data-delete-element='" + response.id + "'" +
                        "style='margin-left: 5px;'>" +
                        "<i class='fa fa-trash'></i>" +
                        "</button>" +
                        "</td></tr>");
                },

                error: function (error) {
                    //Toast error
                    if (error.status == 422) {
                        //Comprobamos si el error es de validación
                        let error_validacion = '';
                        $.each(error.responseJSON.errors, function (clave, item) {
                            error_validacion += '. ' + item;
                        });

                        toastr.error('Error de validación de datos' + error_validacion);
                    } else {
                        toastr.error('Error creando tarea');
                    }
                }, complete: function () {
                    //Spinner cerrado
                    $('#spinner-load-add').hide();
                },
            });
        });
    </script>
@stop
