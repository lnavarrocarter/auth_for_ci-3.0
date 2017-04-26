<form class="form-horizontal" method="post" action="<?= base_url('auth/password_reset');?>">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <div class="form-group">
        <input type="hidden" name="token" value="<?= $token ?>" required>
    </div>
    <div class="form-group">
        <input type="password" name="passwd" class="form-control" placeholder="Contraseña" required>
    </div>
    <div class="form-group">
        <input type="password" name="passwd2" class="form-control" placeholder="Confirmar contraseña" required>
    </div>
    <div class="form-group">
        <button type="submit" type="submit" class="btn btn-lg btn-default">Cambiar Contraseña</button>
    </div>
</form>