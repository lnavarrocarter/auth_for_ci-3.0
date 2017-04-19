/*
|--------------------------------------------------------------------------
| Crear un nuevo usuario
|--------------------------------------------------------------------------
| Este es el AJAX Request para crear un nuevo usuario.
|
*/
$('body').on('click', '[new]', function () {
    $('#newUser').modal({ show: 'true' });
    $.ajax({
        url: baseUrl + 'users/new',
        method: 'get',
        dataType: 'html',
        success: function(response) {
            if (response) {
                $('#contentNewUser').html(response);
                // Renders the select picker.
                $('.selectpicker').selectpicker('render');
            } else {
                console.log('No data returned.');
            }
        }
     })
});

$('body').on('submit', '#createForm', function (ev) {
    ev.preventDefault();
    $.ajax({
        type: $(this).attr('method'),
        dataType: 'html',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (data) {
            try {
                json = $.parseJSON(data);
                swal("¡Error!", json.msg, json.type);
            } catch (error) {
                $('#content').html(data);
                $('#dataTable').DataTable();
                $('.modal-backdrop').hide(true);
                swal("¡Bien!", "El nuevo usuario ha sido creado exitosamente.", "success");
            }
        }
    });
});

/*
|--------------------------------------------------------------------------
| Editar un Usuario
|--------------------------------------------------------------------------
| Este es el AJAX Request para editar un nuevo usuario.
|
*/
$('body').on('click', '[edit]', function () {
    var id = $(this).data('id');
    $('#editUser').modal({ show: 'true' });
    // Obtener Datos
    $.ajax({
        url: baseUrl + 'users/edit/'+id,
        method: 'get',
        dataType: 'html',
        success: function(response) {
            if (response) {
                $('#contentEditUser').html(response);
                // Renders the select picker
                $('.selectpicker').selectpicker('render');
            } else {
                console.log('No data returned.');
            }
        }
     })
});

// Selects and blocks the form.
$('body').on('submit', '#editForm', function (ev) {
    ev.preventDefault();
    $.ajax({
        type: $(this).attr('method'),
        dataType: 'html',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (data) {
            try {
                json = $.parseJSON(data);
                swal("¡Error!", json.msg, json.type);
            } catch (error) {
                $('#content').html(data);
                $('#dataTable').DataTable();
                $('.modal-backdrop').hide(true);
                swal("¡Bien!", "El usuario ha sido editado exitosamente.", "success");
            }
        }
    });
});

/*
|--------------------------------------------------------------------------
| Eliminar Usuario
|--------------------------------------------------------------------------
| Este es el AJAX Request para eliminar un usuario.
|
*/
$('body').on('click', '[delete]', function () {
var id = $(this).data('id');
    swal({
        title: "¿Estás seguro?",
        text: "Esta acción no puede deshacerse.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, estoy seguro",
        closeOnConfirm: false
    },
    function() {
        $(".modal-body #sca_id").val( id );
        // Obtener Datos
        $.ajax({
            url: baseUrl + 'users/destroy/'+id,
            method: 'get',
            dataType: 'html',
            success: function (data) {
                try {
                    json = $.parseJSON(data);
                    swal("¡Error!", json.msg, json.type);
                } catch (error) {
                    $('#content').html(data);
                    $('#dataTable').DataTable();
                    swal("¡Bien!", "El usuario ha sido eliminado exitosamente.", "success");
                }
            }
         })
    });
});

/*
|--------------------------------------------------------------------------
| Bloquear Usuario
|--------------------------------------------------------------------------
| Este es el AJAX Request para eliminar un usuario.
|
*/
$('body').on('click', '[lock]', function () {
var id = $(this).data('id');
    $.ajax({
        url: baseUrl + 'users/lock/'+id,
        method: 'get',
        dataType: 'html',
        success: function (data) {
            try {
                json = $.parseJSON(data);
                swal("¡Error!", json.msg, json.type);
            } catch (error) {
                $('#content').html(data);
                $('#dataTable').DataTable();
                swal("¡Bien!", "El usuario ha sido bloqueado exitosamente.", "success");
            }
        }
     })
});

/*
|--------------------------------------------------------------------------
| Desbloquear Usuario
|--------------------------------------------------------------------------
| Este es el AJAX Request para eliminar un usuario.
|
*/
$('body').on('click', '[unlock]', function () {
var id = $(this).data('id');
    $.ajax({
        url: baseUrl + 'users/unlock/'+id,
        method: 'get',
        dataType: 'html',
        success: function (data) {
            try {
                json = $.parseJSON(data);
                swal("¡Error!", json.msg, json.type);
            } catch (error) {
                $('#content').html(data);
                $('#dataTable').DataTable();
                swal("¡Bien!", "El usuario ha sido desbloqueado exitosamente.", "success");
            }
        }
     })
});