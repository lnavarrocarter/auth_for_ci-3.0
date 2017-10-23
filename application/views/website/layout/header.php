<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css">

    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap.min.js" ></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/fuentes.css">
    
    <meta property="og:url"           content="http://serve.youtouch.cl" />
    <meta property="og:type"          content="article" />
    <meta property="og:title"         content="Dinosaurios Evolucion" />
    <meta property="og:description"   content="He logrado <?= $score->points ?> puntos" />
    <meta property="og:image"         content="<?= base_url('assets/img/dinos/'.$score->dinosaurs.'.png')?>" />

</head>
<body>
    <div class="container-fluid nav-header">
        <div class="row">
            <div class="col-sm-4">
                <a class="navbar-brand" target="_blank" href="http://www.mnhn.cl" ><img src="img/logo-mnhn.png" class="img-responsive"></a>
            </div>
            <div class="col-sm-4">
                <a class="navbar-brand" href="#"><img class="img-responsive" src="<?= base_url() ?>assets/img/logo.png" alt="Logo" height="50px" width="273px"></a>
            </div>
            <div class="col-sm-4 footer-redes text-center hidden-xs">
                <h3><b>Síguenos</b>
                    <a target="_blank" href="https://www.facebook.com/MNHNcl"><img src="<?= base_url() ?>assets/img/facebook.png" class="img-redes"></a>
                    <a target="_blank" href="https://twitter.com/MNHNcl"><img src="<?= base_url() ?>assets/img/twitter.png" class="img-redes"></a>
                    <a target="_blank" href=https://www.youtube.com/user/MNHNChile""><img src="<?= base_url() ?>assets/img/youtube.png" class="img-redes"></a>
                </h3>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-inverse">
        <div id="nav-container" class="container-fluid nav-dino-close">
            <div class="navbar-header">
                <button id="nav-button" type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>                        
                </button>
                
            </div>
            
            <div class="collapse navbar-collapse" id="myNavbar">
                
                <ul class="nav navbar-nav">

                    <li>
                        <a href="<?= base_url() ?>website/">Inicio</a>
                    </li>
                    <li>
                        <a href="<?= base_url() ?>website/exposicion">Exposición</a>
                    </li>
                    <li>
                        <a href="<?= base_url() ?>website/galeria">Galeria</a>
                    </li>
                    <li>
                        <a href="<?= base_url() ?>website/descargas">Descargas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>