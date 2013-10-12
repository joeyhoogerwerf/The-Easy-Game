<?php
session_start();
sleep(0);
//Get fresh-timestamp.
$fresh_timestamp = time();

//Determine click value.
function determine_click_value($cT){
	
	switch($cT){
		
		case 'black':
			return 1;
			break;
			
		case 'grey':
			return 5;
			break;
			
		case 'blue':
			return 25;
			break;
			
		case 'strawberry':
			return 100;
			break;
			
		case 'apple':
			return 500;
			break;
			
		case 'sun':
			return 1000;
			break;
			
		case 'haze':
			return 5000;
			break;
		
		case 'white':
			return 10000;
			break;
	}
	
}

//Calculate multiplier depending on current time.
function calculateMultiplierByUTC($ts){
	
	$h = gmdate('H', $ts);
	$m = gmdate('i', $ts);
	$s = gmdate('s', $ts);
	
	//TEST TIME
	/*$h = 12;
	$m = 11;
	$s = 11;*/
	
	$arrMultiplier5 = array('00', '11', '22', '33', '44', '55');//
	$arrMultiplier10 = array('0101', '0202', '0303', '0404', '0505', '0606');//<<<<<<<<-------------FIXENNNNNNNNNNNNNN 60x per uur :)
	$arrMultiplier15 = array('000', '111', '222', '333', '444', '555');//
	$arrMultiplier25 = array('012', '123', '234', '345', '456');//
	$arrMultiplier40 = array('0000', '1111', '2222', '3333', '4444', '5555');//
	$arrMultiplier50 = array('0123', '1234', '2345', '3456');//
	$arrMultiplier70 = array('00000', '11111', '22222', '33333', '44444', '55555');//
	$arrMultiplier80 = array('01234', '12345', '23456');//
	$arrMultiplier90 = array('000102', '010203', '020304', '030405', '040506', /*'050607',*/ '060708', '070809', '080910', '091011', '101112', '111213', '121314', '131415', '141516', '151617', '161718', '171819', '181920', '192021', '202122', '212223', '222324', '232425', '242526', '252627', '262728', '272829', '282930', '293031');
	$arrMultiplier100 = array('010101', '020202', '030303', '040404', '050505', '060606', '070707', '080808', '090909', '101010', '121212', '131313', '141414', '151515', '161616', '171717', '181818', '191919', '202020', '212121', '232323');//
	$arrMultiplier125 = array('000000', '111111', '222222', '112233', '223344');//
	$arrMultiplier150 = array('123456', '012345');//
	
	foreach($arrMultiplier150 as $mp150){
		if($h.$m.$s == $mp150)
			return array("winning_nr_combination" => $mp150, "multiplier" => 150);
	}
	
	foreach($arrMultiplier125 as $mp125){
		if($h.$m.$s == $mp125)
			return array("winning_nr_combination" => $mp125, "multiplier" => 125);
	}
	 
	foreach($arrMultiplier100 as $mp100){
		if($h.$m.$s == $mp100)
			return array("winning_nr_combination" => $mp100, "multiplier" => 100);
	}
	
	foreach($arrMultiplier90 as $mp90){
		if($h.$m.$s == $mp90)
			return array("winning_nr_combination" => $mp90, "multiplier" => 90);
	}
	
	foreach($arrMultiplier80 as $mp80){
		if(implode('', array(substr($h, 1), $m, $s)) == $mp80)
				return array("winning_nr_combination" => $mp80, "multiplier" => 80);
	}
	
	foreach($arrMultiplier70 as $mp70){
		if(implode('', array(substr($h, 1), $m, $s)) == $mp70)
			return array("winning_nr_combination" => $mp70, "multiplier" => 70);
	}
	
	foreach($arrMultiplier50 as $mp50){
		if($m.$s == $mp50)
			return array("winning_nr_combination" => $mp5, "multiplier" => 50);
	}
	
	foreach($arrMultiplier40 as $mp40){
		if($m.$s == $mp40)
			return array("winning_nr_combination" => $mp40, "multiplier" => 40);
	}
	
	foreach($arrMultiplier25 as $mp25){
		if(implode('', array(substr($m, 1), $s)) == $mp25)
			return array("winning_nr_combination" => $mp25, "multiplier" => 25);
	}
	
	foreach($arrMultiplier15 as $mp15){
		if(implode('', array(substr($m, 1), $s)) == $mp15)
			return array("winning_nr_combination" => $mp15, "multiplier" => 15);
	}
	
	foreach($arrMultiplier5 as $mp5){
		if($s == $mp5)
			return array("winning_nr_combination" => $mp5, "multiplier" => 5);
	}
		
	return array("winning_nr_combination" => '', "multiplier" => 1);
}

//When user is logged in & POST contains click_type.
if(isset($_SESSION['userId']) && isset($_POST['type'])){

	//Initialize variables.
	$user_id = $_SESSION['userId'];
	$time_UTC = gmdate('H:i:s', $fresh_timestamp);
	$score = 0;
	
	//Open new DB-connection.	
	$mysqli = new mysqli("localhost", "root", "root", "luckytime_test");
	
	$resultset_recaptcha_is_active = $mysqli->query("SELECT recaptcha_is_active FROM users WHERE id = ".$_SESSION['userId']);
	$recaptcha_is_active = $resultset_recaptcha_is_active->fetch_assoc();
	
	if($recaptcha_is_active['recaptcha_is_active'] == 0){
		//Get amount of playable clicks by click-type.
		$resultset_ao_playable_clicks = $mysqli->query("SELECT ao_".$_POST['type']."_clicks FROM users WHERE id=".$user_id);
		$ao_playable_clicks = $resultset_ao_playable_clicks->fetch_assoc();
		$ao_playable_clicks = $ao_playable_clicks['ao_'.$_POST['type'].'_clicks'];
	
		//When user has a clickturn.
		if($ao_playable_clicks > 0){
		
			//Determine click-value of click-type.
			$click_value = determine_click_value($_POST['type']);
		
			//Calculate time-multiplier.
			$mp_UTC_arr = calculateMultiplierByUTC($fresh_timestamp);
		
			//Calculate score.
			$score = ($click_value * $mp_UTC_arr['multiplier']);
		
			//User losing 1 click of click-type.
			$mysqli->query("UPDATE users SET ao_".$_POST['type']."_clicks = ".($ao_playable_clicks - 1)." WHERE id=".$user_id);
		
			//Insert new click.
			$mysqli->query("INSERT INTO clicked_clicks VALUES ('', 'click', ".$user_id.", '".$time_UTC."', ".$mp_UTC_arr['multiplier'].", ".$score.")");
		
			//Get last inserted click_id.
			$id_last_inserted = $mysqli->insert_id;
		
			//Update click table.
			$mysqli->query("UPDATE clicked_clicks SET click_score = click_score + 1111 WHERE click_id=".$id_last_inserted);
		
			//Get old-score & old total-amount-of-clicks
			$resultset_old_data = $mysqli->query("SELECT score, tao_clicks FROM users WHERE id=".$user_id);
			$old_data = $resultset_old_data->fetch_assoc();
			$score_new = ($old_data['score'] + $score);
		
			//Update score & tao_clicks.
			$mysqli->query("UPDATE users SET score = ".$score_new.", tao_clicks = ".($old_data['tao_clicks'] + 1)." WHERE id=".$user_id);
		
			//Get final score.
			//$resultset_fs = $mysqli->query("SELECT score FROM users WHERE id=".$user_id);
			//$final_score = $resultset_fs->fetch_assoc();

			//echo table data
			$response = array('time' => $time_UTC, 'multiplier' => $mp_UTC_arr['multiplier'], 'score' => $score, 'newScore' => $score_new, 'tao_clicks' => $old_data['tao_clicks'] + 1, 'id_last_inserted' => $id_last_inserted);
		
			echo json_encode($response);	
		}		
	}
}
?>