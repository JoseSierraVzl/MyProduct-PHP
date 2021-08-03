<?php 

    require 'db/connection.php';
    require 'db/funcs.php';

    $user_id = $mysqli->real_escape_string($_POST['user_id']);
	$token = $mysqli->real_escape_string($_POST['token']);
	$password = $mysqli->real_escape_string($_POST['password']);
	$passwordRepeat = $mysqli->real_escape_string($_POST['passwordRepeat']);


	if(validatePassword($password, $passwordRepeat)){

		$pass_hash = hashPassword($password);

		if(newPassword($pass_hash, $user_id, $token)){

			echo "Password changed successfully";

			echo "<br> <a href='index.php'>Iniciar Session</a>";


		}else{
			echo "Error al modificar password";
		}
	}else{
		echo "Password no coincide";
	}


?>