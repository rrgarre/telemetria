<?php

class Deposito{
  private $cloros = null;
  private $turbis = null;
  private $zona;
  private $nombre;
  private $servicio;
  private $idFecha;

  public function __construct($zona, $nombre, $servicio, $idFecha){
    $this -> zona = $zona;
    $this -> nombre = $nombre;
    $this -> idFecha = $idFecha;
    $this -> servicio = $servicio;
    // echo $this -> zona . '<br>' . $this -> nombre . '<br>';
  }

  // GETs y SETs============================================
  public function set_cloros($cloros){
    if(!empty($cloros)){
      $this -> cloros = $cloros;
    }else{}
  }
  public function get_cloros(){
    return $this -> cloros;
  }
  public function set_turbis($turbis){
    if(!empty($turbis)){
      $this -> turbis = $turbis;
    }else{}
  }
  public function get_turbis(){
    return $this -> turbis;
  }
  public function get_zona(){
    return $this -> zona;
  }
  public function get_nombre(){
    return $this -> nombre;
  }
  public function get_servicio(){
    return $this -> servicio;
  }
  public function get_idfecha(){
    return $this -> idFecha;
  }

  // Metodo para escribir los datos contenidos
  public function imprimir_datos(){
    echo '================================<br>';
    // Mostramos zona
    echo 'Zona: ' . $this -> zona . ' ' . $this -> idFecha . '<br>';

    // Mostramos deposito
    echo $this -> nombre . '<br>';

    // Mostramos cloros
    if(isset($this -> cloros)){
      $cont = 1;
      echo 'Cloros: <br>';
      foreach ($this -> cloros as $cloro) {
        echo 'cloro ' . $cont . ': ' . $cloro . 'ppm.<br>';
        $cont ++;
      }
    }else{
      // echo 'No existen lecturas de cloro.<br>';
    }
    echo '================================<br>';
  }
}

?>
