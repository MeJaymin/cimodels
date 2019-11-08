    <div class="container">
    
    <div class="loginbox change_pwd">
        <div class="logo"><img src="<?php echo ASSETS_URL; ?>/admin/images/logo.png"></div>
        <div class="loginform">
        <?php 
        if(isset($success_message)){
        ?>    
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
        <?php }
        ?>
        
        <?php
            $this->load->view('admin/common/error_message'); 
            $attributes = array('class' => 'signin_form', 'id' => 'chang_pwd');
            $url=current_full_url();
            echo form_open($url, $attributes);
        ?>
            <div class="inputbox"><input type="password" name="n_pwd" id="n_pwd" placeholder="New Password" /></div>
            <div class="inputbox"><input type="password" name="c_pwd" id="c_pwd" placeholder="Confirm Password" /></div>                
            <div class="submitbtn-box"><input type="Submit" id="submit" name="Submit" value="Change Password" /></div>

            <?php echo form_close(); ?>
        </div>
       </div>
   </div>

   <script src="<?php echo ASSETS_URL; ?>admin/js/pages/change_pwd.js"></script>
   