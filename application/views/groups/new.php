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
        <button id="submit" type="submit" class="btn btn-lg btn-success">Crear</button>
    </div>
</form>