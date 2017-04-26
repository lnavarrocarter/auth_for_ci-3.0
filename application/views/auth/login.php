<div class="inner cover">
    <h1 class="cover-heading">Inicio de Sesión</h1>
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-8 col-md-offset-2">
            <?php if ($this->session->flashdata('message')):?>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>¡Atención!</strong><br>
                <?= $this->session->flashdata('message')?>
            </div>
            <?php endif?>
            <hr>
            <?php $this->load->view('auth/forms/login');?>
        </div>
    </div>
</div>
