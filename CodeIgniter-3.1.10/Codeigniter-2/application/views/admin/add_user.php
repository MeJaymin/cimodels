<?php
    if(isAdmin())
    {
        if($this->uri->segment(2) == "add-user")
        {
            $title="Add User Details";
            $style="";
            $redirect="admin/add-user";   
        }
        if($this->uri->segment(2) == "edit-user")
        {
            $title="Edit User Details";
            $style="";
            $redirect="admin/edit-user";
        }


    } 
   
    if($this->uri->segment(2) == "add-user")
    {
        $title="Add User Details";
        $style="";
        $redirect="admin/add-user";
    }

    if($this->uri->segment(2) == "edit-user")
    {
        $title="Edit User Details";
        $style="";
        $redirect="admin/edit-user";
    }

    $if_selected=$else_selected=$fname=$lname=$email_id=$image=$phone_number=$status=$id="";
    if(!empty($edtUser))
    {
       $id=$edtUser[0]->id; 
       $fname=$edtUser[0]->fname;
       $lname=$edtUser[0]->lname;
       $email_id=$edtUser[0]->email_id;
       $status=$edtUser[0]->status;
       $phone_number = $edtUser[0]->phone_number;
       $image = $edtUser[0]->profile_picture;
       if($status == 1)
       {
            $if_selected="selected";
       }
       else
       {
            $else_selected="selected";
       }
    }

?>
<div class="container">
    <div class="main-content">
        <div class="content-topbar">
            <div class="left"><h1><?php echo $title;?></h1></div>
        </div>
        
        <!-- Content Top bar end-->

        <div class="form-container">

            <?php
            $this->load->view('admin/common/error_message'); 
            $attributes = array('class' => 'add_usr', 'id' => 'add_user_form');
            echo form_open_multipart($redirect."/".$id, $attributes);
            ?>
            <div class="elementbox">
                <label class="form-label">First name <span class="required">*</span></label>
                <div class="controls">
                    <input type="text" name="first_name" id="first_name" value="<?php echo $fname; ?>"  class="large" />
                    <input type="hidden" id="hidden_id" value="<?php echo $id; ?>">
                </div>
            </div>
            <div class="elementbox">
                <label class="form-label">Last name </label>
                <div class="controls">
                    <input type="text" name="last_name" id="last_name" value="<?php echo $lname;?>"  class="large" />
                </div>
            </div>

           
            <div class="elementbox">
                <label class="form-label">Email <span class="required">*</span></label>
                <div class="controls">
                    <input type="text" name="email_id" id="email_id" <?php echo (!empty($email_id))?'disabled=disabled':'' ?> value="<?php echo $email_id;?>"  class="large" />
                </div>
            </div>

            <div class="elementbox">
                <label class="form-label">Phone Number <span class="required">*</span></label>
                <div class="controls">
                    <input type="text" name="phone_number" id="phone_number" value="<?php echo $phone_number;?>"  class="large" />
                </div>
            </div>
            
            <div class="elementbox">
                <label class="form-label">Profile Picture </label>
                <div class="controls">
                    <input type="file" name="image" id="image" class="large" />
                    <input type="hidden" name="exist_image_name" id="exist_image_name" value="<?php echo $image;?>">
                    <?php
                    /*if(!empty($image)){ echo $image;}*/
                    ?>
                </div>
            </div>

            <?php
            if(!empty($image))
            {
            ?>
            <div class="elementbox" id="profile_picture_display_outer">
                <label class="form-label"></label>
                <div id="profile_picture_display" class="controls profile-img-outer">
                    <?php
                    if(!empty($image))
                    {
                        $image_name = ASSETS_URL.'profile_pictures/'.$image;
                        ?>
                        <img id="close_button" class="profile-img-close" src="<?php echo ASSETS_URL.'admin/images/close.png';?>" />
                        <img id="profile_picture_view" src="<?php echo $image_name; ?>" height="100" width="100">
                    <?php
                    }
                    ?>
                </div>
            </div>
            <?php 
            }
            ?>

            <div class="elementbox">
                <label class="form-label">Status</label>
                <div class="controls">
                    <select name="status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>


            <div class="elementbox">
                <label class="form-label"></label>
                <div class="controls">
                    <input type="submit" name="submit" value="Submit" class="btn" />
                    <input type="button" name="back" value="Back" class="back backbtn">
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
        <!--form-container end-->
    </div>
    <!-- main content end-->  
    <?php $this->load->view('admin/common/left-menu') ?>
</div>

<script src="<?php echo ASSETS_URL; ?>admin/js/pages/add_user.js"></script>
<script>
$("#close_button").click(function() {
    if(confirm("Are you sure, you want to delete the image? "))
    {
        var url = "<?php echo base_url() . 'admin/delete-profile-picture'; ?>";
        
        var image_name = $("#exist_image_name").val();
        var id = $("#hidden_id").val();
        
        
        $.ajax({
            url: url,
            type: 'POST',
            data: {'image_name': image_name, 'id' : id},
            success: function (data) {
                $("#profile_picture_display_outer").hide();
            },
            error: function (data) {
            }
        }); 
    }
});
</script>