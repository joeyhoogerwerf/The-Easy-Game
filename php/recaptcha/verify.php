<?php
session_start();

if(isset($_SESSION['userId'])){

	require_once('recaptchalib.php');
	$privatekey = "6LeMBNISAAAAAB9D-EQ2wskvSTNyROUvZrkMPAyS";
	$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST['c'], $_POST['r']);

	if (!$resp->is_valid) {
	   // What happens when the CAPTCHA was entered incorrectly
		echo false;
	}

	else{
	   // Your code here to handle a successful verification
		echo true;
		
		$mysqli = new mysqli("localhost", "root", "root", "luckytime_test");//Open new DB-connection.
		if($mysqli->connect_errno)	throw new Exception('Something went wrong.');/*VERIFY DB-CONNECTION*/
		
		$mysqli->query('UPDATE users SET recaptcha_is_active = 0 WHERE id = '.$_SESSION['userId']);
	}
}
?>