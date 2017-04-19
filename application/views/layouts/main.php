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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css') ?>">
    <!-- Custom CSS -->
    <link href="<?= base_url('assets/css/custom.css')?>" rel="stylesheet">
    <!-- Animations CSS -->
    <link href="<?= base_url('assets/css/animations.css')?>" rel="stylesheet">
    <!-- Bootstrap Select -->
    <link href="<?= base_url('assets/css/bootstrap-select.min.css')?>" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="<?= base_url('assets/css/sweetalert.css')?>" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?= base_url('assets/css/ie10-viewport-bug-workaround.css')?>" rel="stylesheet">
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
                <?php $this->load->view('layouts/navbar')?>
                <?php $this->load->view('auth/layouts/errors')?>
                <div class="container">
                    <div class="page-header">
                        <h1><?= $title ?></h1>
                    </div>
                    <p class="lead"><?= $description ?></p>
                    <div id="content">
                        <?php $this->load->view($content)?>
                    </div>
                </div>
                <?php $this->load->view('layouts/footer')?>
            </div>
        </div>
    </div>
    <!-- Cargar jQuery -->
    <script src="<?= base_url('assets/js/jquery-3.2.0.min.js')?>"></script>
    <!-- Boostrap JS -->
    <script src="<?= base_url('assets/js/bootstrap.min.js')?>"></script>
    <!-- Animate It -->
    <script src="<?= base_url('assets/js/css3-animate-it.js')?>"></script>
    <!-- Bootstrap DataTables -->
    <script src="<?= base_url('assets/js/data-tables.js')?>"></script>
    <script src="<?= base_url('assets/js/bs-data-tables.js')?>"></script>
    <!-- Bootstrap Select -->
    <script src="<?= base_url('assets/js/bootstrap-select.min.js')?>"></script>
    <!-- Sweet Alert -->
    <script src="<?= base_url('assets/js/sweetalert.min.js')?>"></script> 
    <!-- Custom JS -->
    <script src="<?= base_url('assets/js/custom.js')?>"></script>
    <!-- View Specific JS -->
    <?php if (isset($script)) :?>
    <script src="<?= base_url('assets/js/scripts/'.$script)?>"></script>
    <?php endif;?>
    <?php if (config_item('use_ajax')):?>
    <!-- Logout -->
    <script>
    $('#logout').click( function() {
      $.ajax({
        type: 'GET',
        dataType: 'json',
        url: baseUrl+'auth/logout',
        success: function (data) {
          if (data.redirect) {
            swal({
              title: '¡Exito!',
              text: data.msg,
              timer: 2000,
              type: 'success',
              showConfirmButton: false
              }, function () {
                window.location.href = baseUrl;
            });
          } else {
            swal('¡Error!',data.msg, data.type);
          }
        }
      });
    });  
    </script>
    <?php endif;?>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?= base_url('assets/js/ie10-viewport-bug-workaround.js')?>"></script>
</body>

</html>
