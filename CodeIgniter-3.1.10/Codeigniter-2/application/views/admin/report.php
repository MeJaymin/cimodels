<div class="container">
        <div class="main-content">
            <div class="content-topbar">
                <?php $this->load->view('admin/common/error_message'); ?>
                <h1>Report List</h1>
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
                            <th>From</th>
                            <th>Topic</th>
                            <th>Title</th>
                            <th>Complaint</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($report)) {
                            $i = 0;
                            foreach ($report as $row) {
                                
                                $i++;
                                ?>
                                <tr>
                                    <td align="center" valign="middle">
                                        <div class="squaredThree">
                                            <input type="checkbox" value="<?php echo $row->id; ?>" id="<?php echo $row->id; ?>" name="check[]" class="check"/>
                                            <label for="<?php echo $row->id; ?>"></label>
                                        </div>
                                    </td>                
                                    <td align="center" valign="middle"><?php echo $row->fname.' '.$row->lname; ?></td>
                                    <td align="center" valign="middle"><?php echo $row->topic; ?></td>
                                    <td align="center" valign="middle"><?php echo $row->title; ?></td>
                                    <td align="center" valign="middle"><?php echo $row->complaint; ?></td>
                                    <td align="center" valign="middle"><?php echo $row->created_at; ?></td>

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
