var frm = $('form');
frm.submit(function(ev) {
    var submit = $('button').html();
    $('button').html('<i class="fa fa-cog fa-2x fa-spin fa-fw"></i>');
    ev.preventDefault();
    $.ajax({
        type: frm.attr('method'),
        dataType: 'json',
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function(data) {
            $('button').html(submit);
            if (data.redirect) {
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
                swal('¡Error!', data.msg, data.type);
            }
        }
    });
});

$('#logout').click(function() {
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: baseUrl + 'auth/logout',
        success: function(data) {
            if (data.redirect) {
                swal({
                    title: '¡Exito!',
                    text: data.msg,
                    timer: 2000,
                    type: 'success',
                    showConfirmButton: false
                }, function() {
                    window.location.href = baseUrl;
                });
            } else {
                swal('¡Error!', data.msg, data.type);
            }
        }
    });
});
