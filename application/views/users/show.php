<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1><?= $title ?></h1>
            </div>
            <p class="lead"><?=$description?></p>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <?php if ($user->is_locked):?>
            <div class="panel panel-danger">
            <?php else :?>
            <div class="panel panel-default">
            <?php endif;?>
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <?php if ($user->name1):?>
                    <?= $user->name1.' '.$user->lastname1 ?><?php if ($user->username) { echo ' (@'.$user->username.')'; } ?>
                    <?php elseif ($user->username):?>
                    <?= $user->username ?>
                    <?php else:?>
                    <?= $user->email ?>
                    <?php endif; ?>   
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4 upload-demo" align="center">
                            <div class="upload-demo">
                            <?php if ($user->avatar_url):?>
                            <img id="upload-demo" alt="User Pic" src="<?= base_url('assets/img/profile/').$user->avatar_url ?>" class="img-circle img-responsive">
                            <?php else :?>
                            <img id="upload-demo" alt="User Pic" src="<?= base_url('assets/img/profile/user_default.jpg')?>" class="img-circle img-responsive">
                            <?php endif ;?>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Grupo:</td>
                                        <td><?= $user->group_name ?></td>
                                    </tr>
                                    <tr>
                                        <td>Fecha de Nacimiento:</td>
                                        <td><?php if ($user->birthdate) { echo strftime('%d/%m/%y', $user->birthdate).' ('.timespan($user->birthdate, time(), 1).')'; } else { echo 'No Definido'; } ?></td>
                                    </tr>
                                    <tr>
                                        <tr>
                                            <td>Sexo:</td>
                                            <td><?php get_gender($user->gender) ?> </td>
                                        </tr>
                                        <tr>
                                            <td>Comuna, Ciudad, País:</td>
                                            <td><?= $user->location ?></td>
                                        </tr>
                                        <tr>
                                            <td>Correo Electrónico:</td>
                                            <td><a href="mailto:<?= $user->email ?>"><?= $user->email ?></a></td>
                                        </tr>
                                        <td>Teléfonos:</td>
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
                            <?php $user_permissions = get_permissions($user->permissions);?>
                            <?php foreach ($user_permissions as $key => $val):?>
                            <?= '<span class="label label-default">'.$val.' ('.$key.')</span> ';?>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                <label id="btnUpload" data-toggle="tooltip" data-original-title="Cambiar avatar" class="btn btn-primary btn-sm btn-file">
                    <i class="fa fa-photo fa-fw"></i><input id="upload" type="file" style="display: none;">
                </label>
                <button style="display: none;" id="btnSave" data-toggle="tooltip" data-original-title="Guardar" data-id="<?= $user->id ?>" class="btn btn-success btn-sm"><i class="fa fa-save fa-fw"></i></button>
                <button style="display: none;" id="btnCancel" data-toggle="tooltip" data-original-title="Cancelar" class="btn btn-danger btn-sm"><i class="fa fa-ban fa-fw"></i></button>
                <span class="pull-right">
                    <button onclick="openModal('users/edit/<?=$user->id?>?id=usersShow', 'Editar Usuario')" class="btn btn-sm btn-primary edit" data-toggle="tooltip" title="Editar" data-placement="top"><i class="fa fa-fw fa-pencil"></i></button>
                    <?php if ($this->session->userdata('id') == $user->id):?>
                        <button onclick="openModal('users/passwd_change/<?=$user->id?>?id=usersShow', 'Cambiar Contraseña')" data-original-title="Cambiar contraseña" data-toggle="tooltip" type="button" class="btn btn-sm btn-info">
                            <i class="fa fa-key fa-fw"></i>
                        </button>
                    <?php else:?>
                        <button onclick="ajaxConfirm('users/passwd_reset/<?=$user->id?>?id=usersShow', 'Enviaremos un correo electrónico al usuario con instrucciones para reestablecer su contraseña.')" data-original-title="Reestablecer contraseña" data-toggle="tooltip" type="button" class="btn btn-sm btn-info">
                            <i class="fa fa-key fa-fw"></i>
                        </button>
                    <?php endif;?>
                    <?php if (!$user->is_locked):?>
                    <button onclick="ajaxGet('users/lock/<?=$user->id?>?id=usersShow')" data-original-title="Bloquear" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="fa fa-lock fa-fw"></i></button>
                    <?php else:?>
                    <button onclick="ajaxGet('users/unlock/<?=$user->id?>?id=usersShow')" data-original-title="Desbloquear" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="fa fa-unlock fa-fw"></i></button>
                    <?php endif;?>
                    <button onclick="ajaxConfirm('users/destroy/<?=$user->id?>?id=usersShow')" data-original-title="Eliminar" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash fa-fw"></i></button>
                </span>
                </div>
            </div>
        </div>
    </div>
</div>