<?php
session_start();
if(isset($_SESSION['userId'])){	
	try {  
		$db_handle = new PDO("mysql:host=localhost;dbname=luckytime_test", 'root', 'root');
		$resultset_last_clicked_clicks = $db_handle->query('SELECT click_id, user_id, time, click_score FROM clicked_clicks ORDER BY click_id DESC LIMIT 0, 10');  
		$resultset_last_clicked_clicks->setFetchMode(PDO::FETCH_ASSOC);  
		echo json_encode($resultset_last_clicked_clicks->fetchAll());
	}  
	
	catch(PDOException $e) {  
	    echo $e->getMessage();  
	}
}
?>