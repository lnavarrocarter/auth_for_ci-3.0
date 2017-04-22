/*
|--------------------------------------------------------------------------
| Crear un nuevo usuario - Vista Users
|--------------------------------------------------------------------------
*/
$('body').on('click', '[new]', function () {
    $("#newUser").modal();
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
                $("body").removeClass('modal-open');
                swal("¡Bien!", "El nuevo usuario ha sido creado exitosamente.", "success");
            }
        }
    });
});

/*
|--------------------------------------------------------------------------
| Editar un Usuario - Vista Usuarios
|--------------------------------------------------------------------------
*/
$('body').on('click', '[edit]', function () {
    var id = $(this).data('id');
    $("#editUser").modal();
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
                $("#phone").mask("(+56) 9 9999 9999");
                $("#mobile").mask("(+56) 9 9999 9999");
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
                $("body").removeClass('modal-open');
                swal("¡Bien!", "El usuario ha sido editado exitosamente.", "success");
            }
        }
    });
});

/*
|--------------------------------------------------------------------------
| Eliminar Usuario - Vista Users
|--------------------------------------------------------------------------
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
| Bloquear Usuario- Vista Users
|--------------------------------------------------------------------------
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
| Desbloquear Usuario - Vista Users
|--------------------------------------------------------------------------
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

/*
|--------------------------------------------------------------------------
| Croppie
|--------------------------------------------------------------------------
*/

var start = $('.upload-demo').html();

$('body').on('change', '#upload', function () { 
    var reader = new FileReader();
    var measure = $('.upload-demo').width()
    $('#btnUpload').hide();
    $('#btnSave').show();
    $('#btnCancel').show();
    $uploadCrop = $('#upload-demo').croppie({
        enableExif: true,
        viewport: {
            width: measure,
            height: measure,
            type: 'circle'
        },
        boundary: {
            width: measure,
            height: measure
        }
    });
    reader.onload = function (e) {
        $uploadCrop.croppie('bind', {
            url: e.target.result
        }).then(function(){
            console.log('jQuery bind complete');
        });
    }
    reader.readAsDataURL(this.files[0]);
});

$('body').on('click', '#btnCancel', function () {
    $(".upload-demo").html(start);
    $('#btnUpload').show();
    $('#btnSave').hide();
    $('#btnCancel').hide();
});

$('#btnSave').on('click', function (ev) {
    var id = $(this).data('id');
    $uploadCrop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
    }).then(function (resp) {

        $.ajax({
            url: baseUrl+'users/change_profile_img/'+id,
            type: "POST",
            data: {"image":resp},
            success: function (data) {
                html = '<img id="upload-demo" src="' + resp + '" alt="User Pic" class="img-circle img-responsive" />';
                $(".upload-demo").html(html);
                $('#btnUpload').show();
                $('#btnSave').hide();
                $('#btnCancel').hide();
            }
        });
    });
});

/*
|--------------------------------------------------------------------------
| Cambiar Conraseña
|--------------------------------------------------------------------------
*/
$('body').on('click', '[passwd-ch]', function () {
    var id = $(this).data('id');
    $("#passwdChange").modal();
    // Obtener Datos
    $.ajax({
        url: baseUrl + 'users/passwd_change/'+id,
        method: 'get',
        dataType: 'html',
        success: function(response) {
            if (response) {
                $('#contentPasswdChange').html(response);
            } else {
                console.log('No data returned.');
            }
        }
     })
});

// Selects and blocks the form.
$('body').on('submit', '#passwdChange', function (ev) {
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
                $('.modal-backdrop').hide(true);
                $("body").removeClass('modal-open');
                swal("¡Bien!", "Su contraseña ha sido cambiada exitosamente.", "success");
            }
        }
    });
});

/*
|--------------------------------------------------------------------------
| Reestablecer Contraseña
|--------------------------------------------------------------------------
*/
$('body').on('click', '[passwd-rst]', function () {
var id = $(this).data('id');
    swal({
        title: "¿Estás seguro?",
        text: "Se enviará un correo electrónico al usuario para reestablecer su conraseña.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, estoy seguro",
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
    },
    function() {
        $.ajax({
            url: baseUrl + 'users/passwd_reset/'+id,
            method: 'get',
            dataType: 'html',
            success: function (data) {
                try {
                    json = $.parseJSON(data);
                    swal("¡Error!", json.msg, json.type);
                } catch (error) {
                    $('#content').html(data);
                    swal("¡Bien!", "Se ha enviado un correo con instrucciones al usuario para reestablecer su conraseña.", "success");
                }
            }
         })
    });
});