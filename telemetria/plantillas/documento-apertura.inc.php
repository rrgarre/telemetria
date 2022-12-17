<!-- Apertura del html -->
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width-device=width, initial-scale=1">

    <?php
    // Incluimos la plantilla para el icono
    include_once "icono-web.inc.php";

    // comprobamos si titulo no esta definido OR esta vacio
    // si ocurre un error ponemos el titulo generico para arreglarlo
    if(!isset($titulo) || empty($titulo)){
      $titulo = 'telemetría MCT';
    }
    echo "<title>$titulo</title>";

    ?>

     <!-- ESTILOS -->
    <link href="<?php echo RUTA_CSS; ?>bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo RUTA_CSS; ?>font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo RUTA_CSS; ?>estilos.css" rel="stylesheet">
    <!-- fanciBox3 -->
    <link  href="<?php echo RUTA_CSS; ?>jquery.fancybox.min.css" rel="stylesheet">


    <!-- SCRIPTS -->
    <!-- jQuery -->
    <script src="<?php echo RUTA_JS; ?>jquery.min.js"></script>

    <!-- fancyBox3 -->
    <script src="<?php echo RUTA_JS; ?>jquery.fancybox.min.js"></script>

    <!-- SCRIPT PROPIO -->
    <script src="<?php echo RUTA_JS; ?>script.js"></script>
    <script src="<?php echo RUTA_JS; ?>script-alertas.js"></script>

  </head>

  <body>
