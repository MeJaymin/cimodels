
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

        <script src="<?php echo ASSETS_URL; ?>admin/js/jquery.js"></script>
        <script src="<?php echo ASSETS_URL; ?>admin/js/custom.js"></script>
        <script src="<?php echo ASSETS_URL; ?>admin/js/jquery.validate.min.js"></script>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>admin/css/reset.css" media="screen">
        <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>admin/css/master.css" media="screen">
        <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>admin/css/responsive.css" media="screen">

    </head>
    <script type="text/javascript">
        var base_url = "<?php echo ASSETS_URL; ?>";
    </script>
    <?php 
        if(!isAdmin())
        {
            $class="homepage";   
        }
        else
        {
            $class="";
        }
    ?>
    <body class="<?php echo $class;?>">
        <div class="wrapper">

            <header>
                <div class="container">
                    <?php if(isAdmin()){?>
                    <div class="menu">
                        <a href="javascript:void(0)" class="menubtn">Navigation <em></em><em></em><em></em></a>
                        <ul>
                            <li class="active"><a href="<?php echo site_url("admin/logout"); ?>">Logout</a></li>

                        </ul>
                    </div>
                    <?php }?>
                </div>
            </header>