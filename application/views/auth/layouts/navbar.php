<div class="masthead clearfix">
    <div class="inner">
        <h3 class="masthead-brand"><?= config_item('app_name')?></h3>
        <nav>
            <ul class="nav masthead-nav">
                <li id="home" class="active"><a href="<?= base_url('/')?>">Inicio</a></li>
                <?php if (!$this->session->userdata('logged_in')):?>
                <?php if (config_item('activate_registration')):?>
                <li id="register"><a href="<?= base_url('auth/register')?>">Registro</a></li>
                <?php endif;?>
                <?php if (!config_item('hidden_login')):?>
                <li id="login"><a href="<?= base_url('auth/login')?>">Entrar</a></li>
                <?php endif;?>
                <?php else :?>
                <li><a href="<?= base_url(config_item('logged_in_controller'))?>">Panel</a></li>
                <li><a id="logout" href="<?php if (config_item('use_ajax')) { echo '#'; } else { base_url('auth/logout'); }?>">Cerrar Sesión</a></li>
                <?php endif;?>
            </ul>
        </nav>
    </div>
</div>