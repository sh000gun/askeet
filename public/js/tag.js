$(document).ready(function() {
  $('form[name="question_tag"]').submit(function(e) {
    e.preventDefault();
    var formSerialize = $(this).serialize();
    var postUrl = '/tag/form/add';

    $.ajax({
        url: postUrl,
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
            // TODO update on client instead of reloading page
            location.reload();
        } else {
             console.log(response);
        }
    });
  });
});

