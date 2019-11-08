
   $('.join_now').click(function(){
      var myurl = "<?php echo site_url('../../sign-up');?>"
      location.href = myurl; 
   });

    jQuery.validator.addMethod("check_mail", function (value, element) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var sendmail = $('#email').val();
        return emailReg.test(sendmail);
    });

   
   $(document).ready(function () {
      $("#signin_form").validate({
          rules: {
              email: {
                  required: true,
                  check_mail:true
              },
              password: {
                  required: true
              }
          },
          messages: {
               email: {
                  required: "Please enter email",
                  check_mail:"Please enter a valid email address"
               },
               password: {
                  required: 'Please enter password'
               }
          },
          errorPlacement: function (error, element) {
              var attr_name = element.attr('name');
              if (attr_name == 'type') {
                  error.appendTo('.type_err');
              } else {
                  error.insertAfter(element.parent());
              }
          }
      });
   })

       