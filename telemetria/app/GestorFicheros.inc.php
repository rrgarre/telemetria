<?php

class GestorFicheros{

  public static function guardar_datos_antiguos($nombre_fichero, $fecha, $contenido){
    print_r($contenido);
    // echo CARPETA_HISTORICOS . $nombre_fichero . '.txt';
    $file = fopen(CARPETA_HISTORICOS . $nombre_fichero . '.txt', "a");
    // echo $fecha . '*************';
    fwrite($file, $fecha . "\n");
    foreach ($contenido as $lectura) {
      fwrite($file, '*' . $lectura["zona"] . '*' .
                    $lectura["codigo"] . '*' .
                    $lectura["tipo"] . '*' .
                    $lectura["dato"] . '*' .
                    $lectura["idfecha"] . "*\n");
    }

    fclose($file);
  }

}

?>
