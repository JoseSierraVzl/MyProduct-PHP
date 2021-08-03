<?php 
    require 'db/connection.php';
    require 'db/funcs.php';
    session_start();

    $errors = array();


    if(isset($_SESSION['id_user'])){
        header("location: welcome.php");
    }

    if (!empty($_POST)) {

        $user = $mysqli->real_escape_string($_POST["user"]);
        $email = $mysqli->real_escape_string($_POST["email"]);
        $password = $mysqli->real_escape_string($_POST["password"]);
        $passwordRepeat = $mysqli->real_escape_string($_POST["passwordRepeat"]);
        $captcha = $mysqli->real_escape_string($_POST["g-recaptcha-response"]);

        $active = 0;
        $userType = 2;
        $secret = "6LeXzcQbAAAAAH8JTQQwswa_QTrHmIc1aer6cixG";

        if(!$captcha){
            $errors[] = "Please check the Captcha";
        }

        if(isNull($user,$email,$password, $passwordRepeat)){
            $errors[] = "You must fill in all fields";
        }

        if(!isEmail($email)){
            $errors[] = "Direction email invalide";
        }
        if(!validatePassword($password,$passwordRepeat)){
            $errors[] = "The password do not coincide";
        }
        if(userExisted($user)){
            $errors[] = "The user $user already exists";
        }
        if(emailExisted($email)){
            $errors[] = "The email $email already exists";
        }

        if(count($errors) == 0){

            $response = curl_init("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha");
            curl_setopt($response, CURLOPT_RETURNTRANSFER, true);

            $verificationCaptcha = curl_exec($response);
            $arr=json_decode($verificationCaptcha,TRUE);
    
            //$arr = json_decode($response,TRUE);

            if($arr['success']){

                $passwordHash = hashPassword($password);
                $token = generateToken();

                $register = registerUser($user,$email, $passwordHash, $active, $token, $userType);

                if($register > 0){

                    $url = 'http://'.$_SERVER['SERVER_NAME'].'/MyProduct/activate.php?id='.$register.'&val='.$token;

                    $matter = 'MyProduct Activar cuenta - Sistema de usuario';
                    $content = "Estimado $user: <br/> <br/> Para continuar con el proceso de registro, es indispensable que de click en la  siguiente ruta: <a href='$url'>Activar cuenta</a>";

                    if(sendEmail($email,$user,$matter,$content)){

                        echo "Para terminar con el proceso de registro siga las intrucciones que le hemos enviado a la direccion de correo electronico: $email";

                        echo "<br><a href='index.php'>Iniciar Sesion</a>";

                        exit;

                    }else{
                        $errors[] = "Error sending email";
                    }

                }else{
                    $errors[] = "Error registering";
                }

            }else{
                $errors[] = "Error checking captcha";
            }

        }else{

        }


    }

?>

<?php include 'include/header.php' ?>

<section class="m-0 vh-100 justify-content-center aling-items-center"> 
    <div class="background-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12 p-4 div-form-center">
                    <form class="form-login-style form text-white" id="formSignUp" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                        <div class="">
                            <div>
                                <div class="text-center users-box colour-1 mb-4 p-2">
                                    <i class="fas fa-user users-icon"></i>

                                    <div class="text-center  fw-bold ">
                                        <p class="fs-3 ">Sign Up</p>
                                        <p class="text-muted">Register to continue</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="row">
                                    <div class=" icon-email-input col-md-1 col-1 text-center">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="col-md-11 col-11">
                                        <input class=" inputs-style mt-2 border-dark text-white f" type="text"
                                            placeholder="User" name="user" required>
                                    </div>
                                    <div class=" icon-email-input col-md-1 col-1 text-center">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="col-md-11 col-11">
                                        <input class=" inputs-style mt-2 border-dark text-white f" type="email"
                                            placeholder="Email" name="email" required>
                                    </div>
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
                                    
                                    <div class="mt-4">
                                        <div class="g-recaptcha" data-sitekey="6LeXzcQbAAAAAIGvK7wP-vvKz9RTIiv7qxTKoG82">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div >
                                 <?php  echo resultBlock($errors); ?>
                            </div>


                            <div class="p-4 col-md-12 col-12">
                                <label class="text-light"><input type="checkbox" id="cbox1" value="first_checkbox"> By
                                    signing up you accept the <a href="#" class="colour-2 text-hover"> Term Of service
                                        and Privacy Policy</a></label><br>
                            </div>

                            <div class="d-grid gap-2 col-6 mx-auto mt-1 button-animation-up">
                                <button class="fs-6 mt-1 btn-lg background-1 button-form text-body" type="submit">Sing Up</button>
                            </div>
                            <div class="text-center mt-2 col-md-12 col-12 fs-6 text-light">
                                <p>Already have an account?<a href="index.php" class="colour-2 text-hover"> <br> Sign In</a>
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




