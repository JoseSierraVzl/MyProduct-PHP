<?php 

    session_start();

	require 'db/connection.php';
    require 'db/funcs.php';

    $errors = array();


    if(!empty($_POST)){

	    if(isset($_SESSION['id_user'])){
	    	$idUser = $_SESSION['id_user'];
	      }

	    $nameProduct = $mysqli->real_escape_string($_POST["nameProduct"]);
	    $brand = $mysqli->real_escape_string($_POST["brand"]);
	    $model = $mysqli->real_escape_string($_POST["model"]);
	    $category = $mysqli->real_escape_string($_POST["category"]);
	    $condition = $mysqli->real_escape_string($_POST["condition"]);
	    $support = $mysqli->real_escape_string($_POST["support"]);
	    $colors = $mysqli->real_escape_string($_POST["colors"]);
	    $price = $mysqli->real_escape_string($_POST["price"]);
	    $description = $mysqli->real_escape_string($_POST["description"]);


	    $savedProduct = newProduct($nameProduct,$idUser,$brand, $model, $category, $condition,
	    $support, $colors, $price, $description);

	    $errors[] = $savedProduct;



    }  

?>

<?php include 'include/header.php' ?>

<section class="m-0 vh-100 justify-content-center aling-items-center">
	<div class="container">
		<div class="row">
			<div class=" div-form-center p-4">
				<div class="col-md-12  form-login-style">
                    <form method="POST" id="loginForm" role="form" action="<?php $_SERVER['PHP_SELF'] ?>">
					<div class="p-4">
						<div class="row">
							<div class="col-md-6">
								<input type="text" placeholder="Nombre del producto" name="nameProduct" class=" inputs-style mt-2 border-dark text-white f">
							</div>
							<div class="col-md-6 ">
								<input type="text" placeholder="Marca" name="brand" class=" inputs-style mt-2 border-dark text-white f">								
							</div>
								<div class="col-md-6 ">
								<input type="text" placeholder="Modelo" name="model" class=" inputs-style mt-2 border-dark text-white f">								
							</div>
								<div class="col-md-6 mt-4">
								<select  class="form-select" name="category">
								  <option selected>Seleccion una categoría</option>
								  <option value="Informatica">Informatica</option>
								  <option value="Electronica">Electronica</option>
								  <option value="Salud">Salud</option>
								</select>
							</div>

							<div class=" col-md-6 mt-4 text-white">

							<label>Condicion del producto</label>								

							<div class="form-check">

						  <input class="form-check-input" type="radio" name="condition" value="Nuevo">
						  <label class="form-check-label" for="flexRadioDefault1">
						    Nuevo
						  </label>
						</div>
						<div class="form-check">
						  <input class="form-check-input" type="radio" name="condition" value="Usado" >
						  <label class="form-check-label" for="flexRadioDefault2">
						    Usado
						  </label>
						</div>
							</div>

							<div class=" col-md-6 mt-4 text-white">

							<label>¿Posee soporte?</label>								

							<div class="form-check">

						  <input class="form-check-input" type="radio" name="support" value="Si">
						  <label class="form-check-label" for="flexRadioDefault1">
						    Si
						  </label>
						</div>
						<div class="form-check">
						  <input class="form-check-input" type="radio" name="support" value="No" >
						  <label class="form-check-label" for="flexRadioDefault2">
						    No
						  </label>
						</div>
							</div>
							<div class="col-md-6">
								<input type="text" placeholder="Colores existentes del producto" name="colors" class=" inputs-style mt-2 border-dark text-white f">
							</div>
							<div class="col-md-6">
								<input type="number" step="any" placeholder="Precio" name="price"class=" inputs-style mt-2 border-dark text-white f">
							</div>

							<div class="col-md-12 mt-4 ">
								<label class="text-white">Descripción del producto</label>
								<textarea name="description"  rows="10" class="form-control">
									
								</textarea>
							</div>

							<div class="col-md-12 mt-4">
								<button class="btn btn-dark" type="submit" >Guardar producto</button>
								
							</div>
							<div >
                                 <?php  echo resultBlock($errors); ?>
                            </div>

						</div>
					</div>					
				</form>
				</div>
			</div>
		</div>
	</div>
</section>

<?php include 'include/footer.php' ?>

