<?php

class RecuperacionClave{
  private $id;
  private $usuario_id;
  private $enlace;
  private $fecha;

  public function __construct($id, $usuario_id, $enlace, $fecha){
    // echo 'creando rec.clave<br>';
    // echo $id . '<br>';
    // echo $autor_id . '<br>';
    // echo $enlace . '<br>';
    // echo $fecha . '<br>';
    $this -> id = $id;
    $this -> usuario_id = $usuario_id;
    $this -> enlace = $enlace;
    $this -> fecha = $fecha;
  }

  // GETERS SETERS
  public function get_id(){
    return $this -> id;
  }
  public function get_usuario_id(){
    return $this -> usuario_id;
  }
  public function get_enlace(){
    return $this -> enlace;
  }
  public function get_fecha(){
    return $this -> fecha;
  }

  public function set_enlace($enlace){
    $this -> enlace = $enlace;
  }
}


?>
