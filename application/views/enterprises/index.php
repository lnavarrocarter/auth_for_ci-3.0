<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1><?= $title ?></h1>
            </div>
            <p class="lead">
                <?=$description?>
            </p>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table id="dataTable" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="text-center text-middle">#</th>
                        <th class="text-center text-middle">DNI/RUT</th>
                        <th class="text-center text-middle">Nombre</th>
                        <th class="text-center text-middle">Nombre Fantasía</th>
                        <th class="hidden-xs text-center text-middle">Creado</th>
                        <th class="text-center text-middle">
                            <button class="btn btn-xs btn-success" onclick="openModal('enterprises/add', 'Nuevo Empresa')" data-toggle="tooltip" title="Nuevo" data-placement="top"><i class="fa fa-fw fa-plus"></i></button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enterprises as $enterprise):?>
                    <?php if ($enterprise->is_active):?>
                    <tr>
                        <?php else:?>
                        <tr class="warning">
                            <?php endif;?>
                            <th class="text-center text-middle">
                                <?= $enterprise->id ?>
                            </th>
                            <th class="text-middle">
                                <?= $enterprise->dni.'-'.$enterprise->dv ?>
                            </th>
                            <th class="text-middle">
                                <?= $enterprise->name ?>
                            </th>
                            <th class="text-middle">
                                <?= $enterprise->fakename ?>
                            </th>
                            <th class="hidden-xs text-center text-middle">
                                <?= strftime('%d/%m/%y', $enterprise->created_at) ?>
                            </th>
                            <th class="text-center text-middle">
                                <div class="btn-group">
                                    <a href="<?= base_url('enterprises/show/').$enterprise->id ?>" class="btn btn-xs btn-default show" data-toggle="tooltip" title="Ver empresa" data-placement="top"><i class="fa fa-fw fa-group"></i></a>
                                    <button onclick="openModal('enterprises/edit/<?=$enterprise->id?>', 'Editar Empresa')" class="btn btn-xs btn-primary edit" data-toggle="tooltip" title="Editar" data-placement="top"><i class="fa fa-fw fa-pencil"></i></button>
                                    <?php if ($enterprise->is_active):?>
                                    <button onclick="ajaxConfirm('enterprises/lock/<?=$enterprise->id?>', 'Bloquear una Empresa impedirá que todos los usuarios petenecientes a ese grupo puedan iniciar sesión.')" data-original-title="Bloquear" data-toggle="tooltip" type="button" class="btn btn-xs btn-warning"><i class="fa fa-lock fa-fw"></i></button>
                                    <?php else:?>
                                    <button onclick="ajaxConfirm('enterprises/unlock/<?=$enterprise->id?>', 'Esto reestablecerá el acceso a todos los usuarios de esta empresa al sistema.')" data-original-title="Desbloquear" data-toggle="tooltip" type="button" class="btn btn-xs btn-warning"><i class="fa fa-unlock fa-fw"></i></button>
                                    <?php endif;?>
                                    <button onclick="ajaxConfirm('enterprises/destroy/<?=$enterprise->id?>', 'Al eliminar una Empresa se eliminarán todos los usuarios del mismo. Debes estar absolutamente seguro de lo que estás haciendo.')" data-original-title="Eliminar" data-toggle="tooltip" type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash fa-fw"></i></button>
                                </div>
                            </th>
                        </tr>
                        <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
