<?php
require 'config/config.php';
require 'config/database.php';
$db = new Database ();
$con = $db ->conectar();

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql-> execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>shop_alzoko</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
  rel="stylesheet" 
  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
  crossorigin="anonymous">
  <link href="css/estilos.css" rel="stylesheet">

</head>
<body>
<header>
    <div class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand">
          <strong>Alzoko</strong>
      </a>
      <button class="navbar-toggler" type="button" 
      data-bs-toggle="collapse" 
      data-bs-target="#navbarHeader" 
      aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class ="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a href="#" class="nav-link active">Nuestro servicio</a>

          </li>
          <li class="nav-item">
            <a href="#" class="nav-link ">Galeria</a>

          </li>
          <li class="nav-item">
            <a href="#" class="nav-link ">CataExpertos en:</a>
          </li>
        </ul>
        <a href="Carrito.php" class="btn btn-primary">Carrito</a>
      </div>
    </div>
  </div>
</header>

<main>
  <div class="container">
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
     <?php foreach ($resultado as $row) { ?>
        <div class="col">
          <div class="card shadow-sm">
            <?php
            $id = $row['id'];
            $imagen= "images/productos/". $id . "/principal.jpg";
            
            if(!file_exists ($imagen)){
              $imagen = "images/nophoto.jpg";
            }
            ?>
            <img src="<?php echo $imagen; ?>">
             <div class="card-body">
              <h5 class="card-title"><?php echo $row ['nombre']; ?></h5>
              <p class= "card-text">$ <?php echo number_format($row['precio'], 3,'.',',') ?></p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo 
                  hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" class="btn 
                  btn-primary">Detalles</a>         
                </div>
                <a href="#" class="btn btn-success">Agregar</a> 
              </div>
            </div>
          </div>
        </div>
    <?php } ?>
   </div>
 </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" 
integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
crossorigin="anonymous">
</script>

</body>
</html>