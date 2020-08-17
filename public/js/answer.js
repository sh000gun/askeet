$(document).ready(function() {
  $('form[name="answer"]').submit(function(e) {
    e.preventDefault();
    var formSerialize = $(this).serialize();

    $.ajax({
        url: '/answer/add',
        method: 'POST',
        data: formSerialize,
        beforeSend: function() {
          $('#indicator').css("background", "url(../../images/indicator.gif) no-repeat 0 0"); 
          $("#indicator").show();
        }
    }).then(function(response) {
        $("#indicator").hide();
        if (response == 'success')
        {
            location.reload();
        }
    });
  });
});
