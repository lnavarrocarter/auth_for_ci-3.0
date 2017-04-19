<form id="editForm" class="form-horizontal" method="post" action="<?= base_url('groups/update/'.$group->id);?>">
    <div class="form-group">
        <input type="text" name="name" class="form-control" value="<?= $group->name ?>" placeholder="Nombre" required>
    </div>
    <div class="form-group">
        <input type="text" name="email" class="form-control" value="<?= $group->email ?>" placeholder="Correo electrónico" required>
    </div>
    <div class="form-group">
        <input type="number" name="max_members" class="form-control" value="<?= $group->max_members ?>" placeholder="Máximo de Miembros" required>
    </div>
    <div class="form-group">
        <input type="text" name="token" class="form-control" value="<?= $group->token ?>" placeholder="Token de Acceso" required>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-success" value="Editar">
    </div>
</form>
<script>
// Crea un usuario
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
            } catch(success) {
                $('#content').html(data);
                $('.modal-backdrop').hide(true);
                swal("¡Bien!", "El grupo ha sido editado exitosamente.", "success");
            } finally {
                if (json.type == 'error') {
                swal("¡Error!", json.msg, "error");
                } else {
                success
                }
            }
        }
    });
});
</script>