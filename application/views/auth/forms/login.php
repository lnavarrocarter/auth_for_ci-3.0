<form class="form-horizontal" method="post" action="<?= base_url('auth/login');?>">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <div class="form-group">
        <input type="text" name="login" class="form-control" value="<?= $this->session->flashdata('login')?>" placeholder="Correo Electrónico<?php if (config_item('register_with_username')):?> o Nombre de Usuario<?php endif;?>" required>
    </div>
    <div class="form-group">
        <input type="password" name="passwd" class="form-control" placeholder="Contraseña" required>
    </div>
    <div class="form-group">
        <input type="checkbox" name="remember">
        <label for="remember" value="1"> Recordarme</label>
    </div>
    <div class="form-group">
        <button type="submit" type="submit" class="btn btn-lg btn-default">Entrar</button>
    </div>
    <?php if (config_item('password_reset')):?>
    <p><a href="<?= base_url('auth/password_reset')?>">¿Olvidaste tu contraseña?</a></p>
    <?php endif;?>
</form>
