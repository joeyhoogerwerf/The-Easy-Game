<?php
require 'php/facebook/facebook.php';
$facebook = new Facebook(array('appId'  => '359940667387959', 'secret' => 'f3352efba1ed894865d29be1292c4bd3'));//Init facebook-sdk.
$user = $facebook->getUser();//Returns a facebook-user-id when a user is logged in && authenticated.
if($user || $_SESSION['userId']) header('Location: http://127.0.0.1/teg/');//When user is already registered thus logged in klap em naar osso.

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="assets/css/style.register.css">
	
	<script src="assets/js/jquery.1.7.2_min.js"></script>
	
	<script>
	
	$(function(){
		//
		//alert('WARNING: You can only choose ');
	});
	
	function validate_async(form, cb) {
		errors = {};
		
		console.log('Validating username..' + form.username);
		
		$.getJSON('php/api/username', {'un':form.username}, function(d){
			if(d.msg != 0){
				cb({username: 'Sorry, that username is taken.'});
			}
			
			else{
				console.log('congrats! ' + form.username + ' is yours! ^_^');
				cb({});
			}
		});
	}
	</script>
</head>
<body>
	<div id="fb-root"></div>
	<script src="https://connect.facebook.net/en_US/all.js#appId=359940667387959&xfbml=1"></script>
	
	<div id="wrapper">
		<h1>Just pick a username.. that's it!</h1>
		<div id="registerBlo">
			<fb:registration id="fbRegistrationPlugin" fields="[{'name' : 'name'}, {'name' : 'username','type' : 'text', 'description' : 'Username'}]" redirect-uri="http://127.0.0.1/teg/php/api/user" width="700px" align="center" fb-only="true" onvalidate="validate_async">
			</fb:registration>
		</div>
		<h1 id="heen"></h1>
	
		<footer>
			<p><span style="color: #000044;">&copy;</span> 2012 <strong>T</strong>he <strong>E</strong>asy <strong>G</strong>ame</p>
			<p id="lLetters"><STRONG>your IP</STRONG> <span style="text-shadow: 0px 0px 1px #00bb00; font-weight: 900; font-size: 7pt; color:#00bb00;"><?php echo $_SERVER["REMOTE_ADDR"]; ?></span> <span class="pipe">|</span> version <span style="font-weight: 900; font-size: 7pt;">1.0</span> <span class="pipe">|</span> running since <span style="font-weight: 900; font-size: 7pt;">10</span> days <span class="pipe">|</span> <span style="font-weight: 900; font-size: 7pt;">2704</span> members <span class="pipe">|</span> <span style="font-weight: 900; font-size: 7pt;">608</span> online players <span class="pipe">|</span> <span style="font-weight: 900; font-size: 7pt;">39579</span> clicked clicks</p>
		</footer>
	</div>
</body>
</html>