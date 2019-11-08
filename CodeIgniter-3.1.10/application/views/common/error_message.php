<?php
$success_message = $this->session->flashdata('success_message');
$error_message = $this->session->flashdata('error_message');
if (isset($success_message) && $success_message != "") {
    ?>
    <div class="alert alert-success" id="success">
        <?php echo $success_message; ?>
    </div>
    <?php
}
?>
<?php
if (isset($error_message) && $error_message != "") {
    ?>
    <div class="alert alert-danger" id="error">
        <?php echo $error_message; ?>
    </div>
    <?php
}
?>
<script type="text/javascript">
    setTimeout(function(){ $('#success,#error').hide(); }, 3000);

</script>