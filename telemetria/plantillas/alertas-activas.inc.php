<?php
Conexion::abrir_conexion();
$alertasUsusario = RepositorioFavoritos::obtener_alertas_usuario(Conexion::obtener_conexion(), $_SESSION['id_usuario']);
$alertasActivas = array();
$alertaActual = false;
foreach($alertasUsusario as $fila){
  $dato = RepositorioTelemetria::obtener_datos_deposito(Conexion::obtener_conexion(), $fila['codigo']);
  $alertaActual = false;

  foreach($dato as $filaDato){
    $max = $fila['max']/100;
    $min = $fila['min']/100;
    $dato = $filaDato['dato'];

    if((($max < $dato) || ($min > $dato)) && !$alertaActual){
      $alertaActual = true;
      $alertasActivas[] = $fila;
    }
  }
}

Conexion::cerrar_conexion();

if(count($alertasActivas) > 0){
  ?>
  <span class="badge"><?php //echo count($alertasActivas); ?></span>
  <?php
}
?>
