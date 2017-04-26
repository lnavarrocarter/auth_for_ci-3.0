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
    <title>
        <?= config_item('app_name')?> |
            <?= $title?>
    </title>
    <!-- Bootstrap core CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css') ?>">
    <!-- Custom CSS -->
    <link href="<?= base_url('assets/css/custom.css')?>" rel="stylesheet">
    <!-- Animations CSS -->
    <link href="<?= base_url('assets/css/animations.css')?>" rel="stylesheet">
    <!-- Croppie -->
    <link href="<?= base_url('assets/css/croppie.css')?>" rel="stylesheet">
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
        <?php $this->load->view('layouts/navbar')?>
        <?php $this->load->view('auth/layouts/errors')?>
        <div id="content">
        <?php $this->load->view($content)?>
        </div>
        <?php $this->load->view('layouts/modal')?>
        <?php $this->load->view('layouts/footer')?>
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
    <!-- Croppie -->
    <script src="<?= base_url('assets/js/croppie.js')?>"></script>
    <!-- Masked Input -->
    <script src="<?= base_url('assets/js/masked-input.min.js')?>"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?= base_url('assets/js/ie10-viewport-bug-workaround.js')?>"></script>
    <!-- Custom JS -->
    <script src="<?= base_url('assets/js/app.js')?>"></script>
</body>

</html>
