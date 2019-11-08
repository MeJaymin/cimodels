
$(document).ready(function () {
    /*For back button*/ 
    $(".back").click(function(){
        window.history.back();
    });

    $("#notification").validate({
        rules: {
             message: {
                required: true
            }
        },
        messages: {
             message: {
                required: "Please enter message"
            },
        },
        errorPlacement: function (error, element) {
            var attr_name = element.attr('name');
            if (attr_name == 'type') {
                error.appendTo('.type_err');
            } else {
                error.insertAfter(element);
            }
        }

    });
    var typingTimerEmail;                //timer identifier
    var doneTypingIntervalEmail = 1000;  //time in ms, 5 second for example
    //var sendmail = $('#email').val();

});
