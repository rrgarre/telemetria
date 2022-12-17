<?php
include_once 'app/Redireccion.inc.php';
include_once 'app/config.inc.php';

include_once 'app/RecuperacionClave.inc.php';
include_once 'app/RepositorioRecuperacionClave.inc.php';
include_once 'app/RepositorioUsuario.inc.php';

// comprobamos qeue el boton de submit del form de recuperacion ha sido pulsado
if(isset($_POST['enviar-email'])){
  $longitud_url = 10;

  $email = $_POST['email'];

  // Comprobamos si existe el email
  Conexion::abrir_conexion();

  if(RepositorioUsuario::email_existe(Conexion::obtener_conexion(), $email)){

    // si existe el email, obtenemos el usuario
    $usuario = RepositorioUsuario::obtener_usuario_por_email(Conexion::obtener_conexion(), $email);

    $nombre_usuario = $usuario -> get_nombre();
    $id_usuario = $usuario -> get_id();

    // comprobamos si hay ya una url generada en una paticion anterior
    if(!RepositorioRecuperacionClave::existe_recuperacion_por_autor_id(Conexion::obtener_conexion(), $id_usuario)){

      // Creamos la cadena aleatoria + nombre usuario
      $cadena_aleatoria = cadena_aleatoria($longitud_url);
      $cadena_aleatoria .= $nombre_usuario;
      // Creamos la URL secreta 64 caracteres
      $url_secreta = hash('sha256', $cadena_aleatoria);

      // intentamos enviar el email con el enlace de recuperacion
      //Cuando queramos dar esta funcionalidad, debemos descomentar
      // el formato y envio de email
      // y poner a false esta variable
      $email_enviado = false;
      $asunto = 'enlace de recuperación de contraseña';
      $mensaje = 'Si ha solicitado una recuperación de sontraseña, pinche el enlace: ';
      $mensaje .= RUTA_RESTABLECER_CLAVE . '/' . $url_secreta;
      $mensaje .= ' De lo contrario, por favor, ignore este email.';
      $email_enviado = mail($usuario -> get_email(), $asunto, $mensaje);

      if($email_enviado){

        // Instanciamos una recueracion de clave
        $id_usuario = $usuario -> get_id();
        $recuperacion = new RecuperacionClave(
                                            '',
                                            $id_usuario,
                                            $url_secreta,
                                            ''
                                            );

        if(RepositorioRecuperacionClave::insertar_recuperacion_clave(Conexion::obtener_conexion(), $recuperacion)){

          // echo 'recuperacion insertada<br>';
          // redirigir a login
          Redireccion::redirigir(RUTA_LOGIN);
        }else{
          // Error en la operación, intentar más tarde
          echo 'No se ha podido completar la operacion';
        }
      }else{
        echo 'no se ha podido enviar la recuperación al email de usuario';
      }
    }else{
      // echo 'Existe una petición de recuperación de contraseña anterior.';
      // echo '<br>Por favor, revise su buzón de SPAN.';
      Redireccion::redirigir(RUTA_LOGIN);
    }

  }else{
    // email no existe
    // redirigir a login
    Redireccion::redirigir(RUTA_LOGIN);
  }

  Conexion::cerrar_conexion();

}







function cadena_aleatoria($long){
  $diccionario = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
  $cadena = '';
  for($i = 0; $i < $long; $i++){
    $cadena .= $diccionario[rand(0, strlen($diccionario) - 1)];
  }
  return $cadena;
}

?>
