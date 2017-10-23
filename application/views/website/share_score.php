<div class="share_points">
    <div class="col-sm-6">
        <div class="dino">
            <?php if($score->dinosaurs == 'Amargasaurio'|| $score->dinosaurs == 'Riojasaurus'): ?>
            <img class="dinosaurio-large"  src="<?= base_url('assets/img/dinos/'.$score->dinosaurs.'.png')?>">
            <?php else:?>
                <img class="dinosaurio"  src="<?= base_url('assets/img/dinos/'.$score->dinosaurs.'.png')?>">
            <?php endif; ?>
            <img class="roca"  src="<?= base_url('assets/img/Base dinosaurio.png')?>">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="putanje">
            <img class="puntaje_obtenido" src="<?= base_url('assets/img/Puntaje obtenido.png')?>">
            <img class="panel-puntaje" src="<?= base_url('assets/img/Panel puntaje.png')?>">
            <div class="score"><?= $score->points ?></div>
        </div>
        <div class="button-share">
            <a href="javascript:window.open('https://www.facebook.com/sharer.php?u=<?= urlencode(base_url("index.php/website/share_score/".$score->id)) ?>')"><img class="facebook-button fb-share-button" 
    data-href="<?= base_url('website/share_score/'.$score->id) ?>" 
    data-layout="button_count" src="<?= base_url('assets/img/FB PanelBoton.png')?>"></a>
        </div>
    </div>
    <div class="col-sm-12 grass-div">
        <img class="inferior-grass" src="<?= base_url('assets/img/Parte inferior.png')?>">
    </div>
</div>
