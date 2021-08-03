<?php 
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	function isNUllLogin($user,$password){
		if(strlen(trim($user)) < 1 || strlen(trim($password)) < 1 ){
			return true;
		}else{
			return false; 
		}
	}

	function login($user, $password){

		global $mysqli;

		$stmt = $mysqli->prepare("SELECT id, id_type, password FROM users WHERE user = ? || email = ? LIMIT 1");
		$stmt->bind_param("ss", $user, $user);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;

		if($rows > 0){

			if(isActive($user)){

				$stmt->bind_result($id, $id_type, $pass);
				$stmt->fetch();

				$valPassword = password_verify($password, $pass);

				if($valPassword){

					lastSession($id);
					$_SESSION['id_user'] = $id;
					$_SESSION['user_type'] = $id_type;

					header("location: welcome.php");

				}else{
					$errors = "Password is incorrect";
				}	
			}else{
				$errors = "The user don't no active";
			}
		}else{
			$errors = "User or email does not exist";
		}

		return $errors;
	}

	function lastSession($id){

		global $mysqli;

		$stmt = $mysqli->prepare("UPDATE users SET last_session = NOW(), token_password='', password_request=1 WHERE id = ?");
		$stmt->bind_param('i',$id);
		$stmt->execute();
		$stmt->close();
	}

	function isActive($user){

		global $mysqli;

		$stmt = $mysqli->prepare("SELECT active FROM users WHERE user = ? || email = ? LIMIT 1");
		$stmt->bind_param("ss", $user, $user);
		$stmt->execute();
		$stmt->bind_result($active);
		$stmt->fetch();

		if($active == 1 ){

			return true;

		}else{

			return false;
		}
	}


	function isNull($user,$email,$password,$passwordRepeat){
		if(strlen(trim($user)) < 1 || strlen(trim($email)) < 1 || strlen(trim($password)) < 1  || strlen(trim($passwordRepeat)) < 1 ){
			return true;
		}else{
			return false;

		}
	}


	function isEmail($email){
		if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
			return true;

		}else{
			return false;
		}
	}

	function validatePassword($var1, $var2){
		if(strcmp($var1, $var2) != 0){
			return false;
		}else{
			return true;
		}
	}

	function minMax($min, $max, $valor){

		if(strlen(trim($value)) < $min){
			return true;
		}else if(strlen(trim($value)) > $max){
			return true;
		}else{
			return false;
		}
	}

	function resultBlock($errors){
		if(count($errors) > 0 ){
			echo"<div id='error' class='alert alert-danger p-4' role='alert'><a href='#' onclick=\"showHide('error');\">[x]</a><ul>";
			foreach($errors as $error){
				echo "<li>".$error."</li>";
			}
			echo "<ul>";
			echo "</ul>";
		}
	}


	function userExisted($user){
		global $mysqli;

		$stmt = $mysqli->prepare("SELECT id FROM users WHERE user = ? LIMIT 1");
		$stmt->bind_param('s',$user);
		$stmt->execute();
		$stmt->store_result();
		$num = $stmt->num_rows;
		$stmt->close();

		if($num > 0){
			return true;
		}else{
			return false;
		}
	}

	function emailExisted($email){
		global $mysqli;

		$stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
		$stmt->bind_param('s',$email);
		$stmt->execute();
		$stmt->store_result();
		$num = $stmt->num_rows;
		$stmt->close();

		if($num > 0){
			return true;
		}else{
			return false;
		}
	}


	function getValue($campo, $campoWhere, $valor){

		global$mysqli;

		$stmt = $mysqli->prepare("SELECT $campo FROM users WHERE $campoWhere = ? LIMIT 1");
		$stmt->bind_param('s', $valor);
		$stmt->execute();
		$stmt->store_result();
		$num = $stmt->num_rows;

		if($num > 0){

			$stmt->bind_result($_campo);
			$stmt->fetch();
			return $_campo;

		}else{

		}
	}

	function generateTokenPass($user_id){

		global $mysqli;

		$token = generateToken();

		$stmt = $mysqli->prepare("UPDATE users SET token_password = ?, password_request = 1  WHERE id = ?");
		$stmt->bind_param('ss', $token, $user_id);
		$stmt->execute();
		$stmt->close();

		return $token;

	}

	function validateTokenPass($user_id, $token){

		global $mysqli;

		$stmt = $mysqli->prepare("SELECT active FROM users WHERE id = ? AND token_password = ? AND password_request = 1 LIMIT 1");
		$stmt->bind_param('is', $user_id ,$token);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;

		if($rows > 0){

			$stmt->bind_result($active);
			$stmt->fetch();
			if($active == 1 ){
				return true;
			}else{
				return false; 
			}
		}
	}

	function generateToken(){
		$gen = md5(uniqid(mt_rand(),false));
		return $gen;
	}

	function validateIdToken($id,$token){

		global $mysqli;
		
		$stmt = $mysqli->prepare("SELECT active FROM users WHERE id = ? AND token = ?  LIMIT 1");
		$stmt->bind_param("is",$id,$token);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;

		if ($rows > 0) {
			$stmt->bind_result($active);
			$stmt->fetch();

			if($active == 1 ){
				$msg = "La cuenta ya se activo  anteriormente.";

			}else{
				if(activeUser($id)){
					$msg = "Cuenta activada.";

				}else{
					$msg = "Error a la activar la cuenta.";
				}
			}
		}else{
			$msg = "No existe el registro para activar";
		}
		return $msg;
	
	}

	function activeUser($id){
		global $mysqli;

		$stmt = $mysqli->prepare("UPDATE users  SET active=1 WHERE id=?");
		$stmt->bind_param('s',$id);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}

	
	function hashPassword($password){
		$hash = password_hash($password, PASSWORD_DEFAULT);
		return $hash;
	}


	function newPassword($password, $user_id, $token){

		global $mysqli;

		$stmt = $mysqli->prepare("UPDATE users SET password = ?, token_password = '', password_request = 0 WHERE id = ? AND token_password = ?");
		$stmt->bind_param('sis', $password, $user_id, $token);

		if($stmt->execute()){
			return true;
		}else{
			false;
		}
	}

	function registerUser($user,$email, $passwordHash, $active, $token, $userType){

		global $mysqli;

		$stmt = $mysqli->prepare("INSERT  INTO users (user, email, password, active, token, id_type) VALUES(?,?,?,?,?,?)");
		$stmt->bind_param('sssisi',$user,$email, $passwordHash, $active, $token, $userType);

		if($stmt->execute()){
			return $mysqli->insert_id;

		}else{
			return 0;
		}
	}


	function sendEmail($email,$user,$matter,$content){
		require_once 'PHPMailer-master/src/PHPMailer.php';
		require_once 'PHPMailer-master/src/SMTP.php';
		require_once 'PHPMailer-master/src/Exception.php';

		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'tls';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = '587';

    	$mail->Username = 'sierramendezjosealejandro@gmail.com';      
    	$mail->Password = '*12*jose*34*'; 

    	$mail->setFrom('sierramendezjosealejandro@gmail.com', 'Sistema de usuario');
    	$mail->addAddress($email,$user);

    	$mail->Subject = $matter;
    	$mail->Body = $content;
    	$mail->isHTML(true);

    	if($mail->send()){
    		return true;

    	}else{
    		return false; 
    	}

	}

	function newProduct($nameProduct,$idUser,$brand, $model, $category, $condition,
	    $support, $colors, $price, $description){

		global $mysqli;

		$product = selectProduct($nameProduct,$idUser, $category);

		echo "Esto recive: $product";

		if($product > 0){

            $stmt = $mysqli->prepare("INSERT INTO product_features (colors, codition, price, brand, model, support, description, id_product) VALUES (?,?,?,?,?,?,?,?)");

			$stmt->bind_param('ssdssssi', $colors, $condition, $price, $brand, $model, $support, $description, $product);

                if($stmt->execute()){

                    header ("location: welcome.php");

                }else{

                    $errors = "Error al registrar producto";
                }
		}

		return $errors;

	}



	function compareCategory($category){

		global $mysqli;
		$stmt = $mysqli->prepare("SELECT id FROM category WHERE name = ? LIMIT 1");
		$stmt->bind_param("s", $category);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;

		if ($rows > 0) {
			$stmt->bind_result($categ);
			$stmt->fetch();
			echo "Esta es la categoria: $categ";
			return $categ;
		}else{
			return 0;
		}
	}

	function selectProduct($nameProduct,$idUser, $category){

		global $mysqli;

		$id_category =  compareCategory($category);

			if($id_category > 0 ){

                $stmt = $mysqli->prepare("INSERT INTO product (name, id_users, id_category) VALUES (?,?,?)");
            	$stmt->bind_param('sii', $nameProduct, $idUser, $id_category);
             	if($stmt->execute()){

     				echo "Este es el id del producto: ".$mysqli->insert_id;

     				$id = $mysqli->insert_id;

	                return $id;

	            }else{
	                return 0;
	            }

			}

	}



	function editProduct($id){
		global $mysqli;

		if($id > 0){

		    $sql = "SELECT * FROM product_features INNER JOIN product ON product_features.id_product = product.id WHERE product.id_users = $id;";

			$result = $mysqli->query($sql);
			
		    echo "Si recibio";

			return $result;
	    
		}


	}

	function selectProducts($idUser){

		global $mysqli;

		if($idUser > 0){

		    $sql = "SELECT * FROM product_features INNER JOIN product ON product_features.id_product = product.id WHERE product.id_users = $idUser;";

		    $result = $mysqli->query($sql);


			return $result;

		}else{
			
			return 0;
		}

	}





?>