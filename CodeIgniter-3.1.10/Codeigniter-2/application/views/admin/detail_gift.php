
<div class="container">
    <div class="main-content">
        <div class="content-topbar"> 
                        <!-- Flash Message -->
                <?php $this->load->view('admin/common/error_message'); ?>         
        	<h1>Detail Gift</h1>
        </div>
     <div class="form-container">

        <div class="elementbox">
            <label class="form-label">From Name<span class="required">*</span></label>
            <div class="controls">
            <?php if(!empty($gifts[0]->from_name))
            {
            ?>
            <span class="form-text"><?php echo $gifts[0]->from_name; ?></span>
            <?php
            }
            else
            {?>
                <span class="form-text"> --- </span>
            <?php
            }
            ?>
            </div>
        </div>

        <div class="elementbox">
            <label class="form-label">Recipient Name<span class="required">*</span></label>
            <div class="controls">
                <span class="form-text"><?php echo $gifts[0]->recipient_name; ?></span>
            </div>
        </div>

        <div class="elementbox">
            <label class="form-label">Amount<span class="required">*</span></label>
            <div class="controls">
                <?php echo $gifts[0]->amount; ?>
            </div>
        </div>

        <div class="elementbox">
            <label class="form-label">Screenshot<span class="required">*</span></label>
            <div class="controls">
                <?php $image_name = ASSETS_URL.'user_videos/'.$gifts[0]->screenshot; ?>
                <img src="<?php echo $image_name; ?>" height="100" width="100">
            </div>
        </div>

        <div class="elementbox">
            <label class="form-label">Video<span class="required">*</span></label>
            <div class="controls">
                <?php $video_name = ASSETS_URL.'user_videos/'.$gifts[0]->video; ?>
                 <video width="320" height="240" controls>
                  <source src="<?php echo $video_name; ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video> 
            </div>
        </div>

        <div class="elementbox">
            <label class="form-label">Payment Type<span class="required">*</span></label>
            <div class="controls">
            <span class="form-text">
                <?php if($gifts[0]->payment_type == 'bank_account')
                        echo "Bank Account";
                ?>
            </span>
            </div>
        </div>

        <div class="elementbox">
            <label class="form-label">Gift Timestamp<span class="required">*</span></label>
            <div class="controls">
            <span class="form-text">
                <?php echo $gifts[0]->gift_timestamp;
                ?>
            </div>
        </div>

        <div class="elementbox">
            <label class="form-label"></label>
            <div class="controls">
                <input type="button" name="back" value="Back" class="btn back backbtn">
            </div>
        </div>

    </div>
    <!-- main content end-->   
         <?php $this->load->view('admin/common/left-menu') ?>
 
</div>
<script>
$(".back").click(function(){
        window.history.back();
    });
</script>
