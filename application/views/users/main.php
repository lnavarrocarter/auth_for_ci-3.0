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
        <div id="index" class="col-md-12">
            <?php $this->load->view('users/index')?>
        </div>
    </div>
</div>
