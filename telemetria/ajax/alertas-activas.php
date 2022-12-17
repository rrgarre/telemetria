<?php
/****************************************************************************************************
Aqui se refresca el numero de alertas para mostrar en el NAVBAR
****************************************************************************************************/
include_once "../app/RepositorioFavoritos.inc.php";
include_once "../app/RepositorioTelemetria.inc.php";
include_once "../app/Conexion.inc.php";
include_once "../app/ControlSesion.inc.php";

/*************************************************************
TURBIDEZ MAXIMA
*************************************************************/
$turbidezMax = 0.7;


if(ControlSesion::sesion_iniciada()){

  Conexion::abrir_conexion();

  $id = $_SESSION['id_usuario'];

  $alertasUsusario = RepositorioFavoritos::obtener_alertas_usuario(Conexion::obtener_conexion(), $id);

  $alertasActivas = array();
  $alertaActual = false;
  foreach($alertasUsusario as $fila){
    $dato = RepositorioTelemetria::obtener_datos_deposito(Conexion::obtener_conexion(), $fila['codigo']);
    $alertaActual = false;

    foreach($dato as $filaDato){
      $max = $fila['max']/100;
      $min = $fila['min']/100;
      $dato = $filaDato['dato'];
      $tipoDato = $filaDato['tipo'];

      if($tipoDato == 'CL'){
        if((($max < $dato) || ($min > $dato)) && !$alertaActual){
          $alertaActual = true;
          $alertasActivas[] = $fila;
        }
      }else if($tipoDato == 'TU'){
        /////////////// Activar para alertas de TURBIDEZ /////////////////
        // if($dato == '-10' && !$alertaActual){
        if((($dato == '-10') || ($dato >= $turbidezMax)) && !$alertaActual){
          $alertaActual = true;
          $alertasActivas[] = $fila;
          // $alertasActivas[] = ($dato);
        }
      }
    }
  }
  Conexion::cerrar_conexion();

  if(count($alertasActivas) > 0){
    echo '<span class="badge">' . count($alertasActivas) . '</span> alarmas';
  }else{
    echo 'alarmas';
  }
}
?>
