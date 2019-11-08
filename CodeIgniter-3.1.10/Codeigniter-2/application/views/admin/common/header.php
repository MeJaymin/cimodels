
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Giftcast</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

        <script src="<?php echo ASSETS_URL; ?>admin/js/jquery.js"></script>
        <script src="<?php echo ASSETS_URL; ?>admin/js/custom.js"></script>
        <script src="<?php echo ASSETS_URL; ?>admin/js/jquery.validate.min.js"></script>
        <script src="<?php echo ASSETS_URL; ?>admin/js/additional-methods.min.js"></script>
        
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>admin/css/font-awesome.min.css" media="screen">
        
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>admin/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>admin/js/dataTables.responsive.min.js"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>admin/js/pages/common.js"></script>
        

        <link rel='stylesheet' href='<?php echo ASSETS_URL; ?>admin/css/jquery.dataTables.min.css' media='screen'>
        <link rel='stylesheet' href='http://cdn.datatables.net/responsive/1.0.0/css/dataTables.responsive.css' media='screen'>
        <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>admin/css/reset.css" media="screen">
        <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>admin/css/master.css" media="screen">
        <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>admin/css/responsive.css" media="screen">

    </head>
    <script type="text/javascript">
        var base_url = "<?php echo ASSETS_URL; ?>";
    </script>
    <?php
    if (!isAdmin()) {
        $class = "homepage";
    } else {
        $class = "";
    }
    ?>
    <body class="<?php echo $class; ?>">
        <div class="wrapper">

            <header>
                <div class="">
                    <?php if (isAdmin()) { ?>
                        <div class="logo"><img src="<?php echo ASSETS_URL; ?>/admin/images/logo.png"></div>
                        <div class="menu">
                            <a href="javascript:void(0)" class="menubtn"><em></em><em></em><em></em></a>
                            <div class="topmenu">
                                <ul>
                                    <li>
                                        <a href="#"><i class="fa fa-user"></i> Welcome <?php echo  $this->session->userdata('admin_email');?><i class="fa fa-caret-down"></i></a>
                                        <ul>
                                            <li><a href="<?php echo site_url("admin/logout"); ?>"><i class="fa fa-sign-out"></i> Logout</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </header>