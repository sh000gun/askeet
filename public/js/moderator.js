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
     mederatorActionClick('a[id^="report_to_moderator"]', '/user/report_question', 'question', false);
});

$(document).ready(function() {
     mederatorActionClick('a[id^="report_answer_to_moderator"]', '/user/report_answer', 'answer', false);
});

$(document).ready(function() {
     mederatorActionClick('a[id^="reset_question_report"]', '/moderator/resetQuestionReports', 'question', false);
});

$(document).ready(function() {
    mederatorActionClick('a[id^="delete_question"]', '/moderator/deleteQuestion', 'question', true);
});

$(document).ready(function() {
     mederatorActionClick('a[id^="reset_answer_report"]', '/moderator/resetAnswerReports', 'answer', false);
});

$(document).ready(function() {
    mederatorActionClick('a[id^="delete_answer"]', '/moderator/deleteAnswer', 'answer', true);
});
