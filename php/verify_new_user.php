<?php
/*TO-DO*/
//VERIFY CONNECTIO + QUERY ELSE THROW!
//////////////////////////////////////
/*SERDTYUYFUGB3
EFH298FGO2I
VG92OUV
JJUVHUH*/
if(isset($_GET['un'])){
	try{
		$db_handle = new PDO("mysql:host=localhost;dbname=luckytime_test", 'root', 'root');
		$resultset_free_username = $db_handle->query('SELECT id FROM users WHERE username = "'.$_GET['un'].'"'); 
		if($resultset_free_username->rowCount() > 0)
			echo json_encode(array('msg' => 'Username not available.'));
	}  
	catch(PDOException $e){  
	    echo $e->getMessage();  
	}
}
/*SERDTYUYFUGB3
EFH298FGO2I
VG92OUV
JJUVHUH*/
?>