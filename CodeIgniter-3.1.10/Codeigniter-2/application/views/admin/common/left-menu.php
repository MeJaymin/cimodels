<div class="left-content">
	<div class="leftmenu">
        <ul>
			<li class="<?php echo ($this->uri->segment(2) =='user' || $this->uri->segment(2) =='add-user' || $this->uri->segment(2) =='edit-user' )?'active':''; ?> "><a href="<?php echo base_url().'admin/user'; ?>"><i class="fa fa-user"></i> User Management</a></li>
			<li class="<?php echo ($this->uri->segment(2) =='add-member' || $this->uri->segment(2) =='detail' || $this->uri->segment(2) =='edit-gifts' || $this->uri->segment(2) =='add-gifts' || $this->uri->segment(2) =='gifts' )?'active':''; ?> "><a href="<?php echo base_url().'admin/gifts'; ?>"><i class="fa fa-gift"></i> Gifts</a></li>
			<li class="<?php echo ($this->uri->segment(2) =='notification' || $this->uri->segment(2) =='notification')?'active':''; ?> "><a href="<?php echo base_url().'admin/notification'; ?>"><i class="fa fa-bell"></i> Notification</a></li>
			<li class="<?php echo ($this->uri->segment(2) =='report-log' || $this->uri->segment(2) =='report-log')?'active':''; ?> "><a href="<?php echo base_url().'admin/report-log'; ?>"><i class="fa fa-flag"></i> Report Log</a></li>
			<li class="<?php echo ($this->uri->segment(2) =='set-tax' || $this->uri->segment(2) =='set-tax')?'active':''; ?> "><a href="<?php echo base_url().'admin/set-tax'; ?>"><i class="fa fa-money"></i> Tax</a></li>
 		</ul>
	</div>
</div>
<!-- left content end-->