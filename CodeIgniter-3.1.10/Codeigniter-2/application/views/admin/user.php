<div class="container">
        <div class="main-content">
            <div class="content-topbar">
                <?php $this->load->view('admin/common/error_message'); ?>
                <h1>User List</h1>
            </div>
            <div class="topbuttons">
                <a href="<?php echo base_url() . 'admin/add-user/'; ?>" class="btn" id="add_user">Add User</a>
                <select name="status" id="status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                <a href="javascript:void(0);" class="btn change_status" id="change-status">Change Status</a>
                <a href="javascript:void(0);" class="btn delete-user" id="delete-user">Delete</a>

            </div>


            <!-- Content Top bar end-->        
            <!-- Content Top bar end-->

            <div class="res-table">
                <table id="sortordr_tbl" class="display responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th align="center" valign="middle" class="all">
                                <div class="squaredThree">
                                    <input type="checkbox" value="None" id="check_all"/>
                                    <label for="check_all"></label>
                                </div>
                            </th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone number</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($users)) {
                            $i = 0;
                            foreach ($users as $row) {
                                
                                $i++;
                                ?>
                                <tr>
                                    <td align="center" valign="middle">
                                        <div class="squaredThree">
                                            <input type="checkbox" value="<?php echo $row->id; ?>" id="<?php echo $row->id; ?>" name="check[]" class="check"/>
                                            <label for="<?php echo $row->id; ?>"></label>
                                        </div>
                                    </td>                
                                    <td align="center" valign="middle"><?php echo !empty($row->fname)?$row->fname. ' ' . $row->lname:"-"; ?></td>
                                    <td align="center" valign="middle"><?php echo !empty($row->email_id)?$row->email_id:"-"; ?></td>
                                    <td align="center" valign="middle"><?php echo !empty($row->phone_number)?$row->phone_number:"-"; ?></td>
                                        <td align="center" valign="middle">
                                        <?php
                                            if ($row->status == 1) {
                                                echo "Active";
                                            } else {
                                                echo "Inactive";
                                            }
                                        ?></td>
                                    <td align="center" valign="middle">
                                        <?php $editurl = base_url() . 'admin/edit-user/' . $row->id; ?>

                                        <a href="<?php echo $editurl; ?>" class="edit-user" >Edit</a> | 
                                        <a href="javascript:void(0)" class="delete-user" data-userid="<?php echo $row->id; ?>">Delete</a>
                                    </td>

                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- main content end-->    
        <?php $this->load->view('admin/common/left-menu') ?>


    </div>
    
<script type="text/javascript">
    $(".change_status").click(function () {
        /* var arr = $(this).data('rowid');
         var status = $(this).data('status');*/
        var url = "<?php echo base_url() . 'admin/change-status'; ?>";

        if ($('.check').is(':checked')) {
            var id = $('.check:checked').val();
            var arr = $('.check:checked').map(function () {
                return this.value;
            }).get();

            var status = $('#status').val();

            if (confirm("Are you sure, you want to change the status??"))
            {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {'id': arr, 'status': status},
                    success: function (data) {
                        location.href = "<?php echo base_url() . 'admin/user'; ?>"
                    },
                    error: function (data) {
                    }
                });
            }
        }
        else {
            alert("Please select item to change status");
        }

    });

    $(".delete-user").click(function () {

        var arr = $(this).data('userid');
        var url = "<?php echo base_url() . 'admin/delete'; ?>";
        if (arr)
        {
            if (confirm("Are you sure, you want to delete the user??"))
            {
                window.location = url + "/" + arr;
            }
        }
        else if ($('.check').is(':checked')) {
            var id = $('.check:checked').val();
            var arr = $('.check:checked').map(function () {
                return this.value;
            }).get();

            if (confirm("Are you sure, you want to delete the user??"))
            {
                window.location = url + "/" + arr;
            }
        }


        else {
            alert("Please select item to delete");
        }
    });

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

        $('#sortordr_tbl').DataTable({
            "responsive": true,
            "iDisplayLength": 25,
            "bPaginate": true,
            "columnDefs": [{
                    "orderable": false,
                    "targets": [0]
                }],
            "order": [[1, "asc"]],
            "aoColumnDefs": [
                {'bSortable': false, 'aTargets': [0,4]}
            ],
            "aaSorting": [[1, 2, 3, "asc"]],
            "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
            oLanguage: {
                sSearch: "",
                sSearchPlaceholder: "Search"
            }
        });

        setTimeout(function() { $("#success").hide(); }, 3000);
        setTimeout(function() { $("#error").hide(); }, 3000);

        

</script>
