<div class="container">
    <div class="page-header">
        <h1><?= $title ?></h1>
    </div>
    <p class="lead">Aquí puedes ver un listado de los detalles de tu grupo y las personas en él.</p>
    <div class="panel panel-primary">
        <div class="panel-heading"><h3 style="color:#FFF">Detalles del Grupo</h3></div>

        <div class="panel-body"></div>
    </div>
    <table id="dataTable" class="table table-hover table-bordered">
        <thead>
            <tr>
                <th class="text-center text-middle">#</th>
                <th class="hidden-xs text-center text-middle">Nombre</th>
                <th class="text-center text-middle">Email</th>
                <th class="hidden-xs text-center text-middle">Permisos</th>
                <th class="hidden-xs text-center text-middle">Creado</th>
                <th class="hidden-xs text-center text-middle">Activo</th>
                <th class="text-center text-middle">
                    <button class="btn btn-xs btn-success new" data-toggle="modal" data-target="#newUser"><i class="fa fa-fw fa-plus"></i></button>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user):?>
            <?php if ($user->is_active):?><tr><?php else:?><tr class="danger"><?php endif;?>
                <th class="text-center text-middle"><?= $user->id ?></th>
                <th class="text-middle hidden-xs"><?= $user->name1.' '.$user->lastname1 ?></th>
                <th class=" text-middle"><?= $user->email ?></th>
                <th class="hidden-xs text-center text-middle">
                    <?php $user_permissions = get_permissions($user->permissions);?>
                    <?php foreach ($user_permissions as $key => $val):?>
                    <?= '<span class="label label-default">'.$val.' ('.$key.')</span> ';?>
                    <?php endforeach;?>
                </th>
                <th class="hidden-xs text-center text-middle"><?= strftime('%d/%m/%y', $user->created_at) ?></th>
                <th class="hidden-xs text-center text-middle"><?= strftime('%d/%m/%y', $user->lastlogin_time) ?></th>
                <th class="text-center text-middle">
                    <div class="btn-group">
                        <button class="btn btn-xs btn-primary edit" data-toggle="modal" data-target="#editUser" data-id="<?= $user->id ?>"><i class="fa fa-fw fa-pencil"></i></button>
                        <?php if ($user->is_active):?>
                        <button class="btn btn-xs btn-warning" data-toggle="tooltip" title="Desactivar" data-placement="top"><i class="fa fa-fw fa-lock"></i></button>
                        <?php else:?>
                        <button class="btn btn-xs btn-warning" data-toggle="tooltip" title="Activar" data-placement="top"><i class="fa fa-fw fa-unlock"></i></button>
                        <?php endif;?>
                        <button class="btn btn-xs btn-danger delete" data-toggle="tooltip" title="Eliminar" data-placement="top" data-id="<?= $user->id ?>"><i class="fa fa-fw fa-trash"></i></button>
                    </div>
                </th>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>
<!-- INICIO: Modal Nuevo Usuario -->
<div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-center">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Crear Nuevo Usuario</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="contentNewUser" class="col-md-10 col-md-offset-1">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FIN: Modal Nuevo Usuario -->
<!-- INICIO: Modal Editar Usuario -->
<div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-id>
    <div class="modal-dialog" role="document">
        <div class="modal-content text-center">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Editar Usuario</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1" id="contentEditUser">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FIN: Modal Editar Usuario -->

<!-- Scripts Para Esta Vista -->
<script>
// Llama el modal para crear un usuario.
$(".new").click( function () {
    $.ajax({
        url: baseUrl + 'users/new',
        method: 'get',
        dataType: 'html',
        success: function(response) {
            if (response) {
                $('#contentNewUser').html(response);
            } else {
                console.log('No data returned.');
            }
        }
     })
});

// Llama el modal para editar un usuario.
$(".edit").click( function () {
    var id = $(this).data('id');
    // Obtener Datos
    $.ajax({
        url: baseUrl + 'users/edit/'+id,
        method: 'get',
        dataType: 'html',
        success: function(response) {
            if (response) {
                $('#contentEditUser').html(response);
            } else {
                console.log('No data returned.');
            }
        }
     })
});

// Elimina un usuario
$(".delete").click( function () {
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
        swal("¡Eliminado!", "El usuario seleccionado ha sido eliminado.", "success");
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
</script>