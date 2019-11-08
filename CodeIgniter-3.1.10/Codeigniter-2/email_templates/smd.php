<?php
use Illuminate\Database\Capsule\Manager as Capsule;	
if (!defined("WHMCS")) {
	die("This file cannot be accessed directly");
}

function smd_config() {
	$configarray = array(
		"name" => "SMD",
		"description" => "",
		"version" => "1.0",
		"author" => "Joseph Clarke",
		"language" => "english");

	return $configarray;
}
function smd_activate() {
	Capsule::schema()->create('consulting_tokens', function ($table) {
		$table->increments('id');
		$table->integer('userid');
		$table->integer('serviceid');
		$table->tinyInteger('used')->default(0);
		$table->integer('network_attorney_userid')->nullable();
		$table->timestamps();
	});

	Capsule::schema()->create('settlements', function ($table) {
		$table->increments('id');
		$table->integer('userid');
		$table->integer('spouse_userid');
       	$table->string('spouse_signup_token')->nullable();
		$table->integer('serviceid');
     	$table->integer("filled_document_id")->nullable();
     	$table->string('question_one_answer')->nullable();
     	$table->string('question_two_answer')->nullable();
     	$table->string('question_three_answer')->nullable();
  		$table->timestamps();
	});
	
   Capsule::schema()->create('network_attorneys', function ($table) {
		$table->increments('id');
		$table->integer('userid');
		$table->timestamps();
	});

   Capsule::schema()->create('selected_network_attorneys', function ($table) {
		$table->increments('id');
		$table->integer('userid');
		$table->integer('attorney_user_id');
		$table->integer('settlement_id');
		$table->string('status');
		$table->string('request_id');
		$table->timestamps();
	});



}
/*@Author: Zaptech
@date: 19/7/2018
@Description: This will give lat,lng based on zipcode */
function getLnt($zip)
{
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=
	".urlencode($zip)."&sensor=false";
	$result_string = file_get_contents($url);
	$result = json_decode($result_string, true);
	$result1[]=$result['results'][0];
	$result2[]=$result1[0]['geometry'];
	$result3[]=$result2[0]['location'];
	return $result3[0];
}

/*@Author: Zaptech
@date: 19/7/2018
@Description: This will give distance based on lat,lng */
function getDistance($zip1, $zip2, $unit)
{
	$first_lat = getLnt($zip1);
	$next_lat = getLnt($zip2);
	$lat1 = $first_lat['lat'];
	$lon1 = $first_lat['lng'];
	$lat2 = $next_lat['lat'];
	$lon2 = $next_lat['lng']; 
	$theta=$lon1-$lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
	cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
	cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);

	if ($unit == "K"){
	return ($miles * 1.609344)." ".$unit;
	}
	else if ($unit =="N"){
	return ($miles * 0.8684)." ".$unit;
	}
	else{
	//return $miles." ".$unit;
	return $miles;
	}

}
function smd_clientarea($vars) {
  require_once __DIR__ . "/../documentmanager/models/load.php";
  require_once __DIR__ . "/models/settlement.php";
  require_once __DIR__ . "/models/attorney.php";
  require_once __DIR__ . "/models/attorney_details.php";
  require_once __DIR__ . "/models/questions.php";
     
  $action = $_REQUEST['action'];
  //echo $action; die;
  if (isset($_REQUEST['k'])) {

   $settlement = \SMD\Settlement::where('spouse_signup_token', $_REQUEST['k'])->first();
   $spouse_client = \WHMCS\User\Client::find($settlement->spouse_userid);
   
   global $autoauthkey;
   $timestamp = time();    
   $email = $spouse_client->email; #
   $goto = "index.php?m=smd&action=update_password";
   $hash = sha1($email.$timestamp.$autoauthkey);
   $url = "dologin.php?email=$email&timestamp=$timestamp&hash=$hash&goto=".urlencode($goto);
   header("Location: $url");
   exit;
  }
  
  if (isset($_REQUEST['s'])) {
	$selected_attorney = \SMD\SelectedAttorney::where('request_id', $_REQUEST['s'])->first();
    $attorney_client = \WHMCS\User\Client::find($selected_attorney->attorney_user_id);
    global $autoauthkey;
    $timestamp = time();    
    $email = $attorney_client->email; #
    $goto = "index.php?m=smd&action=view_consultation_request&id=". $_REQUEST['s'];
    $hash = sha1($email.$timestamp.$autoauthkey);
    $url = "dologin.php?email=$email&timestamp=$timestamp&hash=$hash&goto=".urlencode($goto);
    header("Location: $url");
    exit();
  }
  
  if ($action == "update_password") {
	 if (isset($_POST['password'])) {
		 if ($_POST['password'] == $_POST['password2']) {
           //$spouse_client = \WHMCS\User\Client::find($_SESSION['uid']);
		   localAPI("UpdateClient",['clientid' => $_SESSION['uid'], "password2" => $_REQUEST['password']]);
		   $_SESSION['password_update_required'] = false;
		   unset($_SESSION['password_update_required']);
		   global $CONFIG;
		   global $whmcs;
           $spouse_client = \WHMCS\User\Client::find($_SESSION['uid']);
		   $_SESSION["upw"] = sha1(  $_SESSION["uid"] . $spouse_client->password . substr( sha1( $whmcs->get_hash() ), 0, 20 ) );
	       header("Location: clientarea.php");
           exit;
        }
	 }
	 else {
       $_SESSION['password_update_required'] = true;
	   $template  = "update_password";	 
	 }
	 
	 
  }
  elseif ($action == "view_members" && $_SESSION['account_type'] == "Network Attorney") {
	 $smartyvalues["members"] = \SMD\SelectedAttorney::where('attorney_user_id',$_SESSION['uid'])->whereRaw('(status = \'pending\' OR status = \'accepted\')')->get();
	 
	 $template = "view_members";
	  
  }
  elseif ($action == "view_consultation_request" && $_SESSION['account_type'] == "Network Attorney") {
	 $selected_attorney = \SMD\SelectedAttorney::where('request_id',$_REQUEST['id'])->first();
	 $smartyvalues["attorney_request"] = $selected_attorney;
	 $template = "view_consultation_request";
	  
  }

  ///consultation_complete

  	elseif ($action == "consultation_complete" && $_SESSION['account_type'] == "Network Attorney") { 

  	/*select `selected_network_attorneys`.*, `tblaffiliates`.`balance` from `selected_network_attorneys` join `tblaffiliates` on `selected_network_attorneys`.`attorney_user_id` = `tblaffiliates`.`clientid` where `request_id` = 'bf22d6fa613f6c39e104805e1efb2d5fa7c511c9'*/

	 $selected_attorney = \SMD\SelectedAttorney::select("selected_network_attorneys.*", "tblaffiliates.balance" )
	  	->join('tblaffiliates','selected_network_attorneys.attorney_user_id','=','tblaffiliates.clientid')
	 	->where('request_id',$_REQUEST['att_id'])
		->get();

		$att_details = $selected_attorney[0];
		//print_r($att_details);die;
		// add $75 in NA's account
		$current_bal = $att_details['balance']; 

		$update_by_amount = "75.00";
		$updated_credit = $current_bal + $update_by_amount;
		
		//DB update
		try {
		    $updatedUserCount = Capsule::table('tblaffiliates')
		        ->where('clientid', $att_details['attorney_user_id'])
		        
		        ->update(
		            [
		                'balance' => $updated_credit ,
		            ]
		        );

		    //update the newly added column (consultancy_complete) in selected attorney table
			
		   	$updatedUserCount = Capsule::table('selected_network_attorneys')
		        ->where('request_id', $_REQUEST['att_id'])
		        
		        ->update(
		            [
		                'consultancy_complete' => "1" ,
		                'consultancy_complete_date' => date("Y-m-d")
		            ]
		        );

		    
		} catch (\Exception $e) {
		    echo "I couldn't. {$e->getMessage()}";
		}
		

		$message = 'Your consultation has been completed by your selected network attorney<br /><br /><br />';
		//send notification email to user(client)
		/*sendMessage("Consultation Complete", $selected_attorney->userid, ['selected_attorney'=> $selected_attorney->selected_attorney]);*/

		$command = 'SendEmail';
		$postData = array(
		    '//example1' => 'example',
		    'messagename' => 'Consultation Complete',
		    'id' => $_REQUEST['id'],
		    '//example2' => 'example',
		    'customtype' => 'general',
		    'customsubject' => 'Consultation Complete Email',
		    'custommessage' => $message,
		    'customvars' => base64_encode(serialize(array('selected_attorney'=> $selected_attorney->selected_attorney))),
		);
		//$adminUsername = 'ADMIN_USERNAME'; // Optional for WHMCS 7.2 and later
		
		localAPI($command, $postData);

		header('Location: index.php?m=smd&action=view_members');
	 	exit();
	}


  elseif ($action == "respond_to_consultation_request" && $_SESSION['account_type'] == "Network Attorney") {
	 $selected_attorney = \SMD\SelectedAttorney::where('request_id',$_REQUEST['id'])->first();
	 if ($_REQUEST['accept'] == 1) {
	   $selected_attorney->status = "accepted";
	   $selected_attorney->save();
	   sendMessage("Your Network Attorney Request Accepted", $selected_attorney->userid, ['selected_attorney'=> $selected_attorney->selected_attorney]);
	  
	 }
	 else {
	   $selected_attorney->status = "declined";
	   $selected_attorney->save();	 
	   global $CONFIG;
	       
       $systemurl = ($CONFIG['SystemSSLURL']) ? $CONFIG['SystemSSLURL'].'/' : $CONFIG['SystemURL'].'/';
       $select_attorney_link = $systemurl ."index.php?m=smd&action=view_network_attorneys";
    
	   sendMessage("Your Network Attorney Request Rejected", $selected_attorney->userid, ['select_network_attorney_link'=> $select_attorney_link]);
	
	 }
	 
	 header('Location: index.php?m=smd&action=view_members');
	 exit();
	  
  }

 
  elseif (($action == "view_network_attorneys" && $_SESSION['account_type'] == "Member") || ($action =="network_attorney_filter" && $_SESSION['account_type'] == "Member")) {  
	  $settlement = \SMD\Settlement::whereRaw('userid = ? OR spouse_userid =?',[$_SESSION['uid'], $_SESSION['uid']])->first();
	  
	  $selected_attorney = \SMD\SelectedAttorney::where('userid',  $_SESSION['uid'])->where('settlement_id', $settlement->id)->where('status','accepted')->first();
	  
	  /*if ($selected_attorney) { 
	    $attorney["selected_attorney"] = $selected_attorney->selected_attorney;  
	    
	  } 
	  else { */

	  	if ($selected_attorney) { 
	    	$smartyvalues["selected_attorney"] = true;//$selected_attorney->selected_attorney;  
	    
	  	} 
	     $pending_attorney = \SMD\SelectedAttorney::where('userid',  $_SESSION['uid'])->where('settlement_id', $settlement->id)->where('status','pending')->first();

	     if ($pending_attorney) {
	     		$smartyvalues["pending_attorney_status"] = true;
		     	$smartyvalues["pending_attorney"] = $pending_attorney;
		     }



		/* if (!$pending_attorney) { */
			 $attorney_status_id = \WHMCS\CustomField::where('fieldname','Network Attorney Status')->where('type','client')->first()->id;

			if($action =="network_attorney_filter"){
				
				
				
				//print_r("value between ".$hourly_rate[0]." and ".$hourly_rate[1]);die;
				 $attorneys = \SMD\Attorney::select("tblclients.id as userid","tblclients.*")
			       	->join('tblcustomfieldsvalues','tblclients.id','=','tblcustomfieldsvalues.relid')
			       	
			       	->where('fieldid','3')
			       	->where('value', 'Published');

			       	if($_POST['attorney_language']){
			       		$attorneys = $attorneys->whereIn('tblcustomfieldsvalues.relid',  function($query){
			       			$query->select('tblcustomfieldsvalues.relid')
			       			->from((new WHMCS\CustomField\CustomFieldValue)->getTable())
			       			->where('fieldid','4')
			       			->whereRaw("FIND_IN_SET('".$_POST['attorney_language']."',value)");
			       		});
			       	}
			       	

			       	if($_POST['attorney_location']){
			       		$attorneys = $attorneys->where('postcode',$_POST['attorney_location']);
			       	}
			       	if($_POST['attorney_distance']){ //5,10 miles from dropdown
			       		$attorny_distance = $_POST['attorney_distance'];

			       		$attorneys = \SMD\Attorney::select("tblclients.id as userid","tblclients.*")
				       	->join('tblcustomfieldsvalues','tblclients.id','=','tblcustomfieldsvalues.relid')
				       	
				       	->where('fieldid','3')
				       	->where('value', 'Published');
			       		$attorneys = $attorneys->get();
			       		foreach($attorneys as $akey => $avalue)
			       		{
			       			//echo $akey.'-'.$avalue->postcode.PHP_EOL;
			       			$zipCode = $avalue->postcode; //zipcode db
			       			$zipCode2 = $_POST['attorney_location']; //zipcode entered
			       			$distance = getDistance($zipCode,$zipCode2,"M");
							echo $distance.'<br>';
							preg_match_all('!\d+!', $attorny_distance, $matches);
							//echo '<pre>';
							$attorney_distance = $matches[0][0];
							echo 'attorny_distance'.$attorny_distance.'<br>';
							if($attorny_distance <= $distance)
							{
								$attorneys = \SMD\Attorney::select("tblclients.id as userid","tblclients.*")
						       	->join('tblcustomfieldsvalues','tblclients.id','=','tblcustomfieldsvalues.relid')
						       	
						       	->where('fieldid','3')
						       	->where('value', 'Published');
							}
			       		}
			       		$attorneys = $attorneys->get();
			       		echo "<pre>";print_r($attorneys);die;
			       	}

			      	if($_POST['hourly_rate_discount']){
			       		$attorneys = $attorneys->whereIn('tblcustomfieldsvalues.relid',  function($query){
			       			$query->select('tblcustomfieldsvalues.relid')
			       			->from((new WHMCS\CustomField\CustomFieldValue)->getTable())
			       			->where('fieldid','7')
			       			->where('value', $_POST['hourly_rate_discount']);
			       		});
			        }
			       	if( $_POST['attorney_minimum_retainer']){
			       		$attorneys = $attorneys->whereIn('tblcustomfieldsvalues.relid',  function($query){
			       			
			       			$min_retainer = explode("-", $_POST['attorney_minimum_retainer']);
			       			if($min_retainer[1] != ""){
			       				$q_str = "value between ".$min_retainer[0] ."and ". $min_retainer[1];
			       			}else{
			       				$q_str = "value >= ".$min_retainer[0];
			       			}
			       			
			       			$query->select('tblcustomfieldsvalues.relid')
			       			->from((new WHMCS\CustomField\CustomFieldValue)->getTable())
			       			->where('fieldid','8')
			       			->whereRaw($q_str);
			       		});
			       	}
					if($_POST['attorney_avg_hourly_rate']){
			       		$attorneys = $attorneys->whereIn('tblcustomfieldsvalues.relid',  function($query){
			       			$hourly_rate =explode("-", $_POST['attorney_avg_hourly_rate']);
			       			if($hourly_rate[1] != ""){
			       				$query_str = "value between ".$hourly_rate[0] ."and ". $hourly_rate[1];
			       			}else{
			       				$query_str = "value >= ".$hourly_rate[0];
			       			}
			       			$query->select('tblcustomfieldsvalues.relid')
			       			->from((new WHMCS\CustomField\CustomFieldValue)->getTable())
			       			->where('fieldid','6')
			       			->whereRaw($query_str);
			       		});
					}
			       	$attorneys = $attorneys->get();
			       //	echo "<pre>";print_r($attorneys);die;
					//echo "<pre>";print_r($_POST);die;
					//array of elements to be selected

					$smartyvalues['postvar']['attorney_language'] = $_POST['attorney_language'];
					$smartyvalues['postvar']['hourly_rate_discount'] = $_POST['hourly_rate_discount'];
					$smartyvalues['postvar']['attorney_minimum_retainer'] = $_POST['attorney_minimum_retainer'];
					$smartyvalues['postvar']['attorney_avg_hourly_rate'] = $_POST['attorney_avg_hourly_rate'];
					$smartyvalues['postvar']['attorney_location'] = $_POST['attorney_location'];

			}else{
					$smartyvalues['postvar'] = "";
				  $attorneys = \SMD\Attorney::select("tblclients.id as userid","tblclients.*")
			       	->join('tblcustomfieldsvalues','tblclients.id','=','tblcustomfieldsvalues.relid')
			       	->where('fieldid',$attorney_status_id)
			       	->where('value', 'Published')
			       	->get();
			}
		  
	      //select query
	     /*SELECT tblclients.id as userid, tblcustomfieldsvalues.* FROM `tblclients` JOIN tblcustomfieldsvalues ON tblclients.id = tblcustomfieldsvalues.relid WHERE fieldid IN (3,4)  AND (value = 'Published' OR FIND_IN_SET("English",value)) group by relid;*/
	  

	     /*SELECT tblclients.id as userid,tblclients.*, tblcustomfieldsvalues.* FROM `tblclients` JOIN tblcustomfieldsvalues ON tblclients.id = tblcustomfieldsvalues.relid WHERE fieldid = '4' AND 
FIND_In_SET("English",value) AND tblcustomfieldsvalues.relid IN (select  tblcustomfieldsvalues.relid from  tblcustomfieldsvalues where fieldid ='3' and value='Published')*/


	        //set search filter query here


	       if ($settlement->spouse_userid != 0) {
			    if ($settlement->spouse_userid == $_SESSION['uid']) {
				   $spouse_attorney = \SMD\SelectedAttorney::where('userid',  $settlement->userid)->where('settlement_id', $settlement->id)->whereRaw('(status = \'pending\' OR status = \'accepted\')')->first();
			    }
			    else {
				   $spouse_attorney = \SMD\SelectedAttorney::where('userid',  $settlement->spouse_userid)->where('settlement_id', $settlement->id)->whereRaw('(status = \'pending\' OR status = \'accepted\')')->first();  
			    }
		    }

		     
		  	$smartyvalues['first_na_consultancy'] = "";

            foreach ($attorneys as $attorney) { 

	         	$prev_selected = \SMD\SelectedAttorney::select('selected_network_attorneys.*')->where('userid',  $_SESSION['uid'])->where('settlement_id', $settlement->id)->where('attorney_user_id', $attorney->userid)->get(); 
	         	
		     	if ($prev_selected[0]->status && $prev_selected[0]->status =="declined") {
			    	$attorney["declined"] = true; 
		     	}
		     	$attorney['consultancy_complete'] = $prev_selected[0]->consultancy_complete; 
		     	// check if spouse selected, approved or pending
			     if (isset($spouse_attorney) && $spouse_attorney->attorney_user_id == $attorney->userid) {
				    $attorney["spouse_selected"] = true;
			     }

			     //check if consultancy is completed with original NA
			    if($prev_selected[0]->status =="accepted" && $prev_selected[0]->consultancy_complete == "1"){
			    	$smartyvalues['first_na_consultancy'] = "complete";
			    }
		    
		      
		     
	         $attorney["languages"] = smd_load_client_custom_field("Languages Spoken", $attorney->userid);
		     $attorney["avvo_rating"] = smd_load_client_custom_field("AVVO Rating", $attorney->userid);
		     $attorney["specialties"] = smd_load_client_custom_field("Practice Specialties", $attorney->userid);
             $attorney["hourly_rate"] = smd_load_client_custom_field("Hourly Rate", str_replace('$$', '$', $attorney->userid));
             
            //echo '<pre>';print_r($attorney); die;
             $smartyvalues["attorneys"][] = $attorney;
 	        }	
 	        
		 /*}
		 else {
	  	    $smartyvalues["pending_attorney"] = $pending_attorney;
		 }*/

	  /*}*/

	 $template = "view_network_attorneys";
	 
  }
  elseif ($action == "get_attorney_details" && $_SESSION['account_type'] == "Member") {
	$attorney = \WHMCS\User\Client::find($_REQUEST['id']);
	$attorney["languages"] = smd_load_client_custom_field("Languages Spoken", $attorney->id);
	$attorney["avvo_rating"] = smd_load_client_custom_field("AVVO Rating", $attorney->id);
	$attorney["specialties"] = smd_load_client_custom_field("Practice Specialties", $attorney->id);
    $attorney["hourly_rate"] = smd_load_client_custom_field("Hourly Rate", $attorney->id);
    $attorney["hourly_rate_discount"] = smd_load_client_custom_field("Hourly Rate Discount %", $attorney->id);
   
    $attorney["retainer_contested"] = smd_load_client_custom_field("Minimum Retainer", $attorney->id);
    $attorney["minimum_retainer"] = smd_load_client_custom_field("Minimum Retainer", $attorney->id);
    $attorney["retainer_discount"] = smd_load_client_custom_field("Retainer Discount %", $attorney->id);
    $attorney["smd_rating"] = smd_load_client_custom_field("SMD Rating", $attorney->id);
   
    echo "<strong>Languages Spoken: </strong>" . $attorney["languages"];
    echo "<br><strong>Practice Specialties: </strong>" . $attorney["specialties"];
    echo "<br><strong>Hourly Rate: </strong>" . $attorney["hourly_rate"];
    echo "<br><strong>Hourly Rate Discount %: </strong>" . $attorney["hourly_rate_discount"];        
    echo "<br><strong>Minimum Retainer: </strong>" . $attorney["minimum_retainer"];
    echo "<br><strong>Retainer Discount %: </strong>" . $attorney["retainer_discount"];
    echo "<br><strong>AVVO Rating: </strong>" . $attorney["avvo_rating"];
    echo "<br><strong>SMD Rating: </strong>" . $attorney["smd_rating"];
    exit();

  }
  elseif (($action == "send_consultation_request" || $action == "grant_access_request") && $_SESSION['account_type'] == "Member") {
	  // verify they haven't used their token yet
	  // send consultation request
	  $settlement = \SMD\Settlement::whereRaw('userid = ? OR spouse_userid =?',[$_SESSION['uid'], $_SESSION['uid']])->first();
	  $attorney = new \SMD\SelectedAttorney;
	  $attorney->attorney_user_id = $_REQUEST['id'];
	  $attorney->settlement_id = $settlement->id;
	  $attorney->userid = $_SESSION['uid'];
	  $attorney->status = "pending";
	  $attorney->request_id = sha1(uniqid());
	  $attorney->save();
	  
	  global $CONFIG;
	       
      $systemurl = ($CONFIG['SystemSSLURL']) ? $CONFIG['SystemSSLURL'].'/' : $CONFIG['SystemURL'].'/';
      $smd_request_link = $systemurl ."index.php?m=smd&s=".$attorney->request_id;
    


	  sendMessage("Network Attorney Request", $attorney->attorney_user_id, ['smd_request'=> $smd_request_link]);
	  
	  $_SESSION['alerts'] = "Your request as been sent, you will receive an email once your selected attorney accepts or rejects your request.";
	  header("Location: index.php?m=smd&action=view_network_attorneys");
	  exit();
	  
  }
  //grant settelment document request

  elseif ($action == "wizard") {
  	//echo $action; die;
	  $settlement = \SMD\Settlement::where('userid', $_SESSION['uid'])->first();
	  $selected_attorney = \SMD\SelectedAttorney::where('userid',  $_SESSION['uid'])->where('settlement_id', $settlement->id)->where('status','accepted')->first();
	  
	  if ($selected_attorney) {
	    $smartyvalues["selected_attorney"] = $selected_attorney->selected_attorney;  
	  }
	  // echo '<pre>';
	  // print_r($settlement); die;
	  if ($settlement) {
	    // Primary Account Holder
	    $smartyvalues["settlement"] = $settlement;

	    if (!empty($settlement->question_one_answer)) {
		   $smartyvalues["step"] = "complete_docs";
	    }
	    else {
	       $smartyvalues["step"] = "get_organized";
	    }
   	    $template = "wizard";	    	  
	  }
	  else {
	    $settlement = \SMD\Settlement::where('spouse_userid',$_SESSION['uid'])->first();
	    //$smartyvalues["step"] = "complete_docs";
	    if($settlement)
	    {
	    	$settlement = \SMD\Settlement::where('spouse_userid',$_SESSION['uid'])->first();
	    	$smartyvalues["step"] = "complete_docs";
     		$template = "wizard";
	    }
	    else
	    {
	    	$settlement = \SMD\Settlement::where('userid', '6899')->first();	
	    	$smartyvalues["settlement"] = $settlement;

	    	if (!empty($settlement->question_one_answer)) 
	    	{
		   		$smartyvalues["step"] = "get_organized";
	    	}
	    	else 
	    	{
	       		$smartyvalues["step"] = "get_organized";
	    	}
   	    	$template = "wizard";	    	  
	    }
	    // $settlement = \SMD\Settlement::where('userid', $_SESSION['uid'])->first();
	    // $smartyvalues["step"] = "get_organized";
	    // $smartyvalues["flag"] = "new_user";
     // 	$template = "wizard";
     // 	$settlement = \SMD\Questions::select("*")
			  //      	->where('id', '5')
			  //      	->get();
		// echo '<pre>';
	 //  	print_r($settlement); die;
	  	// $smartyvalues["settlement"] = $settlement;
	  }
	  //echo $_SESSION['uid']; die;
	  // echo '<pre>';
	  // print_r($smartyvalues); die;
	  
  }
  elseif ($action == "save_answers") {
	$settlement = \SMD\Settlement::where('userid', $_SESSION['uid'])->first();
	  if ($settlement) {
	    $settlement->question_one_answer = $_REQUEST['question_one_answer'];
	    
	    if ($settlement->question_set->question_two_only_show_if_previous_answer_is_yes == 1) {
     	  
     	  if ($settlement->question_one_answer == "Yes") {
     	    $settlement->question_two_answer = $_REQUEST['question_two_answer'];
          }
          else {
	         $settlement->question_two_answer = null;  
          }
	    }
	    else {
	      $settlement->question_two_answer = $_REQUEST['question_two_answer'];
		    
	    }

	    if ($settlement->question_set->question_three_only_show_if_previous_answer_is_yes == 1) {
     	  
     	  if ($settlement->question_two_answer == "Yes") {
     	    $settlement->question_three_answer = $_REQUEST['question_three_answer'];
          }
          else {
	         $settlement->question_three_answer = null;  
          }
	    }
	    else {
	      $settlement->question_three_answer = $_REQUEST['question_three_answer'];
		    
	    }
        $settlement->save();
        $smartyvalues["step"] = "complete_docs";
   	    $template = "wizard";	    	  
	  }  
  }
  $smartyvalues["modulelink"] = $vars['modulelink'];


  return array(
    'pagetitle' => 'SMD',
    'breadcrumb' => array($modulelink=>'SMD'),
    'templatefile' => $template,
    'requirelogin' => true,
    'vars' => $smartyvalues,
  );
  
 }	


?>
