<?php 

    require 'db/connection.php';
    require 'db/funcs.php';

    if(empty($_GET['user_id'])){
    	header('location: index.php');
    }

    if(empty($_GET['token'])){
    	header('location: index.php');
    }


    $user_id = $mysqli->real_escape_string($_GET['user_id']);
	$token = $mysqli->real_escape_string($_GET['token']);

	if(!validateTokenPass($user_id, $token)){

		echo 'No se pudo verificar los datos';
		exit;

	}


?>


<?php include 'include/header.php' ?>

<div class="m-0 vh-100 justify-content-center aling-items-center">
		

    <div class="background-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12 div-form-center">
                    <form class="form-login-style form text-white" action="saved_pass.php" method="POST">

                 		<input type="hidden" name="user_id" value="<?php  echo $user_id; ?>">
                 		<input type="hidden" name="token" value="<?php  echo $token; ?>">
                        <div class="">
                            <div>
                                <div class="text-center users-box colour-1 mb-4 p-2">
                                    <i class="fas fa-unlock-alt users-icon"></i>

                                    <div class="text-center  fw-bold ">
                                        <p class="fs-3 ">Restore Password
                                        </p>
                                        <p class="text-muted">Enter a new password to continue</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="row">
                                    <div class="icon-password-input col-md-1 col-1 text-center">
                                        <i class="fas fa-unlock-alt"></i>
                                    </div>
                                    <div class="col-md-11 col-11">
                                        <input class="inputs-style mt-3 border-dark text-white" type="password"
                                            placeholder="Password" name="password" required>
                                    </div>
                                    <div class="icon-password-input col-md-1 col-1 text-center">
                                        <i class="fas fa-unlock-alt"></i>
                                    </div>
                                    <div class="col-md-11 col-11">
                                        <input class="inputs-style mt-3 border-dark text-white" type="password"
                                            placeholder="Repeat password" name="passwordRepeat" required>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <p>Enter a new password.</p>
                                </div>
                                <div class="d-grid gap-2 col-6 mx-auto mt-1 button-animation-up ">
                                    <button class="fs-6 mt-3  btn-lg background-1 button-form text-body">RESTORE</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer.php' ?>



