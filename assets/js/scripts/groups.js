$('#dataTable').DataTable();
// TODO: Editar el scripts de grupos para que diga groups
// Llama el modal para crear un usuario.
$('body').on('click', '.new', function () {
    $.ajax({
        url: baseUrl + 'users/new',
        method: 'get',
        dataType: 'html',
        success: function(response) {
            if (response) {
                $('#contentNewUser').html(response);
                // Renders the select picker.
                $('.selectpicker').selectpicker('render');
                // Crea un usuario
                var frm = $('#createForm');
                frm.submit(function (ev) {
                    ev.preventDefault();
                    $.ajax({
                        type: frm.attr('method'),
                        dataType: 'html',
                        url: frm.attr('action'),
                        data: frm.serialize(),
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
            } else {
                console.log('No data returned.');
            }
        }
     })
});

// Llama el modal para editar un usuario.
$('body').on('click', '.edit', function () {
    var id = $(this).data('id');
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
                // Selects and blocks the form.
                var frm = $('#editForm');
                frm.submit(function (ev) {
                    ev.preventDefault();
                    $.ajax({
                        type: frm.attr('method'),
                        dataType: 'html',
                        url: frm.attr('action'),
                        data: frm.serialize(),
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
            } else {
                console.log('No data returned.');
            }
        }
     })
});

// Elimina un usuario
$('body').on('click', '.delete', function () {
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
                } catch(success) {
                    $('#content').html(data);
                    $('#dataTable').DataTable();
                    swal("¡Eliminado!", "El usuario seleccionado ha sido eliminado.", "success");
                } finally {
                    if (json.type == 'error') {
                        swal("¡Error!", json.msg, "error");
                    } else {
                        success
                    }
                }
            }
         })
    });
});