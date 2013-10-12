<?php
session_start();

//When user is logged in.
if(isset($_SESSION['userId'])){

	//Get user_id.
	$userId = $_SESSION['userId'];
	
	//Open new DB-connection.
	$mysqli = new mysqli("localhost", "root", "root", "luckytime_test");
	
	$resultset_user = $mysqli->query("SELECT username, recaptcha_is_active, tao_clicks, score, ao_black_clicks, ao_grey_clicks, ao_blue_clicks, ao_strawberry_clicks, ao_apple_clicks, ao_sun_clicks, ao_haze_clicks, ao_white_clicks FROM users WHERE id=".$userId);
	
	$resultset_clicked_clicks = $mysqli->query("SELECT click_id, click_score, time, multiplier FROM clicked_clicks WHERE user_id=".$userId." ORDER BY click_id DESC LIMIT 0, 10");


	//Initialize array to store data.
	$user_data_arr = $resultset_user->fetch_assoc();



	$clicked_clicks_data_arr = array();

	//Fetch data into array.
	while($row = $resultset_clicked_clicks->fetch_assoc()){
		$clicked_clicks_data_arr[] = $row;
	}
	
	
	$data_arr = array('user_data' => $user_data_arr, 'click_data' => $clicked_clicks_data_arr);
	
	
	//Echo that shit.
	echo json_encode($data_arr);
}
?>