@extends('main')

@section('content')
    <div class="container">
        <form id="course_form" method="POST">
            @csrf
            <div class="row">
                <input type="hidden" id="courseId">
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
                    <button type="button" class="btn btn-success" id="send_course">Nuevo</button>
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
            <tbody id="course_data">

            </tbody>
        </table>
    </div>

<script>
    $(document).ready(function () {
        let updated = false;
        list_course();

        /* CRUD Courses */

        function list_course(){
            $.ajax({
                url: '{{ url('api/course_api') }}',
                type: 'GET',
                dataType: 'json',
                success: function (e) {
                    let datos = e.data;
                    let contador = 1;
                    let row = '';
                    datos.forEach( course => {
                        row +=  `<tr course="${course.id}">
                                    <td> ${contador++}</td>
                                    <td> ${course.name} </td>
                                    <td> ${course.description} </td>
                                    <td><button type="button" class="btn btn-primary" id="edit_course" >Editar</button></td>
                                    <td><button type="button" class="btn btn-danger" id="deleted_course">Eliminar</button></td>
                                </tr>`;
                    });
                    $('#course_data').html(row);
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert('Error: ' + textStatus + ' - ' + errorThrown);
                }
            });
        }

        $('#send_course').click(function() {
            let name = $('input[name="name"]').val();
            let description = $('input[name="description"]').val();
            let courseId = $('input[id="courseId"]').val();

            let url = updated === false ? '{{ url('api/course_api') }}' : '{{ url('api/course_api') }}'+'/'+courseId;
            let method = updated === false ? 'POST' : 'PUT';

            $.ajax({
                url: url,
                type: method,
                data: {
                    'name': name,
                    'description': description
                },
                success: function (e) {
                    list_course();
                    $('#course_form').trigger('reset');
                    updated = false;
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert('Error: ' + textStatus + ' - ' + errorThrown);
                }
            });
        })

        $(document).on('click', '#deleted_course', function() {
            let button = $(this)[0].parentElement.parentElement;
            let course_id = $(button).attr('course');

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
                        url: '{{ url('api/course_api') }}'+'/'+course_id,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function (e) {
                            list_course();
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            alert('Error: ' + textStatus + ' - ' + errorThrown);
                        }
                    });
                }
            });
        });

        $(document).on('click', '#edit_course', function() {
            let button = $(this)[0].parentElement.parentElement;
            let course_id = $(button).attr('course');

            $.ajax({
                url: '{{ url('api/course_api') }}'+'/'+course_id,
                type: 'GET',
                dataType: 'json',
                success: function (e) {
                    $('input[id="courseId"]').val(e.data.id);
                    $('input[name="name"]').val(e.data.name);
                    $('input[name="description"]').val(e.data.description);
                    updated = true;
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert('Error: ' + textStatus + ' - ' + errorThrown);
                }
            });
        });
        /* ---------------------------------------------------------------------- */
    });

</script>

@endsection