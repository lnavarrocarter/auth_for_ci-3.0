<form id="editForm" class="form-horizontal" method="post" action="<?= base_url('groups/update/'.$group->id);?>">
    <div class="form-group">
        <input type="text" name="name" class="form-control" value="<?= $group->name ?>" placeholder="Nombre" required>
    </div>
    <div class="form-group">
        <button id="submit" type="submit" class="btn btn-lg btn-primary">Editar</button>
    </div>
</form>