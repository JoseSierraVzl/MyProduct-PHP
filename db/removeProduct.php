<?php 
	require 'connection.php';


	global $mysqli;


	if(isset($_POST['id'])){
		$id = $_POST['id'];
	}else{
		echo "No hay id";
	}

	echo "Este es el id: $id";
	$sql = "DELETE FROM product WHERE product.id = $id;";

	$query = $mysqli->query($sql);

	if($query){

	    echo"1 row delete";
	}else{
		echo "Error al eliminar";
	}







?>