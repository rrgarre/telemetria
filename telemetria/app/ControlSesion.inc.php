<?php

/**
 *
 */
class ControlSesion{

  // metodo para iniciar una sesion
  public static function iniciar_sesion($id_usuario, $nombre_usuario, $recordar_usuario){

    // si no existe sesion activa, la iniciamos
    if(session_id() == ''){
      session_start();
    }
    // introducimos los valores a la sesion
    $_SESSION["id_usuario"] = $id_usuario;
    $_SESSION["nombre_usuario"] = $nombre_usuario;
    $_SESSION["recordar_usuario"] = $recordar_usuario;
  }

  // metodo para cerrar una sesion
  public static function cerrar_sesion(){
    // si no existe sesion activa, la iniciamos
    if(session_id() == ''){
      session_start();
    }

    // borramos las coockies
    setcookie("id_usuario", "", time()-1);
    setcookie("nombre_usuario", "", time()-1);
    setcookie("hash_usuario", "", time()-1);

    if(isset($_SESSION["id_usuario"])){
      unset($_SESSION["id_usuario"]);
    }
    if(isset($_SESSION["nombre_usuario"])){
      unset($_SESSION["nombre_usuario"]);
    }
    if(isset($_SESSION["recordar_usuario"])){
      unset($_SESSION["recordar_usuario"]);
    }

    // nos aseguramos liberando la memoria de la sesion en el server
    session_destroy();
  }

  // metodo para saber si la sesion está iniciada o no
  public static function sesion_iniciada(){
    // si no existe sesion activa, la iniciamos
    if(session_id() == ''){
      session_start();
    }

    if(isset($_SESSION["id_usuario"]) && isset($_SESSION["nombre_usuario"])){
      return true;
    }else{
      return false;
    }
  }

}


?>
