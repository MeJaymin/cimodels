

jQuery.validator.addMethod("noSpace", function (value, element) {
    return (value.trim() == value) || (value.indexOf(" ") < 0);
});

/*jQuery.validator.addMethod("onlynumeric", function(value, element) {
 return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
 });*/

jQuery.validator.addMethod("atleastOnechar", function(value, element) {
 return this.optional(element) ||/^(?=.*[\w\d]).+/.test(value);
 });


$(document).ready(function () { 

    /*For back button*/
    $(".back").click(function () {
        window.history.back();
    });

    $(".size_add_form, .size_edit_form").validate({
        rules: {
            name: {
                required: true,
               // maxlength: 15,
                atleastOnechar:true,
                noSpace: true,
            },
            gender: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter name",
               // maxlength: "Name cannot be more than 15 characters",
                atleastOnechar: "Please enter valid name",
                noSpace: "No space allowed in Name",
                
            },
            gender: {
                required: "Please select gender",
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

    /*$('#sortordr_tbl_sub,#sortordr_tbl_stats,#sortordr_tbl_perform,#sortordr_tbl_grade').DataTable({
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
    });*/


});