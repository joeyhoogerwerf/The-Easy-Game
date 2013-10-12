<?php
session_start();

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
		
		echo json_encode($data_array);
		
		$mysqli->close();
	}
	
	catch(Exception $e){
		echo $e->getMessage();
	}
?>