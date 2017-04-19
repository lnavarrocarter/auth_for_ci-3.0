<form class="form-horizontal" method="post" action="<?= base_url('auth/password_reset');?>">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <div class="form-group">
        <input type="text" name="email" class="form-control" placeholder="Correo Electrónico" required>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-lg btn-default">Enviar Link</button>
    </div>
</form>
