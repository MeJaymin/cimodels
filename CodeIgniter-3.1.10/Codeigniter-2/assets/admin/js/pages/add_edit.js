

jQuery.validator.addMethod("noSpace", function (value, element) {
    return (value.trim() == value) || (value.indexOf(" ") < 0);
});
//this takes, either one alphabet or num is req with reg exp
jQuery.validator.addMethod("atleastOnechar", function(value, element) {
 return this.optional(element) ||/^(?=.*[\w\d]).+/.test(value);
 });




$(document).ready(function () {

    /*For back button*/
    $(".back").click(function () {
        window.history.back();
    });

    $(".add_form, .edit_form").validate({
        rules: {
            name: {
                required: true,
                //maxlength: 15,
                noSpace: true,
                atleastOnechar:true,
            },
        },
        messages: {
            name: {
                required: "Please enter name",
                //maxlength: "Name cannot be more than 15 characters",
                noSpace: "No space allowed in Name",
                atleastOnechar:"Please enter valid name",
                
                
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

    $('#sortordr_tbl_sub,#sortordr_tbl_stats,#sortordr_tbl_perform,#sortordr_tbl_grade').DataTable({
        "language": {
            "lengthMenu": "Display _MENU_ records per page",
            "zeroRecords": "Nothing found - sorry",
            "info": "Showing page _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)",
            sSearch: "",
            sSearchPlaceholder: "Search"
        },
        "iDisplayLength": 25,
        "columnDefs": [{
                "orderable": false,
                "targets": [0]
            }],
        "order": [[1, "asc"]],
        "aoColumnDefs": [
            {'bSortable': false, 'aTargets': [0, 3]}
        ]
    });


});