

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

    $("#add_roles").validate({
        rules: {
             name: {
                required: true,
                noSpace:true

            },
            description: {
                required: true
            },
            status: {
                required: true
            }
        },
        messages: {
             name: {
                required: "Please enter name",
                noSpace:"No space allowed in name"
            },
            description: {
                required: "Please enter description"
            },
            status: {
                required: "Please choose status"
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