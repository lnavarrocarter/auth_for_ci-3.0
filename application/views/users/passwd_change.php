<form id="passwdChange" class="form-horizontal" method="post" action="<?= base_url('users/passwd_change/'.$user->id);?>">
    <div class="form-group">
        <input type="password" name="passwd_old" class="form-control" placeholder="Contraseña actual" required>
    </div>
    <div class="form-group">
        <input type="password" name="passwd" class="form-control" placeholder="Contraseña nueva" required>
    </div>
    <div class="form-group">
        <input type="password" name="passwd2" class="form-control" placeholder="Confirmar nueva contraseña" required>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-lg btn-default">Cambiar Contraseña</button>
    </div>
</form>