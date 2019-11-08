
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Companies
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Companies Listing</h3>
              <a href="add-company" class="btn btn-primary">Add Company</a>
              <a href="javascript:void(0);" class="btn btn-warning delete-user" id="delete-user">Delete</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th align="center" valign="middle" class="all">
                      <div class="squaredThree">
                          <input type="checkbox" value="None" id="check_all"/>
                          <label for="check_all"></label>
                      </div>
                  </th>
                  <th>Name</th>
                  <th>Logo</th>
                  <th>Tagline</th>
                  <th>Created at</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                //print_r($company_listing); die;
                  if (!empty($company_listing)) 
                  {
                    foreach ($company_listing as $value) 
                    {
                      ?>
                      <tr>
                      <td align="center" valign="middle">
                        <div class="squaredThree">
                            <input type="checkbox" value="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" name="check[]" class="check"/>
                            <label for="<?php echo $value['id']; ?>"></label>
                        </div>
                      </td>
                      <td align="center" valign="middle"><?php echo $value['name']; ?></td>
                      <td align="center" valign="middle"><img src="<?php echo ASSETS_URL.'company/'.$value['logo']; ?>" height="100" width="100"></td>
                      <td align="center" valign="middle"><?php echo $value['tagline']; ?></td>
                      <td align="center" valign="middle"><?php echo date('Y-M-d H:m:s',strtotime($value['created_at'])) ; ?></td>
                      <td align="center" valign="middle" colspan="2"><a href="edit-company/<?php echo $value['id']; ?>">Edit</a> | 
                      <a href="javascript:void(0)" class="delete-user" data-userid="<?php echo $value['id']; ?>">Delete</a></td>
                      </tr>
                      <?php
                    }
                  }
                ?>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- DataTables -->
<script src="../assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example1').DataTable();
  })

  $(document).on('click', "#check_all", function () {

        if ($("#check_all").prop('checked') == true) {
            $('.check').prop('checked', true);
        } else
        {
            $('.check').prop('checked', false);
        }
    });

    $(".check").click(function () {

        if ($('.check').not(':checked').length > 0) {
            $("#check_all").prop('checked', false)
        } else {
            $("#check_all").prop('checked', true)
        }
    });

    $(".delete-user").click(function () {

        var arr = $(this).data('userid');
        var url = "<?php echo base_url() . 'admin/delete-company'; ?>";
        if (arr)
        {
            if (confirm("Are you sure, you want to delete the company?"))
            {
                window.location = url + "/" + arr;
            }
        }
        else if ($('.check').is(':checked')) {
            var id = $('.check:checked').val();
            var arr = $('.check:checked').map(function () {
                return this.value;
            }).get();

            if (confirm("Are you sure, you want to delete the company?"))
            {
                window.location = url + "/" + arr;
            }
        }


        else {
            alert("Please select item to delete");
        }
    });
</script>