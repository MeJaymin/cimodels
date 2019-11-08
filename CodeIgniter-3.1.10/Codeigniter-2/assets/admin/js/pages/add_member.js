
jQuery.validator.addMethod("noSpace", function(value, element) { 
    return (value.trim() == value) || (value.indexOf(" ") < 0);
});

jQuery.validator.addMethod("onlynumeric", function(value, element) {
    return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
});

jQuery.validator.addMethod("onlynum", function(value, element) {
    return this.optional(element) || /^[0-9]+$/.test(value);
});

jQuery.validator.addMethod("onlydate", function(value, element) {

    if (!value.match(/^0000\-00\-00$/)) return value;

});

jQuery.validator.addMethod("check_mail", function (value, element) { 
    if(value !=""){
        var emailReg =/^[-a-z0-9~!$%^&*_=+}{.\'?]+(\.[-a-z~!$%^&*_=+}{\'?]+)*@([a-z_][-a-z_]*(\.[-a-z_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
        var sendmail = $('#email_id').val(); return emailReg.test(sendmail); 
    }
    return true;
});

$(document).ready(function(){
	$("#dob").datepicker({maxDate: new Date()});
	
	$(".back").click(function(){
        window.history.back();
    });
        $("#add_user_form").validate({
        rules: {
             first_name: {
                required: true,
                maxlength:15,
                onlynumeric:true,
                noSpace:true,

            },

            gender:{
                required: true
            },

            last_name: {
                maxlength:15,
                onlynumeric:true,

                noSpace:true
            },
            nick_name:{
                required: true,
                maxlength:15,
                onlynumeric:true,
                noSpace:true,
            },



            email_id: {
                  check_mail:true
            },

            dob: {
                required: true,
                onlydate: true
            },
        },
        messages: {
             first_name: {
                required: "Please enter first name",
                maxlength:"First name cannot be more than 15 characters",
                noSpace:"No space allowed in First name",
                onlynumeric:"Please enter only alphabetic value"
            },
            last_name: {
                maxlength:"Last name cannot be more than 15 characters",
                noSpace:"No space allowed in Last name",
                onlynumeric:"Please enter only alphabetic value"
            },
            
            nick_name:{
                required: "Please enter nick name",
                maxlength:"Nick name cannot be more than 15 characters",
                noSpace:"No space allowed in Nick name",
                onlynumeric:"Please enter only alphabatic value"

            },

            gender:{
                required: "Please select gender"
            },

            dob: {
                required: "Please enter date of birth",
                onlydate: "please enter valid date"
            },
            email_id: {
                check_mail:"Please enter a valid email address"
            }
        },
        errorPlacement: function (error, element) {
            var attr_name = element.attr('name');
            if (attr_name == 'gender') {
                error.appendTo('.gender');
            } else {
                error.insertAfter(element);
            }
        }

    });

});
$(document).on('keydown', 'input[type=text]', function(e) {
    if (e.keyCode == 32) return false;
});