<form id="createForm" class="form-horizontal" method="post" action="<?= base_url('groups/create');?>">
    <div class="form-group">
        <input type="text" name="name" class="form-control" placeholder="Nombre" required>
    </div>
    <div class="form-group">
        <input type="text" name="email" class="form-control" placeholder="Correo electrónico" required>
    </div>
    <div class="form-group">
        <input type="number" name="max_members" class="form-control" placeholder="Máximo de Miembros" required>
    </div>
    <div class="form-group">
        <input type="text" name="token" class="form-control" placeholder="Token de Acceso" required>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-success" value="Crear">
    </div>
</form>
<script>
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
            } catch(success) {
                $('#content').html(data);
                $('.modal-backdrop').hide(true);
                swal("¡Bien!", "El nuevo grupo ha sido creado.", "success");
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