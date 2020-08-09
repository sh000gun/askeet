$(document).ready(function showLoginForm() {
  var $container = $('.interested_block');
  $container.find('a[id^="unauthenticated_interested_link"]').on('click', function(e) {
    e.preventDefault();
    if ($('#login').is(':visible')) {
       $('#login').hide();
    }

    $('#login').show( 'slide', { direction: "down" } , 500);
  });
});

$(document).ready(function() {
  var $container = $('#login');
  $container.find('a[id="login_cancel"]').on('click', function(e) {
    e.preventDefault();
    $('#login').hide('slide', { direction: "down" } , 500);  });
});
