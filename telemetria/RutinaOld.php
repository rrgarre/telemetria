<?php

include_once 'app/Conexion.inc.php';
include_once 'app/config.inc.php';
include_once 'app/Deposito.inc.php';
include_once 'app/Rastreador.inc.php';
include_once 'app/RepositorioTelemetria.inc.php';
include_once 'app/GestorFicheros.inc.php';
// $titulo = 'telemetría MCT';
// include_once 'plantillas/documento-apertura.inc.php';
// // incluimos la barra de navegacion
// include_once 'plantillas/documento-navbar.inc.php';

$numerCheck = 1;    //Eliminar

$tiempo = time();

Conexion::abrir_conexion();



echo '<br>CHECK: ' . $numerCheck++ . '<br>';    //Eliminar


// ====================================Mantenimiento BBDD==============
$rastreador = new Rastreador();

echo '<br>CHECK: ' . $numerCheck++ . '<br>';    //Eliminar

// Con verificacion de fecha==============================
if($rastreador -> existe_actualizacion(Conexion::obtener_conexion())){
  echo '<br>CHECK: ' . $numerCheck++ . '<br>';    //Eliminar
  // Guardar indices de datos existentes para mandar a historico
  $rastreador -> obtener_rango_ids_antiguas(Conexion::obtener_conexion());
  echo '<br>CHECK: ' . $numerCheck++ . '<br>';    //Eliminar
  // Si existe actualizacion de la telemetria, se actualiza la fecha en BBDD
  // se reconstruye la tabla de DATOS
  $rastreador -> reconstruir(Conexion::obtener_conexion());
  echo '<br>CHECK: ' . $numerCheck++ . '<br>';    //Eliminar
  // Mandamos al historico
  // $rastreador -> guardar_datos_antiguos(Conexion::obtener_conexion());
  // Borramos datos memorizados
  $rastreador -> borrar_datos_antiguos(Conexion::obtener_conexion());
  echo '<br>CHECK: ' . $numerCheck++ . '<br>';    //Eliminar
}

// Sin verificar fecha, SIEMPRE se ejecuta=======================
// $rastreador -> reconstruir(Conexion::obtener_conexion());
// ====================================Mantenimiento BBDD==============

// Cerramos conexion
Conexion::cerrar_conexion();

$tiempo = time() - $tiempo;
echo '<br>' . $tiempo . ' seg';

echo '<br>CHECK: ' . $numerCheck++ . '<br>';    //Eliminar



?>
