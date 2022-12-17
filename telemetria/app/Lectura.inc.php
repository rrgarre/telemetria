<?php

class Lectura
{
  private $id;
  private $zona;
  private $codigo;
  private $tipo;
  private $dato;
  private $idfecha;
  public function __construct($id, $zona, $codigo, $tipo, $dato, $idfecha){
    $this -> id = $id;
    $this -> zona = $zona;
    $this -> codigo = $codigo;
    $this -> tipo = $tipo;
    $this -> dato = $dato;
    $this -> idfecha = $idfecha;
  }
  public function get_id(){
    return $this -> id;
  }
  public function get_zona(){
    return $this -> zona;
  }
  public function get_codigo(){
    return $this -> codigo;
  }
  public function get_tipo(){
    return $this -> tipo;
  }
  public function get_dato(){
    return $this -> dato;
  }
  public function get_idfecha(){
    return $this -> idfecha;
  }

}


?>
