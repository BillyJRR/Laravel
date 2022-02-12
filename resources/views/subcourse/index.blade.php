@extends('main')

@section('content')
    <div class="container">
        <form id="subcourse_form" method="POST">
            @csrf
            <div class="row">
                <input type="hidden" id="subId">
                <div class="col-md-6 p-2">
                    <div class="input-group">
                        <label class="input-group-text">Curso</label>
                        <select class="form-select" name="course">
                            <option selected>Elija el curso al que pertenece</option>
                            @foreach($course as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 p-2">
                    <div class="input-group">
                        <span class="input-group-text">Nombre</span>
                        <input type="text" class="form-control" name="name">
                    </div>
                </div>
                <div class="col-md-6 p-2">
                    <div class="input-group">
                        <span class="input-group-text">Descripción</span>
                        <input type="text" class="form-control" name="description">
                    </div>
                </div>
                <div class="col-md-12 text-center pt-4">
                    <button type="button" class="btn btn-success" id="send_subcourse">Nuevo</button>
                </div>
            </div>
        </form>
        <table class="table table-striped text-center mt-4">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="subcourse_data">
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            let updated = false;
            list_subcourse();
    
            /* CRUD Subcourses */
    
            function list_subcourse(){
                $.ajax({
                    url: '{{ url('api/subcourse_api') }}',
                    type: 'GET',
                    dataType: 'json',
                    success: function (e) {
                        let datos = e.data;
                        let contador = 1;
                        let row = '';
                        datos.forEach( subcourse => {
                            row +=  `<tr subcourse="${subcourse.id}">
                                        <td> ${contador++}</td>
                                        <td> ${subcourse.name} </td>
                                        <td> ${subcourse.description} </td>
                                        <td><button type="button" class="btn btn-primary" id="edit_subcourse" >Editar</button></td>
                                        <td><button type="button" class="btn btn-danger" id="deleted_subcourse">Eliminar</button></td>
                                    </tr>`;
                        });
                        $('#subcourse_data').html(row);
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        alert('Error: ' + textStatus + ' - ' + errorThrown);
                    }
                });
            }
    
            $('#send_subcourse').click(function() {
                let name = $('input[name="name"]').val();
                let description = $('input[name="description"]').val();
                let course = $('select[name="course"]').val();
                let subId = $('input[id="subId"]').val();
    
                let url = updated === false ? '{{ url('api/subcourse_api') }}' : '{{ url('api/subcourse_api') }}'+'/'+subId;
                let method = updated === false ? 'POST' : 'PUT';
    
                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        'courses_id': course,
                        'name': name,
                        'description': description
                    },
                    success: function (e) {
                        list_subcourse();
                        $('#subcourse_form').trigger('reset');
                        updated = false;
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        alert('Error: ' + textStatus + ' - ' + errorThrown);
                    }
                });
            })
    
            $(document).on('click', '#deleted_subcourse', function() {
                let button = $(this)[0].parentElement.parentElement;
                let subcourse_id = $(button).attr('subcourse');
    
                Swal.fire({
                    title: '¿Seguro que desea eliminar el registro?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: '¡Si, eliminar!'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url('api/subcourse_api') }}'+'/'+subcourse_id,
                            type: 'DELETE',
                            dataType: 'json',
                            success: function (e) {
                                list_subcourse();
                            },
                            error: function(jqXHR, textStatus, errorThrown){
                                alert('Error: ' + textStatus + ' - ' + errorThrown);
                            }
                        });
                    }
                });
            });
    
            $(document).on('click', '#edit_subcourse', function() {
                let button = $(this)[0].parentElement.parentElement;
                let subcourse_id = $(button).attr('subcourse');
    
                $.ajax({
                    url: '{{ url('api/subcourse_api') }}'+'/'+subcourse_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function (e) {
                        $('input[id="subId"]').val(e.data.id);
                        $('input[name="name"]').val(e.data.name);
                        $('input[name="description"]').val(e.data.description);
                        $('select[name="course"]').val(e.data.courses_id);
                        updated = true;
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        alert('Error: ' + textStatus + ' - ' + errorThrown);
                    }
                });
            });
            /* --------------------------------------------------------------------------- */
        });
    
    </script>

@endsection
