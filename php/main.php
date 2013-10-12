<?php
session_start();
require 'facebook/facebook.php';

$facebook = new Facebook(array('appId'  => '359940667387959', 'secret' => 'f3352efba1ed894865d29be1292c4bd3'));//Init facebook-sdk.
$user = $facebook->getUser();//Returns a facebook-user-id when a user is logged in && authenticated.

if($user){
	try{
		$user_profile = $facebook->api('/me');//Fetch user profile.
		$facebook->api('/me/permissions', 'DELETE');
		
		$mysqli = new mysqli("localhost", "root", "root", "luckytime_test");
		
		if($mysqli->connect_errno)
			throw new Exception('Something went wrong.');
		
		$resultset_fb_user = $mysqli->query('SELECT * FROM users WHERE fb_id = '.$user_profile['id']);
		
		
		if($resultset_fb_user->num_rows > 0){//When user is registered -> set fresh session.
			$fetched_user = $resultset_fb_user->fetch_assoc();	
			session_regenerate_id();
			$_SESSION['userId'] = $fetched_user['id'];
		}
		
		$mysqli->close();
	} 
	
	catch (FacebookApiException $e){//On facebook error.
		$user = null;
	}
	
	catch(Exception $e){//On local error.
		echo $e->getMessage();
	}
}

else{
	session_destroy();
}
//echo 'hyper';
?>