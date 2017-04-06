<?php if ($this->session->flashdata('error')):?>
<div class="animatedParent navbar-fixed-top">
    <div class="alert alert-danger alert-dismissable animated growIn text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?= $this->session->flashdata('error');?>
    </div>
</div>
<?php elseif ($this->session->flashdata('success')):?>
<div class="animatedParent navbar-fixed-top">
    <div class="alert alert-success alert-dismissable animated growIn text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?= $this->session->flashdata('success');?>
    </div>
</div>
<?php elseif ($this->session->flashdata('warning')):?>
<div class="animatedParent navbar-fixed-top">
    <div class="alert alert-warning alert-dismissable animated growIn text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?= $this->session->flashdata('warning');?>
    </div>
</div>
<?php elseif ($this->session->flashdata('info')):?>
<div class="animatedParent navbar-fixed-top">
    <div class="alert alert-info alert-dismissable animated growIn text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?= $this->session->flashdata('info');?>
    </div>
</div>
<?php endif;?>