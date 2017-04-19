<form class="form-horizontal" method="post" action="<?= base_url('auth/register');?>">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <?php if (config_item('register_with_name')):?>
    <div class="form-group">
        <input type="text" name="name1" class="form-control" value="<?= $this->session->flashdata('name1')?>" placeholder="Nombre" required>
    </div>
    <div class="form-group">
        <input type="text" name="lastname1" class="form-control" value="<?= $this->session->flashdata('lastname1')?>" placeholder="Apellido" required>
    </div>
    <?php endif;?>
    <?php if (config_item('register_with_username')):?>
    <div class="form-group">
        <input type="text" name="username" class="form-control" value="<?= $this->session->flashdata('username')?>" placeholder="Nombre de Usuario" required>
    </div>
    <?php endif;?>
    <div class="form-group">
        <input type="text" name="email" class="form-control" value="<?= $this->session->flashdata('email')?>" placeholder="Correo electrónico" required>
    </div>
    <div class="form-group">
        <input type="password" name="passwd" class="form-control" placeholder="Contraseña" required>
    </div>
    <div class="form-group">
        <input type="password" name="passwd2" class="form-control" placeholder="Confirmar contraseña" required>
    </div>
    <?php if (config_item('register_with_terms')):?>
    <div class="form-group">
        <input type="checkbox" name="terms">
        <label for="remember" value="1"> Acepto los Términos</label>
    </div>
    <?php endif;?>
    <div class="form-group">
        <button type="submit" class="btn btn-lg btn-default">Registarme</button>
    </div>
</form>
