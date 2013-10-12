<?php
session_start();
$fresh_timestamp = time();

//When user is logged in.
if(isset($_SESSION['userId'])){
	
	try{
		$nr_of_spawned_clicks = 0;
		$clickspawn_rate = 100;
		$clickspawn_rate_MIN = 3;
		$clickspawn_rate_MAX = 30;
		$ao_clickspawns = 0;
		$ao_clickTurns_MAX = 3;
		$inventory_is_full = false;
		$luckyNr = 1;
		$elapsed_time = 0;
		
		$winningClickTypesArray = array();
		
		$mysqli = new mysqli("localhost", "root", "root", "luckytime_test");//Open new DB-connection.
		if($mysqli->connect_errno)	throw new Exception('Something went wrong.');/*VERIFY DB-CONNECTION*/
		
		$resultset_tao_clickTurns = $mysqli->query('SELECT ao_black_clicks, ao_grey_clicks, ao_blue_clicks, ao_strawberry_clicks, ao_apple_clicks, ao_sun_clicks, ao_haze_clicks, ao_white_clicks, (ao_black_clicks + ao_grey_clicks + ao_blue_clicks + ao_strawberry_clicks + ao_apple_clicks + ao_sun_clicks + ao_haze_clicks + ao_white_clicks) AS "tao_clickTurns" FROM users WHERE id = '.$_SESSION['userId']);
		
		/*VERIFY QUERY*/
		if(!$resultset_tao_clickTurns) throw new Exception('Something went wrong.');
		
		$tao_clickturns = $resultset_tao_clickTurns->fetch_assoc();
		
		if($tao_clickturns['tao_clickTurns'] < $ao_clickTurns_MAX){//Check if user is allowed to get a new click.
			
			$r1 = $mysqli->query('SELECT timestamp_last_clickspawn, clickspawn_rate FROM users WHERE id = '.$_SESSION['userId']);//Select data from user.
			
			/*VERIFY QUERY*/
			if(!$r1) throw new Exception($mysqli->error);
			
			$r1_arr = $r1->fetch_assoc();
			
			$clickspawn_rate = $r1_arr['clickspawn_rate'];//Set variables
			
			$elapsed_time = (($fresh_timestamp - $r1_arr['timestamp_last_clickspawn'])/* + $r1_arr['clickspawn_buffer']*/);//Get elapsed time since last-clickspawn.
			
			if($elapsed_time >= $clickspawn_rate){//When waiting time has elapsed -> spawn new click/clicks.
				
				if($elapsed_time > $clickspawn_rate_MAX)
					$clickspawn_rate = $clickspawn_rate_MAX;
				
				$ao_clickspawns = floor($elapsed_time / $clickspawn_rate);//Calculate amount of clicks to spawn.
				$elapsed_time = 0;//Reset elapsed time.
				
				$clickspawn_rate = mt_rand($clickspawn_rate_MIN, $clickspawn_rate_MAX);//Random choose new spawn-rate.

				for($nr_of_spawned_clicks; $nr_of_spawned_clicks < $ao_clickspawns; $nr_of_spawned_clicks++){//For each clickspawn	.
					
					if(($tao_clickturns['tao_clickTurns'] + $nr_of_spawned_clicks) < $ao_clickTurns_MAX){//Check each loop if user is allowed to get a new click.
						
						if(($tao_clickturns['tao_clickTurns'] + $nr_of_spawned_clicks) == ($ao_clickTurns_MAX - 1)){
							$inventory_is_full = true;
						}
						
						$winningClickType = 'black';//Initialize default winning-click-type.
						
						if(mt_rand(0, 10000) == $luckyNr) $winningClickType = 'white';
						
						elseif(mt_rand(0, 5000) == $luckyNr) $winningClickType = 'haze';
						
						elseif(mt_rand(0, 2500) == $luckyNr) $winningClickType = 'haze';
						
						elseif(mt_rand(0, 1000) == $luckyNr) $winningClickType = 'gold';
						
						elseif(mt_rand(0, 500) == $luckyNr) $winningClickType = 'apple';
						
						elseif(mt_rand(0, 100) == $luckyNr) $winningClickType = 'strawberry';
						
						elseif(mt_rand(0, 25) == $luckyNr) $winningClickType = 'blue';
						
						elseif(mt_rand(0, 5) == $luckyNr) $winningClickType = 'grey';
						
						$winningClickTypesArray[] = $winningClickType;
												
						$mysqli->query('UPDATE users SET ao_'.$winningClickType.'_clicks = (ao_'.$winningClickType.'_clicks + 1) WHERE id = '.$_SESSION['userId']);//Increment winning DB-field.
					}
					
					else{
						$inventory_is_full = true;
						break;
					}
					
					$mysqli->query('UPDATE users SET timestamp_last_clickspawn = '.$fresh_timestamp.', clickspawn_rate = '.$clickspawn_rate.' WHERE id = '.$_SESSION['userId']);
				}	
			}
		}
		
		else $inventory_is_full = true;
		
		echo json_encode(array('timeTillNextClickspawn' => ($clickspawn_rate - $elapsed_time), 'winningClickTypesArray' => $winningClickTypesArray, 'inventoryIsFull' => $inventory_is_full, 'nrOfSpawnedClicks' => $nr_of_spawned_clicks, 'elapsedTime' => $elapsed_time));
		
		$mysqli->close();
	}
	
	catch(Exception $e){
		echo $e->getMessage();
	}
}
?>