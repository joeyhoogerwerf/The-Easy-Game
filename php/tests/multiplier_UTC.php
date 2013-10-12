<?php
$itemCounter = 0;

$arrMultiplier5 = array('00', '11', '22', '33', '44', '55');//
$arrMultiplier15 = array('000', '111', '222', '333', '444', '555');//
$arrMultiplier25 = array('012', '123', '234', '345', '456');//
$arrMultiplier40 = array('0000', '1111', '2222', '3333', '4444', '5555');//
$arrMultiplier50 = array('0123', '1234', '2345', '3456');//
$arrMultiplier70 = array('00000', '11111', '22222', '33333', '44444', '55555');//
$arrMultiplier80 = array('01234', '12345', '23456');//
$arrMultiplier100 = array('010101', '020202', '030303', '040404', '050505', '060606', '070707', '080808', '090909', '101010', '121212', '131313', '141414', '151515', '161616', '171717', '181818', '191919', '202020', '212121', '232323');//
$arrMultiplier125 = array('000000', '111111', '222222', '112233', '223344');//
$arrMultiplier150 = array('123456');//

$arrays = array($arrMultiplier5, $arrMultiplier15, $arrMultiplier25, $arrMultiplier40, $arrMultiplier50, $arrMultiplier70, $arrMultiplier80, $arrMultiplier100, $arrMultiplier125, $arrMultiplier150);

foreach($arrays as $array){
	foreach($array as $item){
		$itemCounter++;
	}
}

//echo 'Items: '.$itemCounter.'<br />';

//Midnight, 00:00:00
$timestamp = 1335916800;

//echo 'Starttime: '.gmdate('H:i:s', $timestamp);

$ao_multipliers = 0;


function calculateMultiplier($tS){
	
	$arrMultiplier5 = array('00', '11', '22', '33', '44', '55');//
	$arrMultiplier15 = array('000', '111', '222', '333', '444', '555');//
	$arrMultiplier25 = array('012', '123', '234', '345', '456');//
	$arrMultiplier40 = array('0000', '1111', '2222', '3333', '4444', '5555');//
	$arrMultiplier50 = array('0123', '1234', '2345', '3456');//
	$arrMultiplier70 = array('00000', '11111', '22222', '33333', '44444', '55555');//
	$arrMultiplier80 = array('01234', '12345', '23456');//
	$arrMultiplier100 = array('010101', '020202', '030303', '040404', '050505', '060606', '070707', '080808', '090909', '101010', '121212', '131313', '141414', '151515', '161616', '171717', '181818', '191919', '202020', '212121', '232323');//
	$arrMultiplier125 = array('000000', '111111', '222222', '112233', '223344');//
	$arrMultiplier150 = array('123456');//
	
	$h = gmdate('H', $tS);
	$m = gmdate('i', $tS);
	$s = gmdate('s', $tS);
	
	//echo $s;
	
	foreach($arrMultiplier150 as $mp150){
		if($h.$m.$s == $mp150)
			return 150;
	}
	
	foreach($arrMultiplier125 as $mp125){
		if($h.$m.$s == $mp125)
			return 125;
	}
	
	foreach($arrMultiplier100 as $mp100){
		if($h.$m.$s == $mp100)
			return 100;
	}
	
	foreach($arrMultiplier80 as $mp80){
		if(implode('', array(substr($h, 1), $m, $s)) == $mp80)
			return 80;
	}
	
	foreach($arrMultiplier70 as $mp70){
		if(implode('', array(substr($h, 1), $m, $s)) == $mp70)
			return 70;
	}
	
	foreach($arrMultiplier50 as $mp50){
		if($m.$s == $mp50)
			return 50;
	}
	
	foreach($arrMultiplier40 as $mp40){
		if($m.$s == $mp40)
			return 40;
	}
	
	foreach($arrMultiplier25 as $mp25){
		if(implode('', array(substr($m, 1), $s)) == $mp25)
			return 25;
	}
	
	foreach($arrMultiplier15 as $mp15){
		if(implode('', array(substr($m, 1), $s)) == $mp15)
			return 15;
	}
	
	foreach($arrMultiplier5 as $mp5){
		if($s == $mp5){
			return 5;
		}
	}

	return 1;
}

for($i = 0; $i < 86400; $i++){

	$multiplier = calculateMultiplier($timestamp);
	
	if($multiplier > 1){
		$ao_multipliers++;
		echo '<span style="font-size: 8pt;">Time: '.gmdate('H:i:s', $timestamp).' | Multiplier: '.$multiplier.'</span><br />';
	}
			
	$timestamp++;
}

echo 'Amount of multipliers detected: '.$ao_multipliers;
?>