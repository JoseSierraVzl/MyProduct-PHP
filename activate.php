<?php 

    require 'db/connection.php';
    require 'db/funcs.php';

    if(isset($_GET['id']) AND isset($_GET['val'])){
    	$idUser = $_GET['id'];
    	$token = $_GET['val'];

    	$message = validateIdToken($idUser, $token);
    }

?>

<?php include 'include/header.php' ?>


<div class="container">
	<div class="jumbotron">
		<h1><?php  echo $message; ?></h1>
		<br>
		<p><a class="btn btn-primary btn-lg" role="button" href="index.php">Iniciar Sesi&oacute;n</a></p>
	</div>
</div>


<?php include 'include/footer.php' ?>

