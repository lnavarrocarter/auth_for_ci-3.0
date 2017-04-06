<div class="inner cover">
    <h1 class="cover-heading">Nueva Contraseña</h1>
    <form class="form-horizontal" method="post" action="<?= base_url('auth/password_reset');?>">
        <div class="row">
            <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-8 col-md-offset-2">
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
            </div>
        </div>
        <input type="submit" class="btn btn-lg btn-default" value="Cambiar Contraseña">
    </form>
</div>
