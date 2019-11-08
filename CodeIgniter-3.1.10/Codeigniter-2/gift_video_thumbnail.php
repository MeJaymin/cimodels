<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//print_r($_FILES);
if(isset($_FILES["file"]["name"]) && $_FILES["file"]["name"] != '')
{
	$newfilename = $_FILES["file"]["name"];
//echo $_FILES["file"]["name"]; die;
	$upload = move_uploaded_file($_FILES["file"]["tmp_name"], "/home/giftcast/public_html/master/assets/user_videos/".$newfilename);
	if($upload)
	{
		$thumbnail_name =  preg_replace('"\.(jpeg|gif|png )$"', '.jpg', $newfilename);
		//$movie = "/home/giftcast/public_html/master/assets/user_videos/".$newfilename;
		$thumbnail = "/home/giftcast/public_html/master/assets/user_videos/".$thumbnail_name;
		// $command = '/usr/bin/ffmpeg -ss 00:00:01 -i '.$movie.' -f image2 -vframes 1 '.$thumbnail.' 2>&1';
		// $output = passthru($command);
	    $data = array('status' => 1 , 'message' => 'Image successfully uploaded','Image Name' => $thumbnail_name);
	}
	else
	{
	    $data = array('status' => 0 , 'message' => 'Error in uploading');
	}
	
}
else
{
	$data = array('status' => 0, 'message' => 'Please select image' );
}
echo json_encode($data); die;
?>

