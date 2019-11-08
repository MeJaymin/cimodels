<?php
    if(isAdmin())
    {
        if($this->uri->segment(2) == "set-tax")
        {
            $title="Set Tax";
            $style="";
            $redirect="admin/set-tax";   
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
            $attributes = array('class' => 'set_tax', 'id' => 'set_tax');
            echo form_open_multipart($redirect, $attributes);
            ?>
            <div class="elementbox">
                <label class="form-label">Tax Amount <span class="required">*</span></label>
                <div class="controls">
                    
                    <input type="text" name="tax" id="tax" value="<?php echo !empty($tax_data[0]->tax)?$tax_data[0]->tax:""; ?>" class="large" />
                    <input type="hidden" name="id" id="id" value="<?php echo !empty($tax_data[0]->id)?$tax_data[0]->id:""; ?>" class="large" />
                </div>
            </div>
            
            <div class="elementbox">
                <label class="form-label"></label>
                <div class="controls">
                    <input type="submit" name="submit" value="Send" class="btn" />
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
        <!--form-container end-->
    </div>
    <!-- main content end-->  
    <?php $this->load->view('admin/common/left-menu') ?>
</div>

<script src="<?php echo ASSETS_URL; ?>admin/js/pages/set_tax.js"></script>