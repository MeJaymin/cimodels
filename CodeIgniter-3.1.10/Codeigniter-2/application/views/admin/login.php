<div class="container">

    <div class="loginbox">
        <div class="logo"><img src="<?php echo ASSETS_URL; ?>/admin/images/logo.png"></div>
        <div class="loginform">
            <?php
            $this->load->view('admin/common/error_message');
            $attributes = array('class' => 'signin_form', 'id' => 'signin_form');
            echo form_open('admin/', $attributes);
            ?>
            <div class="inputbox"><input type="text" name="email" id="email" placeholder="E-mail Address" /></div>
            <div class="inputbox"><input type="password" name="password" placeholder="Password" /></div>                
            <div class="submitbtn-box"><input type="Submit" name="Submit" value="Login &gt;" /></div>
                <?php echo form_close(); ?>
        </div>
    </div>

</div>
<!--container end-->
<script src="<?php echo ASSETS_URL; ?>/admin/js/pages/login.js"></script>
