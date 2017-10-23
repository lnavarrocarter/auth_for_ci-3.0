    <div id="footer" class="hidden-xs">
        <div class="container-fluid footer">
            <div class="col-sm-12 text-center">
                <p>Dirección: Parque Quinta Normal, Santiago, Chile Teléfono: +56 2 2680 4603 mail: comunicaciones.mnhn@mnhn.cl</p>
            </div>
            <div class="col-xs-12 col-sm-3">
                <a target="_blank" href="http://www.mnhn.cl" ><img src="<?= base_url() ?>assets/img/logo-mnhn.png" class="img-responsive img-download"></a>
            </div>
            <div class="col-xs-12 col-sm-3">
                <a target="_blank" href="http://www.dibam.cl"><img src="<?= base_url() ?>assets/img/logo-diban2.png" class="img-responsive float-left"></a>
                
            </div>
            <div class="col-xs-12 col-sm-3">
                <a target="_blank" href="http://www.mnhn.cl" ><img src="<?= base_url() ?>assets/img/logo-mnhn.png" class="img-responsive float-right"></a>
            </div>
            <div class="col-xs-12 col-sm-3">
                <img src="<?= base_url() ?>assets/img/logo.png" class="img-responsive img-download">
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#nav-button").click(function(){
                if ($("#nav-container").hasClass('nav-dino-close')) {
                    $("#nav-container").removeClass("nav-dino-close");
                    $("#nav-container").addClass("nav-dino-open");
                }
                else{
                    $("#nav-container").removeClass("nav-dino-open");
                    $("#nav-container").addClass("nav-dino-close");
                }
            });
        });
    </script>
    
</body>
</html>