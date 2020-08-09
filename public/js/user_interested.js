$(document).ready(function() {
  var $container = $('.interested_block');
  $container.find('a[id^="interested_link"]').on('click', function(e) {
    e.preventDefault();
    var $link = $(e.currentTarget);

    $.ajax({
        url: '/user/interested/'+$link.data('question'),
        method: 'POST',
        beforeSend: function() {
          $('#indicator').css("background", "url(../images/indicator.gif) no-repeat 0 0"); 
          $("#indicator").show();
        }
    }).then(function(response) {
        $("#indicator").hide();
        $("#mark_"+$link.data("question")).fadeTo(100, 0.1).fadeTo(200, 1.0);
        $('#interested_link'+$link.data('question')).replaceWith("interested!");
        const result = JSON.parse(response);
        $('#mark_'+$link.data('question')).text(result.interestedUsers);

    });
  });
});
