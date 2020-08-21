$(document).ready(function() {
    $('#question_tag_tag').autocomplete({hint: false, autoselect: true}, [
        {
            source: function(query, cb) {
                $.ajax({
                        url: '/tag/autocomplete/' + query
                    }).then(function(data) {
                        cb(data);
                });
            },
            displayKey: 'tag',
            templates: {
                suggestion: function(suggestion) {
                                return suggestion;
                            }
            },
            debounce: 500 // only request every 1/2 second
        }
    ]).on("autocomplete:selected", function(event, suggestion, dataset) {
        $("#question_tag_tag").val(suggestion);
  });
});
