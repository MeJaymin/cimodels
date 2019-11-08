<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>404 Page Not Found</title>
<link rel="stylesheet" href="<?php echo ASSETS_URL;?>errorPage/css/reset.css" media="screen">
<link rel="stylesheet" href="<?php echo ASSETS_URL;?>errorPage/css/master.css" media="screen">
<link rel="stylesheet" href="<?php echo ASSETS_URL;?>errorPage/css/responsive.css" media="screen">
<link rel="stylesheet" href="<?php echo ASSETS_URL;?>errorPage/css/font-awesome.min.css" media="screen">

<style type="text/css">

::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
	margin: 0;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
}
.pagenotfoundcover {
	max-width: 600px;
	/* margin-top: 100px; */
	margin: 100px auto 0;
	text-align: center;
	padding: 0 15px
}
.emsg {
	color: #cd1f26;
	font-size: 140px;
	font-weight: 700;
	letter-spacing: 5px;
	position: relative;
	display: inline-block;
	line-height: 100px;
}
.emsg span { opacity: 0 }
.emsg i {
	font-size: 60px;
	color: #fff;
	background: #cd1f26;
	width: 100px;
	height: 100px;
	position: absolute;
	left: 50%;
	top: -5px;
	display: block;
	margin-left: -57px;
	border-radius: 100% 100% 00% 100%;
	border: 5px solid #ffffff;
	line-height: 90px;
	padding: 0 34px;
}
.emsg-text {
	margin: 20px 0;
	font-size: 26px;
	line-height: 30px;
}
.emsg-text i {color: #cd1f26;}
header .logo {
	float: none;
	padding: 0px 0px;
	max-width: 100px;
	margin: 0 auto;
}
header {
  background: #6aa2b9 none repeat scroll 0 0;
}
</style>
</head>
<body>
	<!-- <div id="container">
		<h1><?php //echo $heading; ?></h1>
		<?php //echo $message; ?>
	</div> -->
<div class="wrapper">
<header class="head404">
	<div class="">
		<div class="logo"><img src="<?php echo ASSETS_URL;?>/admin/images/logo.png"></div>
	</div>
</header>
<div class="pagenotfoundcover">
	<div class="emsg">
		4<span>0</span>4
		<i class="fa fa-bolt"></i>
	</div>
	<div class="emsg-text"><i class="fa fa-info-circle"></i> Oops! The page you requested was not found!</div>
	<div>
		<a href="<?php echo base_url();?>admin/" class="btn">Back to Home</a>
	</div>
</div>
</div>
	
</body>
</html>
