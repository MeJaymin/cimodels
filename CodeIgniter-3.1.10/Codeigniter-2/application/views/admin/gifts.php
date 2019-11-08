<div class="container">
        <div class="main-content">
            <div class="content-topbar">
                <?php $this->load->view('admin/common/error_message'); ?>
                <h1>Gifts List</h1>
            </div>
            <div class="topbuttons">
                <a href="javascript:void(0);" class="btn truncate_gifts" id="change-status">Clear All Gifts</a>
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
                            <th>Title</th>
                            <th>Final Amount</th>
                            <th>Gift Timestamp</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($gifts)) {
                            $i = 0;
                            foreach ($gifts as $row) {
                                
                                $i++;
                                ?>
                                <tr>
                                    <td align="center" valign="middle">
                                        <div class="squaredThree">
                                            <input type="checkbox" value="<?php echo $row->id; ?>" id="<?php echo $row->id; ?>" name="check[]" class="check"/>
                                            <label for="<?php echo $row->id; ?>"></label>
                                        </div>
                                    </td>                
                                    <td align="center" valign="middle"><?php echo $row->title; ?></td>
                                    <td align="center" valign="middle"><?php echo $row->final_amount; ?></td>
                                    <td align="center" valign="middle"><?php echo $row->gift_timestamp; ?></td>
                                    <td align="center" valign="middle">
                                        <?php $editurl = base_url() . 'admin/detail-gift/' . $row->id; ?>
                                        <a href="<?php echo $editurl; ?>" class="edit-user" >View</a>
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

    $(".truncate_gifts").click(function () {

    var url = "<?php echo base_url() . 'admin/gifts-truncate'; ?>";
    if (confirm("Are you sure, you want to truncate gifts ?"))
    {
        $.ajax({
            url: url,
            type: 'POST',
            
            success: function (data) {
                location.href = "<?php echo base_url() . 'admin/gifts'; ?>"
            },
            error: function (data) {
            }
        });
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
                {'bSortable': false, 'aTargets': [0,3]}
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
