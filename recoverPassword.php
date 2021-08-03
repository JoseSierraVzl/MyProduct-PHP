<?php 

    
    require 'db/connection.php';
    require 'db/funcs.php';

    $errors = array();

    if(!empty($_POST)){
        $email = $mysqli->real_escape_string($_POST['email']);

        if(!isEmail($email)){
            $errors[] = 'Direction email invalide';
        }       
        if(emailExisted($email)){

            $user_id = getValue('id', 'email', $email);
            $user = getValue('user', 'email', $email);

            $token = generateTokenPass($user_id);

            $url = 'http://'.$_SERVER['SERVER_NAME'].'/MyProduct/new_passwrod.php?user_id='.$user_id.'&token='.$token;
            $matter = 'MyProduct Recuperar Password - Sistema de usuario';
            $content = "Hola $user: <br/> <br/> Para restaurar su contraseña en necesario que de click al siguiente enlace: <a href='$url'>Cambiar contraseña</a>";
    
            if(sendEmail($email, $user, $matter, $content)){

                echo "Se ha enviando un correo electronico a la direccion $email para restablecer password. </br>";
                echo "<a href='index.php'>Iniciar Sesion </a>";
                exit;

            }else{
                    $errors[] = "Error sending email";
            }

        }else{
            $errors[] = "The email $email does not exist";
        }
    }
?>

<?php include 'include/header.php' ?>

<div class="m-0 vh-100 justify-content-center aling-items-center">        
    <div class="background-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12 div-form-center">
                    <form class="form-login-style form text-white" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                        <div class="">
                            <div>
                                <div class="text-center users-box colour-1 mb-4 p-2">
                                    <i class="fas fa-unlock-alt users-icon"></i>

                                    <div class="text-center  fw-bold ">
                                        <p class="fs-3 ">Rocover password
                                        </p>
                                        <p class="text-muted">Enter your email to continue</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="row">
                                    <div class=" icon-email-input col-md-1 col-1 text-center">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="col-md-11 col-10">
                                        <input class=" inputs-style mt-2 border-dark text-white f" type="email"
                                            placeholder="Email"  name="email" required>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <p>Enter the email address you used to create your account.</p>
                                </div>
                                <div class="d-grid gap-2 col-6 mx-auto mt-1 button-animation-up ">
                                    <button class="fs-6 mt-3  btn-lg background-1 button-form text-body" type="submit">RECOVER</button>
                                </div>
                                   <div class="mt-3">
                                        <?php  echo resultBlock($errors); ?>
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



