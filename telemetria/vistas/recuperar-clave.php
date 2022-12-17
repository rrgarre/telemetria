<?php

$titulo = 'Recuperación de contraseña';
include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/documento-navbar.inc.php';

?>
<!-- hacemos el formulario -->
<div class="container">
  <div class="row">
    <div class="col-md-3">
      <!-- espaciamos 3 columnas -->
    </div>
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading text-center">
          <h4>Recuperación de contraseña</h4>
        </div>
        <div class="panel-body">
          <!-- en action nos reenviamos la info a la misma pagina -->
          <form role='form' action="<?php echo RUTA_GENERAR_URL_SECRETA; ?>" method="post">
            <h2>Introduce tus datos</h2>
            <br>
            <p>
              Indica el email con el que te registraste para enviarte la recuperación.
            </p>
            <br>
            <!-- 2 campos -->
            <!-- Este label es para las tecnologias de accesibilidad -->
            <label for="email" class="sr-only">Email</label>
            <input type="email" name="email" id='email' placeholder="Email" class="form-control" required autofocus>

            <br>
            <!-- boton con estilo Bootstrap -->
            <button type="submit" name="enviar-email" class="btn btn-lg btn-primary btn-block">
              Enviar email
            </button>
            <br>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <!-- espaciado -->
    </div>
  </div>
</div>




<!-- cerramos documento -->
<?php
include_once 'plantillas/documento-cierre';
 ?>
