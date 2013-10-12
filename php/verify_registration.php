<?php
define('FACEBOOK_APP_ID', '359940667387959');
define('FACEBOOK_SECRET', 'f3352efba1ed894865d29be1292c4bd3');

function parse_signed_request($signed_request, $secret){
  list($encoded_sig, $payload) = explode('.', $signed_request, 2);//Explode signed-request into encoded-real-fb-sig-key + data-payload.
  $sig = base64_url_decode($encoded_sig);//Decrypt data.
  $data = json_decode(base64_url_decode($payload), true);//Decode payload into JSON-string.
  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {//Verify used decrypting-algorithm.
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
  }
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);//Decode the real-fb-sig-key.
  if ($sig !== $expected_sig) {//Check if used signature-key is valid.
    error_log('Bad Signed JSON signature!');
    return null;
  }
  return $data;
}

function base64_url_decode($input){
    return base64_decode(strtr($input, '-_', '+/'));
}


if($_REQUEST){//When request contains REQUEST-data.
	$response = parse_signed_request($_REQUEST['signed_request'], FACEBOOK_SECRET);//Parse data into working object.
	$username = $response['registration']['username'];
	
	echo 'USERNAME['.$username.']';
	print_r($response);

	//Check if posted fields are valid.
	//if()
	
	
	
	
	
	`id` ,
	`fb_id` ,
	`username` ,
	`tao_clicks` ,
	`score` ,
	`ao_live_black` ,
	`ao_live_grey` ,
	`ao_live_blue` ,
	`ao_live_strawberry` ,
	`ao_live_apple` ,
}







/*define('FACEBOOK_APP_ID', '359940667387959');
define('FACEBOOK_SECRET', 'f3352efba1ed894865d29be1292c4bd3');

$username = "";

function parse_signed_request($signed_request, $secret) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

  $sig = base64_url_decode($encoded_sig);// decode the data
  $data = json_decode(base64_url_decode($payload), true);

  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
  }

  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);// check sig
  if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
  }

  return $data;
}

function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
}


if($_REQUEST){//When request contains data.
	$response = parse_signed_request($_REQUEST['signed_request'], FACEBOOK_SECRET);
	$username = $response['registration']['username'];
	
	// /print_r($response);

	print_r(json_decode('{"dop":1}'));
	//Check if posted fields are valid.
	//if()
} 
//////////////////////////////////

session_start();
require 'facebook/facebook.php';

//Init Facebook SDK.
$facebook = new Facebook(array(
  'appId'  => '359940667387959',
  'secret' => 'f3352efba1ed894865d29be1292c4bd3'
));

//Get authenticated user.
$user = $facebook->getUser();

if($user){//When user is logged in at Facebook & has authorized The Easy Game.
	try{
		$user_profile = $facebook->api('/me');//Fetch user profile.

		//Query user table with facebook_id.
		$mysqli = new mysqli("localhost", "root", "root", "luckytime_test");
		
		
		$username = $mysqli->real_escape_string($username);
		
		
		$mysqli->select_db("luckytime_test");
		$resultset_fb_user = $mysqli->query('SELECT * FROM users WHERE fb_id = '.$user_profile['id']);
		
		//When user is registered -> set session.
		if($resultset_fb_user->num_rows > 0){
			
			//Fetch user -> generate new SESSION-id -> set session.
			$fetched_user = $resultset_fb_user->fetch_assoc();	
			session_regenerate_id();
			$_SESSION['userId'] = $fetched_user['id'];
			
			echo 'Exisinting user fo!';
			//$facebook->api('/me/permissions', 'DELETE');
			//header('Location: http://127.0.0.1/teg/');
		}
		
		//When user is not registered -> insert new user in database -> set session.
		else{
			//echo 'Registered new user: '.$user_profile['id'];
			$resultset_user = $mysqli->query("INSERT INTO users VALUES ('', '".$user_profile['id']."', '".$username."', 0, 0, 5, 2, 1, 1, 1, 0, 0, 0, 0, ".time().", 100, 0)");
			$resultset_for_session_id = $mysqli->query('SELECT id FROM users WHERE fb_id = '.$user_profile['id']);
			$fetched_id = $resultset_for_session_id->fetch_assoc();
			
			session_regenerate_id();
			$_SESSION['userId'] = $fetched_id['id'];
			header('Location: http://127.0.0.1/teg/');
			//$facebook->api('/me/feed', 'POST', array('message' => 'Playing the easiest game in the world. http://theeasygame.com'));
		}
		
		$mysqli->close();
	} 
	
	//On Facebook error.
	catch (FacebookApiException $e){
		$user = null;
	}
}*/
?>