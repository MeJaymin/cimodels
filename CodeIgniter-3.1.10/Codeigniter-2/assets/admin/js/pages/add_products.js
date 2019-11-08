

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

jQuery.validator.addMethod("dollarsscents", function (value, element) {
        return this.optional(element) || /^\d{0,3}(\.\d{0,3})?$/i.test(value);
    }, "You must include two decimal places");

$(document).ready(function () {
    /*For back button*/ 
    $(".back").click(function(){
        window.history.back();
    });

    $("#add_products").validate({
        rules: {
             item_name: {
                required: true
            },
            description: {
                required: true
            },
            price:{
                required: true,
                dollarsscents:true,
            },
        },
        messages: {
             item_name: {
                required: "Please enter item name"
            },
            description: {
                required: "Please enter description"
            },
            price:{
                required: "Please enter price",
                dollarsscents:"Please enter only numeric value and decimal upto 3 places only"
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
$(document).on('keydown', 'input[type=text]', function(e) {
    if (e.keyCode == 32) return false;
});