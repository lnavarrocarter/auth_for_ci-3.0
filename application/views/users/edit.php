<form id="editForm" class="form-horizontal" method="post" action="<?= base_url('users/update/'.$user->id);?>">
    <div class="form-group">
        <input type="text" name="name1" class="form-control" value="<?= $user->name1 ?>" placeholder="Nombre" required>
    </div>
    <div class="form-group">
        <input type="text" name="lastname1" class="form-control" value="<?= $user->lastname1 ?>" placeholder="Apellido" required>
    </div>
    <?php if (config_item('register_with_username')):?>
    <div class="form-group">
        <input type="text" name="username" class="form-control" value="<?= $user->username ?>" placeholder="Nombre de Usuario" required>
    </div>
    <?php endif;?>
    <div class="form-group">
        <input type="text" name="email" class="form-control" value="<?= $user->email ?>" placeholder="Correo electrónico" required>
    </div>
    <div class="form-group">
        <select name="permissions[]" class="form-control selectpicker" multiple>
            <?php $perms = get_permissions($this->session->userdata('permissions'))?>
            <?php $user_permissions = get_permissions($user->permissions);?>
            <?php foreach ($perms as $key => $val):?>
                <?php if(array_key_exists($key, $user_permissions)):?>
                    <option value="<?= $key ?>" selected><?= $val ?></option>
                <?php else:?>
                    <option value="<?= $key ?>"><?= $val ?></option>
                <?php endif;?>
            <?php endforeach;?>
        </select>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-success" value="Editar">
    </div>
</form>