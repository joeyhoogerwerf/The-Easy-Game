<?php#This interface serves all GET-requests & POST-datatransfers.
require 'Slim/Slim.php';
require '../facebook/facebook.php';

{/*GLOBAL FUNCTIONS*/

	function s_s(){//Start SESSION!
		session_start();
	}
	
	function r_db_conn(){//Return DATABASE CONNECTION!
		try{
			$dbh = new PDO("mysql:host=localhost;dbname=luckytime_test", 'root', 'root');
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $dbh;
		} 	
		catch (PDOException $e){
			echo 'Connection failed: ' . $e->getMessage();
		}
		
	}
	
	function r_fb_sdk(){//Return FACEBOOK SDK!
		return $facebook = new Facebook(array('appId'  => '359940667387959', 'secret' => 'f3352efba1ed894865d29be1292c4bd3'));//Init Facebook SDK.;
	}
}

{/*ROUTING*/
	$app = new Slim();
	
	$app->get('bitch', function(){
		
		echo 'kk';
	});
	
	$app->get('/highscore', function (){
	    try{
			$mysqli = new mysqli("localhost", "root", "root", "luckytime_test");
			if($mysqli->connect_errno)
				throw new Exception('Something went wrong.');
			
			$resultset_highscore = $mysqli->query("SELECT fb_id, username, score FROM users ORDER BY score DESC LIMIT 0, 100");
			if(!$resultset_highscore)
				throw new Exception('Something went wrong.');
		
			$highscore_data_array = array();
			while($highscore_result = $resultset_highscore->fetch_assoc()){
				$highscore_data_array[] = $highscore_result;
			}	
		
			if(isset($_SESSION['userId'])){
				$resultset_highscore_rank = $mysqli->query('SELECT COUNT(*) AS rank FROM users WHERE score > (SELECT score FROM users WHERE id = '.$_SESSION['userId'].')');
				if(!$resultset_highscore_rank)
					throw new Exception('Something went wrong.');
			
				$rank_number = $resultset_highscore_rank->fetch_assoc();
				$data_array = array('rank' => ($rank_number['rank'] + 1), 'users' => $highscore_data_array);
			}
		
			else
				$data_array = array('users' => $highscore_data_array);
		
			$mysqli->close();
		
			echo json_encode($data_array);
		}
	
		catch(Exception $e){
			echo $e->getMessage();
		}
	});

	$app->get('/username', function (){
		/*TO-DO*/
		//VERIFY CONNECTION + QUERY ELSE THROW!
		//////////////////////////////////////
		/*SERDTYUYFUGB3
		EFH298FGO2I
		VG92OUV
		JJUVHUH*/
		if(isset($_GET['un'])){
			try{
				$em_ar = array('msg' => 0);
				$db_handle = r_db_conn();
				$resultset_free_username = $db_handle->query('SELECT id FROM users WHERE username = "'.$_GET['un'].'"');
				if($resultset_free_username->rowCount() > 0)
					$em_ar['msg'] = 'Username is not available!';//:@ Errorcode 1 | Username is not available!
				echo json_encode($em_ar);
			} 
		
			catch(PDOException $e){
				echo $e->getMessage();
			}
		}
		/*SERDTYUYFUGB3
		EFH298FGO2I
		VG92OUV
		JJUVHUH*/
	});

	$app->post('/user', function(){
		
		function parse_signed_request($signed_request, $secret){
			list($encoded_sig, $payload) = explode('.', $signed_request, 2);//Explode signed-request into encoded-real-fb-sig-key + data-payload.
			$sig = base64_url_decode($encoded_sig);//Decrypt data.
			$data = json_decode(base64_url_decode($payload), true);//Decode payload into JSON-string.
			if(strtoupper($data['algorithm']) !== 'HMAC-SHA256') {//Verify used decrypting-algorithm.
				error_log('Unknown algorithm. Expected HMAC-SHA256');
				return null;
			}
			
			$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);//Decode the real-fb-sig-key.
			if($sig !== $expected_sig) {//Check if used signature-key is valid.
				error_log('Bad Signed JSON signature!');
				return null;
			}
			return $data;
		}
		
		function base64_url_decode($input){//Returns adjusted string.
			return base64_decode(strtr($input, '-_', '+/'));
		}
		
		if($_REQUEST){//When request contains REQUEST-data.
			$s_r = parse_signed_request($_REQUEST['signed_request'], 'f3352efba1ed894865d29be1292c4bd3');//Parse 'signed_request' into working object.
			if($s_r['registration_metadata']['fields'] == "[{'name' : 'name'}, {'name' : 'username','type' : 'text', 'description' : 'Username'}]"){//Verify user registration data fields.
				
				#
				echo 'package safe!';
				print_r($s_r);
				#
				
				$fb = r_fb_sdk();
				if($fb){//When user is not authorized.
					try{
						//$username = $s_r['registration']['username'];
						$user_profile = $fb->api('/me');//Fetch user profile.
						
						#
						echo'Fetched user proile: <br />';
						print_r($user_profile);
						#
						
						
						
						$db_handle = r_db_conn();
						$resultset_fb_user = $mysqli->query('SELECT * FROM users WHERE fb_id = '.$user_profile['id']);
						
						//$username = $mysqli->real_escape_string($username);
						//When user is not registered -> insert new user in database -> set session.
						//echo 'Registered new user: '.$user_profile['id'];
						
						$resultset_user = $mysqli->query("INSERT INTO users VALUES ('', '".$user_profile['id']."', '".$username."', 0, 0, 5, 2, 1, 1, 1, 0, 0, 0, 0, ".time().", 100, 0)");
						$resultset_for_session_id = $mysqli->query('SELECT id FROM users WHERE fb_id = '.$user_profile['id']);
						$fetched_id = $resultset_for_session_id->fetch_assoc();
						session_regenerate_id();
						$_SESSION['userId'] = $fetched_id['id'];
						header('Location: http://127.0.0.1/teg/');
						//$facebook->api('/me/feed', 'POST', array('message' => 'Playing the easiest game in the world. http://theeasygame.com'));
					} 
					
					catch (FacebookApiException $e){//On Facebook error.
						$user = null;
					}
				}//else {GTFO}.
			}
			
			/*
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
			*/
		}
	});
	
}

$app->run();
?>