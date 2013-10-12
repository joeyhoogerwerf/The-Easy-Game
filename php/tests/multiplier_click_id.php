<?php

//Calculate multiplier depending on current time.
function calculateMultiplierClick_id($click_id){
	
	$arrMultiplier5 = array('00', '11', '22', '33', '44', '55', '66', '77', '88', '99');
	$arrMultiplier15 = array('000', '111', '222', '333', '444', '555', '666', '777', '888', '999');
	$arrMultiplier25 = array('012', '123', '234', '345', '456', '567', '678', '789');
	
	$length_click_id = strlen($click_id);
	
	//echo 'Length click_id: '.$length_click_id;

	//When click_id has 3 numbers.
	if($length_click_id == 3){

		//Find match -> on match return multiplier.
		foreach($arrMultiplier25 as $mp25){
			if($mp25 == substr($click_id, ($length_click_id - 3)))
				return 25;
		}

		//Find match -> on match return multiplier
		foreach($arrMultiplier15 as $mp15){
			if($mp15 == substr($click_id, ($length_click_id - 3)))
				return 15;
		}
	}
	
	//When click_id has 2 numbers.
	elseif($length_click_id == 2){
	
		//Find match -> on match return multiplier.
		foreach($arrMultiplier5 as $mp5){
			if($mp5 == substr($click_id, ($length_click_id - 2)))
				return 5;
		}
	}
		
	//Find match -> on match return multiplier.
	foreach($arrMultiplier5 as $mp5){
		if($mp5 == substr($click_id, ($length_click_id - 2)) && $length_click_id >= 2)
			return 5;
	}
		
	return 1;
}




for($i = 0; $i < 1000; $i++){
	echo 'Click_id: '.$i.' | Score: '.calculateMultiplierClick_id((string)$i).'<br />';
}

?>