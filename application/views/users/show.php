<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 style="color: #fff"><?= $user->name1.' '.$user->lastname1 ?></h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4" align="center">
                            <img alt="User Pic" src="<?= base_url('assets/img/profile/user_default.jpg')?>" class="img-circle img-responsive">
                        </div>
                        <div class="col-sm-8">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Fecha de Nacimiento:</td>
                                        <td><?php if (!$user->birthdate) { echo 'No Definido'; } else { echo strftime('%d/%m/%y', $user->birthdate); } ?></td>
                                    </tr>
                                    <tr>
                                        <tr>
                                            <td>Sexo</td>
                                            <td><?php if ($user->gender == 2 ) { echo 'Masculino'; } elseif ($user->gender == 1 ) { echo 'Femenino'; } else { echo 'No Definido'; } ?> </td>
                                        </tr>
                                        <tr>
                                            <td>Comuna, Ciudad, País</td>
                                            <td><?= $user->location ?></td>
                                        </tr>
                                        <tr>
                                            <td>Correo Electrónico</td>
                                            <td><a href="mailto:<?= $user->email ?>"><?= $user->email ?></a></td>
                                        </tr>
                                        <td>Teléfonos</td>
                                        <td><?= $user->mobile ?> (Móvil)<br>
                                            <br><?= $user->phone ?> (Otro)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Creado:</td>
                                        <td><?= strftime('%d/%m/%y',$user->created_at) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Editado:</td>
                                        <td><?= strftime('%d/%m/%y', $user->edited_at) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="#" class="btn btn-primary">My Sales Performance</a>
                            <a href="#" class="btn btn-primary">Team Sales Performance</a>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                <a data-original-title="Cambiar Foto" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="fa fa-photo fa-fw"></i></a>
                <span class="pull-right">
                    <a href="edit.html" data-original-title="Editar este usuario" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary">
                        <i class="fa fa-edit fa-fw"></i>
                    </a>
                    <?php if ($user->is_locked): ?>
                    <a href="edit.html" data-original-title="Bloquear este usuario" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning">
                        <i class="fa fa-unlock fa-fw"></i>
                    </a>
                    <?php else :?>
                    <a href="edit.html" data-original-title="Bloquear este usuario" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning">
                        <i class="fa fa-lock fa-fw"></i>
                    </a>
                    <?php endif;?>
                    <a data-original-title="Eliminar este usuario" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger">
                        <i class="fa fa-trash fa-fw"></i>
                    </a>
            </span>
                </div>
            </div>
        </div>
    </div>
</div>
