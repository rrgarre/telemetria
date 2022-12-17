<?php

include_once 'app/ValidadorCambioClave.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/RepositorioRecuperacionClave.inc.php';
include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/config.inc.php';

Conexion::abrir_conexion();

// obtenemos el autor id asociado al enlace de recuperacion

$recuperacion = RepositorioRecuperacionClave::obtener_recuperacion_por_url(Conexion::obtener_conexion(), $url_secreta);

if(isset($recuperacion)){

  $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(),$recuperacion -> get_usuario_id());

}else{
  echo 'no existe direccion de recuperacion';
}

// Si ya hemos validado el FORM
if(isset($_POST['cambiar-clave'])){

  $validador = new ValidadorCambioClave($_POST['clave'],$_POST['clave-repetida']);

  // Si todo es correcto:
  if($validador -> cambio_clave_valido()){
    // Cambiamos la password de $usuario pasando por hash
    $usuario -> set_password(password_hash($validador -> get_password(), PASSWORD_DEFAULT));
    // echo $validador -> get_password();
    // actualizamos BBDD de usuario con el usuario modificado
    // .... Y borramos recuperacion de la base de datos al mismo tiempo

    if(RepositorioUsuario::actualizar_clave_usuario_por_recuperacion(
      Conexion::obtener_conexion(), $usuario)){
        // redirigimos a login
        Redireccion::redirigir(RUTA_LOGIN);

      }else{
        echo 'ERROR: no se ha podido actualizar la contraseña';
      }
  }
}

Conexion::cerrar_conexion();


// Creamos la vista
$titulo = 'Restablecer contraseña';
include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/documento-navbar.inc.php';

?>

<div class="container">
  <div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading text-center">
          <h4>Restablecer contraseña</h4>
        </div>
        <div class="panel-body">
          <form role="form" action="<?php echo RUTA_RESTABLECER_CLAVE . '/' . $url_secreta; ?>" method="post">
            <div class="form-group">
              <label for="clave">Nueva contraseña</label>
              <input type="password" name="clave" id="clave" class="form-control" required>
              <?php
              if(isset($_POST['cambiar-clave'])){
                $validador -> mostrar_error_password1_en_form();
              }
              ?>
            </div>
            <div class="form-group">
              <label for="clave-repetida">Repite la contrase</label>
              <input type="password" name="clave-repetida" id="clave-repetida" class="form-control" required>
              <?php
              if(isset($_POST['cambiar-clave'])){
                $validador -> mostrar_error_password2_en_form();
              }
              ?>
            </div>

            <button type="submit" name="cambiar-clave" class="btn btn-lg btn-primary btn-block">
              Cambiar contraseña
            </button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-3">
    </div>
  </div>
</div>


<?php
include_once 'plantillas/documento-cierre.inc.php';
?>
