
		<div class="container">
			<div class="main-content">
		        <div class="content-topbar">
		        	<div class="left"><h1>Edit User Details</h1></div>
		        </div>
		        <p id="email_exist_msg" name="email_exist_msg"></p>
		        <!-- Content Top bar end-->

		        <div class="form-container">

		        	<?php
		        		$this->load->view('admin/common/error_message');
			            $attributes = array('class' => 'edit_user', 'id' => 'add_user_form');
			            echo form_open_multipart('admin/edit/'.$id, $attributes); 
			        ?>
		        		<div class="elementbox">
		        			<label class="form-label">First name <span class="required">*</span></label>
		        			<div class="controls">
		        				<input type="text" name="first_name" id="first_name" value="<?php echo isset($edtUser[0]->first_name)?$edtUser[0]->first_name:""; ?>"  class="large" />
		        			</div>
		        		</div>
		        		<div class="elementbox">
		        			<label class="form-label">Last name </label>
		        			<div class="controls">
		        				<input type="text" name="last_name" id="last_name" value="<?php echo isset($edtUser[0]->last_name)?$edtUser[0]->last_name:""; ?>"  class="large" />
		        			</div>
		        		</div>

		        		<div class="elementbox">
		        			<label class="form-label">Nick name <span class="required">*</span></label>
		        			<div class="controls">
		        				<input type="text" name="nick_name" id="nick_name" value="<?php echo isset($edtUser[0]->nick_name)?$edtUser[0]->nick_name:""; ?>"  class="large" />
		        			</div>
		        		</div>

		        		<div class="elementbox">
		        			<label class="form-label">Email</label>
		        			<div class="controls">
		        				<input type="text" name="email_id" id="email_id" value="<?php echo isset($edtUser[0]->email_id)?$edtUser[0]->email_id:""; ?>"  class="large" readonly/>
		        			</div>
		        		</div>

		        		<div class="elementbox">
		        			<label class="form-label">Phone No <span class="required">*</span></label>
		        			<div class="controls">
		        				<input type="text" name="phone_no" id="phone_no" value="<?php echo isset($edtUser[0]->phone_no)?$edtUser[0]->phone_no:""; ?>"  class="large" />
		        			</div>
		        		</div>
		        		<div class="elementbox">
		        			<label class="form-label">Status</label>
		        			<div class="controls">
		        				<select name="status">
		        					<?php
		        						$a_sele = $in_a_sele ="";
		        						if(isset($edtUser[0]->is_active) && $edtUser[0]->is_active == 1){
		        							$a_sele = "selected";
		        						}else{
		        							$in_a_sele = "selected";
		        						}
		        						?>
		        					<option value="1" <?php echo $a_sele ?>>Active</option>
		        					<option value="0" <?php echo $in_a_sele ?>>Inactive</option>
		        				</select>
		        			</div>
		        		</div>

		        		<div class="elementbox">
		        			<label class="form-label">Premium</label>
		        			<div class="controls">
		        				<select name="premium">
		        					<?php
		        						$a_sele = $in_a_sele ="";
		        						if(isset($edtUser[0]->is_premium) && $edtUser[0]->is_premium == 1){
		        							$a_sele = "selected";
		        						}else{
		        							$in_a_sele = "selected";
		        						}
		        						?>
		        					<option value="1" <?php echo $a_sele ?>>Yes</option>
		        					<option value="0" <?php echo $in_a_sele ?>>No</option>
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