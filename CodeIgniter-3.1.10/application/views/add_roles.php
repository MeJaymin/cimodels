<?php
if($this->uri->segment(2) == "add-roles")
{
    $title="Add roles";
    $style="";
    $redirect="add-roles";   
}
if($this->uri->segment(2) == "edit-roles")
{
    $title="Edit roles";
    $style="";
    $redirect="edit-roles";
} 

$if_selected=$else_selected=$name=$status=$description=$company_id=$id="";
$color_code = "#ff0000";
//print_r($edit_company[0]['name']);
if(!empty($edit_role))
{
   $id=$edit_role[0]['id']; 
   $name=$edit_role[0]['name'];
   $description = $edit_role[0]['description'];
   $status = $edit_role[0]['status'];
   $company_id = $edit_role[0]['company_id'];
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
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Roles
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-9">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $title; ?></h3>
            </div>
            <?php $this->load->view('common/error_message'); ?>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="POST" id="add_roles" enctype="multipart/form-data" action="<?php echo $id; ?>">
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" placeholder="Enter name">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Description</label>
                  <textarea  class="form-control" name="description" id="description" placeholder="Enter Description"><?php echo $description;  ?></textarea>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Status</label>
                  <select id="status" name="status" class="form-control">
                    <option value="">Select</option>
                    <option value="1" <?php echo $if_selected ?>>Active</option>
                    <option value="0" <?php echo $else_selected ?>>Inactive</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Choose Company</label>
                  <select id="c_id" name="c_id" class="form-control">
                    <option value="">Select</option>
                    <?php
                    $selected ="";
                    foreach ($company_listing as $value) 
                    {
                      if($value['id'] == $company_id)
                      {
                        $selected = "selected";
                      }
                      else
                      {
                        $selected="";
                      }
                      ?>
                      <option value="<?php echo $value['id'];?>" <?php echo $selected;?> ><?php  echo $value['name'];?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (left) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script src="<?php echo ASSETS_URL; ?>customjs/add_roles.js"></script>