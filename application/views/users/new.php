<form id="createForm" class="form-horizontal" method="post" action="<?= base_url('users/create');?>">
    <div class="form-group">
        <input type="text" name="name1" class="form-control" value="<?= $this->session->flashdata('name1')?>" placeholder="Nombre" required>
    </div>
    <div class="form-group">
        <input type="text" name="lastname1" class="form-control" value="<?= $this->session->flashdata('lastname1')?>" placeholder="Apellido" required>
    </div>
    <?php if (config_item('register_with_username')):?>
    <div class="form-group">
        <input type="text" name="username" class="form-control" value="<?= $this->session->flashdata('username')?>" placeholder="Nombre de Usuario" required>
    </div>
    <?php endif;?>
    <div class="form-group">
        <input type="text" name="email" class="form-control" value="<?= $this->session->flashdata('email')?>" placeholder="Correo electrónico" required>
    </div>
    <div class="form-group">
        <select name="permissions[]" class="form-control selectpicker" multiple>
            <?php $perms = get_lower_permissions($this->session->userdata('permissions'))?>
            <?php foreach ($perms as $key => $val):?>
                <option value="<?= $key ?>"><?= $val ?></option>
            <?php endforeach;?>
        </select>
    </div>
    <div class="form-group">
        <input type="text" name="passwd" class="form-control" placeholder="Contraseña temporal" required>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-success" value="Crear">
    </div>
</form>