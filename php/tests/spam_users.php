<?php
//Open new DB-connection.
$mysqli = new mysqli("localhost", "root", "root", "luckytime_test");

$usernames_array = array('Zezima', 'Masterdamus', 'phr33st00f', 'gr0009dke', 'polskii22', 'pir920ppp', '0092uwo');

for ($i=0; $i < 300; $i++) { 
	$mysqli->query("INSERT INTO users VALUES ('', '', '".$usernames_array[rand(0, 6)]."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ".rand(0, 15000).", 0, 0, 0)");
}
?>