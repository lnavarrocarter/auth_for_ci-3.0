// Toastr Options
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": true,
  "progressBar": true,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "3000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}

var frm = $('form');
frm.submit(function (ev) {
    ev.preventDefault();
    $.ajax({
        type: frm.attr('method'),
        dataType: 'json',
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function (data) {
            if (data.redirect) {
                toastr[data.type](data.msg);
                setTimeout(function() {
                    window.location.href = baseUrl+data.redirect;
                }, 3000);     
            } else {
                toastr[data.type](data.msg);
            }
        }
    });
});

$('#logout').click( function() {
  $.ajax({
      type: 'GET',
      dataType: 'json',
      url: baseUrl+'auth/logout',
      success: function (data) {
          if (data.redirect) {
              toastr[data.type](data.msg);
              setTimeout(function() {
                  window.location.href = data.redirect;
              }, 3000);     
          } else {
              toastr[data.type](data.msg);
          }
      }
  });
});


