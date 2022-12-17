<?php

include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/Redireccion.inc.php';

// leemos datos del GET comprobando iniciada y noVacia
// if(isset($_GET['nombre']) && !empty($_GET['nombre'])){
//   $nombre = $_GET['nombre'];
// }else{
//   Redireccion::redirigir(SERVIDOR);
// }

// añadimos las plantillas de la página
$titulo = '¡Registro correcto!';
include_once 'plantillas/documento-apertura.inc.php';
// aquí necesitaremos una conexion, que se abre dentro de navbar, pero debemos
// incluir conexion en este documento actual... HECHO
include_once 'plantillas/documento-navbar.inc.php';
?>
<!-- contenido en html -->
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>
          Registro correcto
        </div>
        <div class="panel-body text-center">
          <p>¡Gracias por registrarte <b><?php echo $nombre ?></b>!</p>
          <br>
          <p><a href="<?php echo RUTA_LOGIN ?>">Inicia sesión</a> para comenzar a utilizar tu cuenta.</p>
        </div>
      </div>
    </div>
  </div>
</div>


<?php
// cerramos documento
include_once 'plantillas/documento-cierre.inc.php';
 ?>
