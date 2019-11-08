<?php
if($this->uri->segment(2) == "add-company")
{
    $title="Add Company";
    $style="";
    $redirect="add-company";   
}
if($this->uri->segment(2) == "edit-company")
{
    $title="Edit Company";
    $style="";
    $redirect="edit-company";
} 

$if_selected=$else_selected=$username=$password=$company_name=$tagline=$description=$logo=$id="";
$color_code = "#ff0000";
//print_r($edit_company[0]['name']);
if(!empty($edit_company))
{
   $id=$edit_company[0]['id']; 
   $username=$edit_company[0]['username'];
   $password=$edit_company[0]['password'];
   $company_name=$edit_company[0]['name'];
   $tagline=$edit_company[0]['tagline'];
   $description = $edit_company[0]['description'];
   $color_code = $edit_company[0]['color_code'];
   $logo = $edit_company[0]['logo'];
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Company
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
            <form role="form" method="POST" id="add_company" enctype="multipart/form-data" action="<?php echo $id; ?>">
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Username</label>
                  <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" placeholder="Enter username">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php echo $password; ?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Company Name</label>
                  <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo $company_name; ?>" placeholder="Company name">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Tagline</label>
                  <input type="text" class="form-control" id="tagline" value="<?php echo $tagline; ?>" name="tagline" placeholder="Tagline..">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Description</label>
                  <textarea  class="form-control" name="description" id="description"><?php echo $description;  ?></textarea>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Color Code</label>
                  <input type="color" name="color_code" id="color_code" value="<?php  echo $color_code; ?>" class="form-control">
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">Logo</label>
                  <input type="file" id="logo" name="logo">
                  <?php
                  if(!empty($logo))
                  {
                    ?>
                    <div class="img-logo" style="margin-top: 20px;">
                      <img src="<?php echo ASSETS_URL.'company/'.$logo; ?>" height="100" width="100">
                    </div>
                    <?php
                  }
                  ?>
                  <p class="help-block">Logo minimum size should be defined here.</p>
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
  <script src="<?php echo ASSETS_URL; ?>customjs/add_company.js"></script>