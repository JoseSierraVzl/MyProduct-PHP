<?php 
    session_start();

	require 'db/connection.php';
    require 'db/funcs.php';
	
    $errors = array();
    
      if(isset($_SESSION['id_user'])){
        header("location: welcome.php");
    }

    if(!empty($_POST)){

        $user = $mysqli->real_escape_string($_POST["user"]);
        $password = $mysqli->real_escape_string($_POST["password"]);

        if(isNUllLogin($user,$password)){
            $errors[] = "You must fill in all fields";
        }

        $errors[] = login($user, $password);
            
    }  

?>

<?php include 'include/header.php' ?>

<section class="m-0 vh-100 justify-content-center aling-items-center">
    <div class="background-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12 div-form-center">
                    <form class="form-login-style form text-white" method="POST" id="loginForm" role="form" action="<?php $_SERVER['PHP_SELF'] ?>">
                        <div class="">
                            <div>
                                <div class="text-center users-box colour-1 mb-4 p-2">
                                    <i class="fas fa-user users-icon"></i>

                                    <div class="text-center fw-bold">
                                        <p class="fs-3">Sign in</p>
                                        <p class="text-muted ">Login to continue</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="row">
                                    <div class=" icon-email-input col-md-1 col-1 text-center">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="col-md-11 col-10">
                                        <input class=" inputs-style mt-2 border-dark text-white f" type="text"
                                            placeholder="User" name="user" id="user" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="icon-password-input col-md-1 col-1 text-center">
                                        <i class="fas fa-unlock-alt"></i>
                                    </div>
                                    <div class="col-md-11 col-10">
                                        <input class="inputs-style mt-3 border-dark text-white" type="password"
                                            placeholder="Password" name="password" id="password" required>
                                    </div>
                                </div>

                            </div>
                                <div>                                     
                                   <?php  echo resultBlock($errors); ?>
                                </div>
                            <div class="d-grid gap-2 col-6 mx-auto mt-3 button-animation-up">
                                <button class="fs-6 mt-3  btn-lg background-1 button-form text-body" type="submit">LOGIN</button>
                            </div>
                            <div class="text-center mt-1">
                                <a class="colour-2 text-hover" href="recoverPassword.php">Recover password</a>
                            </div>
                            <div class="text-center mt-5 col-md-12 col-12 fs-6">
                                <p class="text-light">You don't have an account? <a href="sign_Up.php"
                                        class="colour-2 text-hover"> <br>Sign Up</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>	
	
</section>

<?php include 'include/footer.php' ?>








