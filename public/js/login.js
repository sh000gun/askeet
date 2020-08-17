function showLoginForm(container, element)
{
    var $container = $(container);
    $container.find(element).on('click', function(e) {
    e.preventDefault();
    if ($('#login').is(':visible')) {
       $('#login').hide();
    }

    $('#login').show( 'slide', { direction: "down" } , 500);
  });
};

$(document).ready(function showLoginFromInterestedLink() {
    showLoginForm('.interested_block', 'a[id^="unauthenticated_interested_link"]');
});

$(document).ready(function showLoginFromAnswer() {
    showLoginForm('.answer', 'a[id^="answer_"]');
});

$(document).ready(function() {
  var $container = $('#login');
  $container.find('a[id="login_cancel"]').on('click', function(e) {
    e.preventDefault();
    $('#login').hide('slide', { direction: "down" } , 500);  });
});
