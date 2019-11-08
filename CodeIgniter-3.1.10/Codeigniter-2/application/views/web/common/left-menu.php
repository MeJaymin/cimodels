<div class="left-content">
	<div class="leftmenu">
        <ul>
<!--         	<?php   $admin_type = $this->session->userdata['role']; ?>
 -->			<li class="<?php echo ($this->uri->segment(2) =='home' || $this->uri->segment(2) =='edit' )?'active':''; ?> "><a href="<?php echo base_url().'admin/home'; ?>">User Management</a></li>		
		</ul>
	</div>
</div>
<!-- left content end-->