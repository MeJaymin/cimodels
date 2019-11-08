

jQuery.validator.addMethod("noSpace", function (value, element) {
    return (value.trim() == value) || (value.indexOf(" ") < 0);
});

/*jQuery.validator.addMethod("onlynumeric", function(value, element) {
 return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
 });*/


//this takes, either one alphabet or num is req with reg exp
jQuery.validator.addMethod("atleastOnechar", function(value, element) {
 return this.optional(element) ||/^(?=.*[\w\d]).+/.test(value);
 });



$(document).ready(function () {

    /*For back button*/
    $(".back").click(function () {
        window.history.back();
    });

    $(".cat_add_form, .cat_edit_form").validate({
        rules: {
            name: {
                required: true,
                //maxlength: 15,
                 atleastOnechar:true,
                noSpace: true,
            },
            age_range: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter name",
                //maxlength: "Name cannot be more than 15 characters",
                noSpace: "No space allowed in Name",
                atleastOnechar:"Please enter only characters"
            },
             age_range: {
                required: "Plaese select age range",
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

    


});