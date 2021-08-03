<?php 

	$mysqli = new mysqli('localhost', 'root', '', 'MyProduct');

	if(mysqli_connect_errno()){
		echo 'Fail the connection: ',mysqli_connect_errno();
		exit();
	}

?>