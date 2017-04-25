/*
|------------------------------------------------
| Selectors
|------------------------------------------------
| Selectors that initialize something in the DOM
|
*/

$(document).ready( function () {
    // Load BS tooltips
    $("body").tooltip({
        selector: '[data-toggle="tooltip"]'
    });
    // Load DataTables
    $('body #dataTable').DataTable();
});

/*
|------------------------------------------------
| App Functions
|------------------------------------------------
| Contains predefined functions for Ajax Calls and
| other JS actions.
|
*/

// Just need one modal. The html will bring the title and all that.
function openModal(url, title) {
    $.ajax({
        url: baseUrl + url,
        method: 'get',
        dataType: 'json',
        success: function(data) {
            if (data.html) {
                $('#baseModal').modal();
                $('#modalContent').html(data.html);
                $('#modalTitle').html(title);
                // Renders the select picker if has any.
                $('.selectpicker').selectpicker('render');
            } else {
                swal("¡Error!", data.msg, data.type);
            }
        }
    })
};

function openOverlay(url) {
    $.ajax({
        url: baseUrl + url,
        method: 'get',
        dataType: 'json',
        success: function(data) {
            if (data.html) {
                $('#baseOverlay').modal();
                $('#overlayContent').html(data.html);
                // Renders the select picker if has any.
                $('.selectpicker').selectpicker('render');
            } else {
                swal("¡Error!", data.msg, data.type);
            }
        }
    })
};

function ajaxGet(url) {
    $.ajax({
        url: baseUrl + url,
        method: 'get',
        dataType: 'json',
        success: function (data) {
            if (data.html) {
                $('#content').html(data.html);
                $('#dataTable').DataTable();
                $('#baseModal').modal('hide');
                swal('¡Éxito!', data.msg, data.type);
            } else if (data.redirect) {
                swal({
                    title: '¡Exito!',
                    text: data.msg,
                    timer: 2000,
                    type: data.type,
                    showConfirmButton: false
                }, function() {
                    window.location.href = baseUrl + data.redirect;
                });
            } else {
                swal("¡Error!", data.msg, data.type);
            }
        }
    })
};

function ajaxConfirm(url, msg = 'Esta acción no puede deshacerse.') {
    // Checks if confirm is enabled
    swal({
        title: "¿Estás seguro?",
        text: msg,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, estoy seguro.",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
    }, 
    function() {
        ajaxGet(url);
    });
};

$('body').on('submit', 'form', function (ev) {
    var buttonHTML = $('#submit').html();
    var content = $(this).data('target');
    $('#submit').html('<i class="fa fa-cog fa-2x fa-spin fa-fw"></i>');
    ev.preventDefault();
    $.ajax({
        type: $(this).attr('method'),
        dataType: 'json',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (data) {
            $('#submit').html(buttonHTML);
            if (data.html) {
                $('#content').html(data.html);
                $('#dataTable').DataTable();
                $('#submit').html(buttonHTML);
                $('#baseModal').modal('hide');
                swal('¡Éxito!', data.msg, data.type);
            } else if (data.redirect) {
                swal({
                    title: '¡Exito!',
                    text: data.msg,
                    timer: 2000,
                    type: data.type,
                    showConfirmButton: false
                }, function() {
                    window.location.href = data.redirect;
                });
            } else {
                swal("¡Error!", data.msg, data.type);
            }
        }
    });
});

/*
|--------------------------------------------------------------------------
| Croppie
|--------------------------------------------------------------------------
*/

var start = $('.upload-demo').html();

$('body').on('change', '#upload', function () { 
    var reader = new FileReader();
    var measure = $('.upload-demo').width()
    $('#btnUpload').hide();
    $('#btnSave').show();
    $('#btnCancel').show();
    $uploadCrop = $('#upload-demo').croppie({
        enableExif: true,
        viewport: {
            width: measure,
            height: measure,
            type: 'circle'
        },
        boundary: {
            width: measure,
            height: measure
        }
    });
    reader.onload = function (e) {
        $uploadCrop.croppie('bind', {
            url: e.target.result
        }).then(function(){
            console.log('jQuery bind complete');
        });
    }
    reader.readAsDataURL(this.files[0]);
});

$('body').on('click', '#btnCancel', function () {
    $(".upload-demo").html(start);
    $('#btnUpload').show();
    $('#btnSave').hide();
    $('#btnCancel').hide();
});

$('#btnSave').on('click', function (ev) {
    var id = $(this).data('id');
    $uploadCrop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
    }).then(function (resp) {
        $.ajax({
            url: baseUrl+'users/change_profile_img/'+id,
            type: "POST",
            data: {"image":resp},
            success: function (data) {
                html = '<img id="upload-demo" src="' + resp + '" alt="User Pic" class="img-circle img-responsive" />';
                $(".upload-demo").html(html);
                $('#btnUpload').show();
                $('#btnSave').hide();
                $('#btnCancel').hide();
            }
        });
    });
});