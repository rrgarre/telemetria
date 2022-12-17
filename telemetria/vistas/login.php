<?php
// Includes necesarios para conexion y config
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/RepositorioUsuario.inc.php';
// Para pedir el usuario por email a la BBDD
include_once 'app/Usuario.inc.php';
// validador
include_once 'app/ValidadorLogin.inc.php';
// Herramienta de redireccion para login satisfactorio
include_once 'app/Redireccion.inc.php';
include_once 'app/ControlSesion.inc.php';

// comprobamos si la sesion está iniciada
// en caso afirmativo lo redirigimos para que no pueda entrar en login
if(ControlSesion::sesion_iniciada()){
  Redireccion::redirigir(SERVIDOR);
}
// Comprobamos si se ha pulsado el boton LOGIN
if(isset($_POST['login'])){
  Conexion::abrir_conexion();
  $validador = new ValidadorLogin($_POST['email'], $_POST['password'], Conexion::obtener_conexion());

  // comprobamos que todo ha ido bien
  if($validador -> get_error() === '' &&
      !is_null($validador -> get_usuario()))
  {
    // Si marcamos recordar contraseña, LO MEMORIZAMOS EN SESSION
    if(isset($_POST['recordar']) && $_POST['recordar'] == '1'){
      $recordar = 'OK';
    }else{
      $recordar = 'NO';
    }

    // iniciamos sesion
    ControlSesion::iniciar_sesion(
        $validador -> get_usuario() -> get_id(),
        $validador -> get_usuario() -> get_nombre(),
        $recordar
    );
    // mandamos al usuario a index o gestor de entradas o lo que sea
    Redireccion::redirigir(SERVIDOR);
  }
  Conexion::cerrar_conexion();
}

$titulo = 'login';
include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/documento-navbar.inc.php';

?>
<!-- hacemos el formulario -->
<div class="container" id="login-container">
  <div class="row">
    <div class="col-md-3">
      <!-- espaciamos 3 columnas -->
    </div>
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading text-center">
          <h4>Iniciar Sesion</h4>
        </div>
        <div class="panel-body">
          <!-- en action nos reenviamos la info a la misma pagina -->
          <form role='form' action="<?php echo RUTA_LOGIN ?>" method="post">
            <h2>Introduce tus datos</h2>
            <br>
            <!-- 2 campos -->
            <!-- Este label es para las tecnologias de accesibilidad -->
            <label for="email" class="sr-only">Email</label>
            <input type="email" name="email" id='email' placeholder="Email" class="form-control"
              <?php
              if(isset($_POST['login']) && isset($_POST['email']) && !empty($_POST['email'])){
                echo 'value="' . $_POST['email'] . '"';
              }
              ?>
              required autofocus>
            <br>
            <label for="password" class="sr-only">Contraseña</label>
            <input type="password" name="password" id='password' placeholder="Contraseña" class="form-control" required>
            <?php
            if(isset($_POST['login'])){
              if($validador -> get_error() !== ''){
                $validador -> mostrar_error();
              }
            }
            ?>
            <br>
            <!-- boton con estilo Bootstrap -->
            <button type="submit" name="login" class="btn btn-lg btn-primary btn-block">
              Iniciar sesión
            </button>
            <br>
            <label for="recordar" class="sr-only">Recordar contraseña</label>
            <p>
              Recordar contraseña&nbsp;&nbsp;
              <input type="checkbox" name="recordar" id='recordar' value="1">
            </p>
            <?php
            if(isset($_POST['login'])){
              if($validador -> get_error() !== ''){
                $validador -> mostrar_error();
              }
            }
            ?>
            <br>
            <div class="text-center">
              <a href="<?php echo RUTA_REGISTRO; ?>">Registrarse</a>
            </div>
            <br>
            <div class="text-center">
              <a href="<?php echo RUTA_RECUPERAR_CLAVE; ?>">¿Olvidaste tu contraseña?</a>
            </div>
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
  include_once 'plantillas/documento-cierre.inc.php';
?>
