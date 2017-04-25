<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title><?= config_item('app_name')?> | <?= $title?></title>
    <!-- Bootstrap core CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <!-- Animations CSS -->
    <link href="<?= base_url('assets/css/animations.css')?>" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="<?= base_url('assets/css/sweetalert.css')?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css') ?>">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?= base_url('assets/css/ie10-viewport-bug-workaround.css')?>" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?= base_url('assets/css/cover.css')?>" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        baseUrl = '<?= base_url();?>';
    </script>
</head>

<body>
    <div class="site-wrapper">
        <div class="site-wrapper-inner">
            <div class="cover-container">
                <?php $this->load->view('auth/layouts/navbar')?>
                <?php $this->load->view('auth/layouts/errors')?>
                <?php $this->load->view($content)?>
                <?php $this->load->view('auth/layouts/footer')?>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/js/jquery-3.2.0.min.js')?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js')?>"></script>
    <script src="<?= base_url('assets/js/css3-animate-it.js')?>"></script>
    <!-- Sweet Alert -->
    <script src="<?= base_url('assets/js/sweetalert.min.js')?>"></script> 
    <?php if (config_item('use_ajax')):?>
    <!-- App JS Functions -->
    <script src="<?= base_url('assets/js/app.js')?>"></script>
    <?php endif;?>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?= base_url('assets/js/ie10-viewport-bug-workaround.js')?>"></script>
    <script type="text/javascript">
    // Hack para mostrar la vista activa con estilo.
    $(document).ready(function () {
        if(window.location.href.indexOf("login") > -1) {
           $("#home").removeClass('active');
           $("#login").addClass('active');
        } else if (window.location.href.indexOf("register") > -1) {
            $("#home").removeClass('active');
            $("#register").addClass('active');
        } else if (window.location.href.indexOf("password_reset") > -1) {
            $("#home").removeClass('active');
        }
    });
</script>
</body>

</html>
