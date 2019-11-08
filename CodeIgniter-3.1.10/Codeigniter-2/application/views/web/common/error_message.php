<?php 

  if(isset($success_message) && $success_message!=""){
?>
  <div class="alert alert-success">
     <?php echo $success_message; ?>
  </div>
<?php
  }
?>
<?php 
  if(isset($error_message) && $error_message!=""){
?>
  <div class="alert alert-danger">
    <?php echo $error_message; ?>
  </div>
<?php
  }
?>