function mederatorActionClick(linkIdToClick, targetUrl, dataLink , isDeleteConfirmation)
{
    $(linkIdToClick).on('click', function(e) {
    if (isDeleteConfirmation)
    {
        event.stopPropagation();

        if(!confirm("Do you want to delete?"))
        {
            return false; 
        }
    }

    e.preventDefault();
    var $link = $(e.currentTarget);

    $.ajax({
        url: targetUrl + '/' +  $link.data(dataLink),
        method: 'POST',
        beforeSend: function() {
          $('#indicator').css("background", "url(../images/indicator.gif) no-repeat 0 0"); 
          $("#indicator").show();
        }
    }).then(function(response) {
       $("#indicator").hide();
         if (response == 'success')
        {
            // TODO update on client instead of reloading page
            location.reload();
        }
    });
  });
}


$(document).ready(function() {
     mederatorActionClick('a[id^="delete_user"]', '/administrator/delete', 'user', true);
});

$(document).ready(function() {
     mederatorActionClick('a[id^="refuse_moderator"]', '/administrator/refuseModerator', 'user', true);
});

$(document).ready(function() {
     mederatorActionClick('a[id^="grant_moderator"]', '/administrator/grantModerator', 'user', true);
});

$(document).ready(function() {
     mederatorActionClick('a[id^="grant_administrator"]', '/administrator/grantAdministrator', 'user', true);
});
