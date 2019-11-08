

jQuery.validator.addMethod("noSpace", function(value, element) { 
    return (value.trim() == value) || (value.indexOf(" ") < 0);
});

jQuery.validator.addMethod("onlynumeric", function(value, element) {
    return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
});


$(document).ready(function () {
    /*For back button*/ 
    $(".back").click(function(){
        window.history.back();
    });
    
    $("#user_form").validate({
        rules: {
             first_name: {
                required: true,
                maxlength:15,
                onlynumeric:true,
                noSpace:true,

            },
            last_name: {
                required: true,
                maxlength:15,
                onlynumeric:true,
                noSpace:true,
            },
            phone_no: {
                required: true,
                noSpace:true,
                maxlength:10

            },
            

        },
        messages: {
             first_name: {
                required: "Please enter first name",
                maxlength:"First name cannot be more than 15 characters",
                noSpace:"No space allowed in First name",
                onlynumeric:"Please enter only characters"
            },
            last_name: {
                required: "Please enter last name",
                maxlength:"Last name cannot be more than 15 characters",
                noSpace:"No space allowed in Last name",
                onlynumeric:"Please enter only characters"
            },
            phone_no: {
                required: "Please enter Phone No.",
                noSpace:"No space allowed in Phone No",
                maxlength:"Phone No cannot be more than 10 characters"

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
    var sendmail = $('#email').val();

   
    
});