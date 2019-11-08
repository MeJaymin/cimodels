

$(document).ready(function () {
    /*For back button*/ 
    $(".back").click(function(){
        window.history.back();
    });

    $("#notification").validate({
        rules: {
             tax: {
                required: true,
                number : true,
            }
        },
        messages: {
             tax: {
                required: "Please enter tax",
                number: "Please enter tax in numeric format"
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
