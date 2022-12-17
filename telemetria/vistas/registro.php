<?php

include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
// Incluimos la clase Usuario
include_once 'app/Usuario.inc.php';
include_once 'app/RepositorioUsuario.inc.php';
// Incluimos el validador que acabamos de crear
include_once 'app/ValidadorRegistro.inc.php';
// Incluimos la clase Redireccion con la herramienta para cambiar de página
include_once 'app/Redireccion.inc.php';

// comprobamos que se la variable ENVIAR existe para validarRegistro
// enviar es el nombre que le damos al elemento boton de envio formulario
if(isset($_POST['enviar'])){
  Conexion::abrir_conexion();
  // creamos una variavle validador y la inicializamos con la info devuelta
  // por el formulario
  $validador = new ValidadorRegistro($_POST['nombre'], $_POST['email'],
                                $_POST['password1'], $_POST['password2'],
                                Conexion::obtener_conexion());
  if($validador -> registro_valido()){
    // echo Todo CORRECTO!";
    // en lugar de escribir todo correcto, vamos ha insetar el usuario
    $usuario = new Usuario('',
                            $validador -> get_nombre(),
                            $validador -> get_email(),
                            password_hash($validador -> get_password(), PASSWORD_DEFAULT),
                            '',
                            '');
    $usuario_insertado = RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $usuario);
    // Comprobamos si el usuario se ha insertado con exito
    if($usuario_insertado){
      // Redirigimos a login y pasamos parámetro
      Redireccion::redirigir(RUTA_REGISTRO_CORRECTO .
                              '/' . $usuario -> get_nombre());

    }
  }
  Conexion::cerrar_conexion();
}

// Aqui estaba la parte de conexion a BBDD para pedir total_usuarios
// pero como es algo que usaremos en NAVBAR, una buena praxis
// consiste en llevarnos ese código a la plantilla de NAVBAR

// incluimos la apertura del documento
// definimos el título de página como parámetro para poder
// usar esa plantilla en cualquier sitio
$titulo = 'Registro';
include_once 'plantillas/documento-apertura.inc.php';

// incluimos la barra de navegacion
include_once 'plantillas/documento-navbar.inc.php';

?>


<!-- jumbotron -->
<div class="container">
  <div class="jumbotron">
    <h1 class="text-center"><?php echo $titulo; ?></h1>
  </div>
</div>
<!-- contenido principal -->
<div class="container">
  <div class="row">

    <!-- <div class="col-md-6 text-center">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Instrucciones</h3>
        </div>
        <div class="panel-body">
          <br>
          <p class="text-justify">
            Para unirte al blog de LrEnder introduce un nombre de usuario,
            email y una contraseña. El email debe ser real para gestionar
            tu usuario. Te recomendamos que utilices una contraseña variada.
          </p>
          <br>
          <a href="#">¿Ya estás registrado?</a>
          <br><br>
          <a href="#">¿Olvidaste tu contraseña?</a>
        </div>
      </div>
    </div> -->

    <div class="col-md-6">
      <!-- formulario -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Introduce tus datos</h3>
        </div>
        <div class="panel-body">
          <!-- en bootstrap: role = form -->
          <form role="form" method="post" action="<?php echo RUTA_REGISTRO; ?>">
            <?php

            if(isset($_POST['enviar'])){
              include_once 'plantillas/RegistroValidado.inc.php';
            } else{

              include_once 'plantillas/RegistroVacio.inc.php';
            }
            ?>
          </form>
          <hr>
          <div class="" align="center">
            <a href="<?php echo RUTA_LOGIN; ?>">¿Ya estás registrado?</a>
            <br><br>
            <a href="<?php echo RUTA_RECUPERAR_CLAVE; ?>">¿Olvidaste tu contraseña?</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php
  include_once 'plantillas/documento-cierre.inc.php';
 ?>
