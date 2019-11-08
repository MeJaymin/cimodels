

jQuery.validator.addMethod("noSpace", function(value, element) { 
    return (value.trim() == value) || (value.indexOf(" ") < 0);
});

jQuery.validator.addMethod("onlynumeric", function(value, element) {
    return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
});

jQuery.validator.addMethod("onlynum", function(value, element) {
    return this.optional(element) || /^[0-9]+$/.test(value);
});


jQuery.validator.addMethod("check_mail", function (value, element) { 
    var emailReg =/^[-a-z0-9~!$%^&*_=+}{.\'?]+(\.[-a-z~!$%^&*_=+}{\'?]+)*@([a-z_][-a-z_]*(\.[-a-z_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
    var sendmail = $('#email_id').val(); return emailReg.test(sendmail); 
});

$(document).ready(function () {
    /*For back button*/ 
    $(".back").click(function(){
        window.history.back();
    });

    $("#add_user_form").validate({
        rules: {
             first_name: {
                required: true,
                //maxlength:15,
                onlynumeric:true,
                noSpace:true,

            },
            email_id: {
                  required: true,
                  check_mail:true
            },
            phone_number: {
                required: true,
                onlynum: true
            },
        },
        messages: {
             first_name: {
                required: "Please enter first name",
                //maxlength:"First name cannot be more than 15 characters",
                noSpace:"No space allowed in First name",
                onlynumeric:"Please enter only alphabatic value"
            },
            email_id: {
                required: "Please enter email",
                check_mail:"Please enter a valid email address"
            },
            phone_number: {
                required: "Please enter phone number",
                onlynum:"Only Numbers are allowed"
            }
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
$(document).on('keydown', 'input[type=text]', function(e) {
    if (e.keyCode == 32) return false;
});