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
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="POST" enctype="multipart/form-data" action="add-company">
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Username</label>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Company Name</label>
                  <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company name">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Tagline</label>
                  <input type="text" class="form-control" id="tagline" name="tagline" placeholder="Tagline..">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Description</label>
                  <textarea  class="form-control" name="description" id="description"></textarea>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Color Code</label>
                  <input type="color" name="color_code" value="#ff0000" class="form-control">
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">Logo</label>
                  <input type="file" id="logo" name="logo">
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