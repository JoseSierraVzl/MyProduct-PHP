<?php 
	session_start();
	require 'db/connection.php';
  require 'db/funcs.php';


    if(!isset($_SESSION['id_user'])){
    	header("location: index.php");
    }

    $idUser = $_SESSION['id_user'];



    $sql = "SELECT id, user FROM users WHERE id = '$idUser' ";

    $result = $mysqli->query($sql);

    $row = $result->fetch_assoc();

    $products = selectProducts($idUser);





?>
<?php include 'include/header.php' ?>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container ">
    <a class="navbar-brand" href="#">MyProduct</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end " id="navbarNavAltMarkup">
        <ul class="navbar-nav ">
                    <li  class="navbar-text">
          <span>
            <?php echo utf8_decode($row['user']); ?>
          </span>
          </li>
          <?php if($_SESSION['user_type'] == 1){ ?>
            <li class="nav-item">
                <a class="nav-link" href="#">Manage users</a>         
            </li>
          <?php } ?>  

          <li class="nav-item">
            <a class="nav-link" href="logout.php">Cerrar sesión</a>
          </li>
        </ul>
    </div>
  </div>
</nav>


<section>
	<div class="jumbotron jumbotron-fluid">
  <div class="container text-center p-4">
    <h1 class="display-4"><?php echo 'Bienvenido '.utf8_decode($row['user']); ?></h1>
    <p class="lead">Un gusto tenerte aquí</p>
    <h3 class="">¿Que quieres hacer hoy?</h3>
    <div class="p-4 d-grid gap-2 d-md-block">

    <a class="btn btn-dark" href="new_product.php">Agregar nuevo producto</a>
    <a class="btn btn-dark">Ver mis productos</a>
      
    </div>
  </div>
</div>
</section>

<main>
    <?php if($products){ ?>
    <div class="container-fluid bg-trasparent my-4 p-3" style="position: relative;">
        <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-4 g-3">
              <?php $n=0; while($row = mysqli_fetch_array($products)){ $n++; ?>
                    <?php $_SESSION['id_product'] = $row['id']; ?>
            <div class="col">
                <div class="card h-100 shadow-sm"> <img src="https://www.freepnglogos.com/uploads/notebook-png/download-laptop-notebook-png-image-png-image-pngimg-2.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <div class="clearfix mb-3"> <span class="float-start badge rounded-pill bg-primary"><?php echo utf8_decode($row['name']); ?></span> <span class="float-end price-hp"><?php echo 'Precio: '.utf8_decode($row['price']).'$'; ?></span> </div>

                        <h5 class="card-title"><?php echo utf8_decode($row['description']); ?></h5>
                        <div class="clearfix mb-3">
                        <span class="float-start"><?php echo 'Marca: '.utf8_decode($row['brand']); ?></span>
                        <br>
                        <span class="float-start"><?php echo 'Modelo: '.utf8_decode($row['model']); ?></span>
                      </div>

                        <div class="text-center my-4"> 
                          <a href="#" class="btn btn-info text-white" onclick="alert('Aun no tiene funcionalidad')">Editar</a>
                           <a href="#" class="btn btn-danger" onclick="removeProduct(<?php echo $row['id']; ?>)">Eliminar</a> 
                        </div>

                    </div>
                </div>
            </div>
              <?php } ?>
        </div>
    </div>
    <?php }else{ ?>
        <hr>
        <div class="container">
          <div class="row">
            <div class="col-md-12 p-4">
              <div class="text-center">
                <h1>No has agregado ningún producto.</h1>
              </div>
            </div>
          </div>
        </div>

      <?php } ?>

</main>


<?php include 'include/footer.php' ?>

