<form id="createForm" class="form-horizontal" method="post" action="<?= base_url('groups/create');?>">
    <div class="form-group">
        <input type="text" name="name" class="form-control" placeholder="Nombre" required>
    </div>
    <div class="form-group">
        <button id="submit" type="submit" class="btn btn-lg btn-success">Crear</button>
    </div>
</form>