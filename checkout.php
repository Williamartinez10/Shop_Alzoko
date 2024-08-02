<?php

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db ->conectar();


$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

print_r($_SESSION); 

$lista_carrito = array();

if ($productos != null){
  foreach ($productos as $clave => $cantidad) {

   $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo=1");
   $sql->execute([$clave]);
   $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
}
}

// session_destroy();

 

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
        <a href="checkout.php" class="btn btn-primary">Carrito
          <span id="num_cart" classs= "badge bg-secondary"> <?php echo $num_cart; ?></span>
        </a>
      </div>
    </div>
  </div>
</header>

<main>
  <div class="container">
  <div class='table-responsive'>
    <table class ='table'>
      <thead>
        <tr>
          <th> Producto </th>
          <th> Precio </th>
          <th> cantidad </th>
          <th> Subtotal </th>
          <th></th>
         </tr>
       </thead>
      <tbody>
        <?php  if ($lista_carrito == null){
          echo '<tr><td colspan="5" class="text-center"><b>Lista vacia</b></td></tr>';
        } else{
          $total = 0;
          foreach ($lista_carrito as $producto){
            $_id = $producto['id'];
            $nombre = $producto['nombre'];
            $precio = $producto['precio'];
            $descuento = $producto['descuento'];
            $cantidad = $producto['cantidad'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            $subtotal = $cantidad * $precio_desc;
            $total += $subtotal;
             ?>

        <tr>
          <td><?php echo $nombre; ?></td>
          <td><?php echo MONEDA . number_format($precio_desc, 3, '.', '.'); ?></td>
          <td>
            <input type="number" min="1" max="10" step="1" value="<?php echo 
            $cantidad ?>" size="5" id="cantidad_<?php echo $_id; ?>" 
            onchange="actualizaCantidad(this.value, <?php  echo  $_id; ?>)">
          </td>
          <td> 
            <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo 
            MONEDA . number_format($subtotal, 3, '.', '.'); ?></div>
          </td>
          <td><a id="eliminar" class="btn btn-warning btn-sm" data-bs-id="<?php  echo 
          $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar</a>
          </td>
          </tr>    
        <?php } ?>

        <tr><td colspan="3"></td>
            <td colspan="2">
              <p class="h3" id="total"><?php echo MONEDA . number_format($total, 3,'.','.'); ?></P>
            </td>

          </td></tr>
        </tbody>

<?php } ?>
</table>
 </div>
 <div class="row">
  <div class="col-md-5 offset-md-7 d-grid gap-2">
    <button class="btn btn-primary btn-lg">Realizar pago</button>
  
 </div>
 </div>
</main>

<div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="eliminaModalLabel">Alerta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Desea eliminar el producto de la lista?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button id= "btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" 
integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
crossorigin="anonymous">
</script>

<script> 


let eliminaModal = document.getElementById('eliminaModal')
eliminaModal.addEventListener('show.bs.modal', function(event){
  let button = event.relatedTarget 
  let id = button.getAttribute('data-bs-id')
  let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
  buttonElimina.value = id
})

  function actualizaCantidad(cantidad, id){
    let url= 'clases/actualizar_carrito.php'
    let formData = new FormData()
    formData.append('action', 'agregar') 
    formData.append('id', id) 
    formData.append('cantidad', cantidad)

    fetch(url, {
      method: 'POST',
      body: formData,
      mode: 'cors'
    }).then(response => response.json())
    .then(data => {
      if (data.ok) {

        let divsubtotal = document.getElementById('subtotal_' + id)
        divsubtotal.innerHTML = data.sub

        let total= 0.00
        let list = document.getElementsByName('subtotal[]')

        for(let i = 0; i < list.length; i++){
          total += parseFloat(list[i].innerHTML.replace (/[$,]/g, ''))
        }
        total = new Intl.NumberFormat('en-US',{
          minimumFractionDigits: 3
        }). format(total)
        document.getElementById('total'). innerHTML = '<?php  echo MONEDA; ?>' + total
      }
    })
  }



  function eliminar(){

    let botonElimina = document.getElementById('btn-elimina')
    let id = botonElimina.value

    let url= 'clases/actualizar_carrito.php'
    let formData = new FormData()
    formData.append('action', 'eliminar') 
    formData.append('id', id) 


    fetch(url, {
      method: 'POST',
      body: formData,
      mode: 'cors'
    }).then(response => response.json())
    .then(data => {
      if (data.ok) {
        location.reload()
      }
    })
  }
</script>
</body>
</html>