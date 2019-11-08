<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//print_r($_POST); die;
if(isset($_FILES["file"]["name"]) && $_FILES["file"]["name"] != ''){
	$newfilename = $_FILES["file"]["name"];
	$upload = move_uploaded_file($_FILES["file"]["tmp_name"], "/home/giftcast/public_html/master/assets/user_videos/".$newfilename);
	if($upload){
		$thumbnail_name =  preg_replace('"\.(mp4|avi|flv|vob|oggg)$"', '.jpg', $newfilename);
		$movie = "/home/giftcast/public_html/master/assets/user_videos/".$newfilename;
		$thumbnail = "/home/giftcast/public_html/master/assets/user_videos/".$thumbnail_name;
		$selectType = $_POST['selectType'];
		$devicetype = $_POST['devicetype'];
		if($devicetype == 'ios')
		{
			if($selectType == 'library') //if library then only rotate the screenshot
			{
			   //$command = '/usr/bin/ffmpeg -y -ss 00:00:01 -i '.$movie.' -f image2 -vframes 1 '.$thumbnail.' 2>&1';
	            $command = '/usr/bin/ffmpeg -ss 00:00:01 -i '.$movie.' -f image2 -vframes 1 '.$thumbnail.' 2>&1';
			    $output = passthru($command);
			}
			else
			{
				$command = '/usr/bin/ffmpeg -ss 00:00:01 -i '.$movie.' -f image2 -vframes 1 '.$thumbnail.' 2>&1';
	        	$output = passthru($command);
	            //$image = '/var/www/html/demos/exif/test.jpg';
				$image = $thumbnail;
				//rotation angle
				$degrees = 270;

				//load the image
				$source = imagecreatefromjpeg($image);

				//rotate the image
				$rotate = imagerotate($source, $degrees, 0);


				//display the rotated image on the browser
				imagejpeg($rotate,$thumbnail);

				//free the memory
				imagedestroy($source);
				imagedestroy($rotate);
			}
		}
		else //android
		{
			if($selectType == 'library') //if library then only rotate the screenshot
			{
			   //$command = '/usr/bin/ffmpeg -y -ss 00:00:01 -i '.$movie.' -f image2 -vframes 1 '.$thumbnail.' 2>&1';
	            $command = '/usr/bin/ffmpeg -ss 00:00:01 -i '.$movie.' -f image2 -vframes 1 '.$thumbnail.' 2>&1';
			    $output = passthru($command);
			}
			else
			{
				$command = '/usr/bin/ffmpeg -ss 00:00:01 -i '.$movie.' -f image2 -vframes 1 '.$thumbnail.' 2>&1';
	        	$output = passthru($command);
	            //$image = '/var/www/html/demos/exif/test.jpg';
				$image = $thumbnail;
				//rotation angle
				$degrees = 90;

				//load the image
				$source = imagecreatefromjpeg($image);

				//rotate the image
				$rotate = imagerotate($source, $degrees, 0);


				//display the rotated image on the browser
				imagejpeg($rotate,$thumbnail);

				//free the memory
				imagedestroy($source);
				imagedestroy($rotate);
			}
		}
		
	    $data = array('status' => 1 , 'video'=> $newfilename, 'screenshot'=> $thumbnail_name, 'message' => 'Video successfully uploaded','selectType' => $selectType ,'devicetype' => $devicetype);
	}
	else{
	    $data = array('status' => 0 , 'message' => 'Error in uploading');
	}
	
}
else{
	$data = array('status' => 0, 'message' => 'Please select video' );
}
echo json_encode($data); die;
?>
`~~`