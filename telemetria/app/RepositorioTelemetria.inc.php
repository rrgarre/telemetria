<?php

class RepositorioTelemetria{

  // Metodos para la Rutina de actualizacion

  public static function obtener_fecha($conexion){

    $fecha = '';
    if(isset($conexion)){
      try {
        $sql = 'SELECT fecha_actualizacion FROM fechas ORDER BY id DESC LIMIT 1';
        $sentencia = $conexion -> prepare($sql);
        $sentencia -> execute();
        $resultado = $sentencia -> fetch();
        if(!empty($resultado)){
          $fecha = $resultado['fecha_actualizacion'];
        }
      } catch (PDOException $ex) {
        print 'ERROR: ' . $ex -> getMessage();
      }
      return $fecha;
    }
    return 'No existe fecha que mostrar';
  }

  public static function agregar_fecha($conexion, $fecha){
    // echo $fecha . ' desde repositorio';
    $fecha_insertada = false;
    if(isset($conexion)){
      try {
        $sql = 'INSERT INTO fechas(fecha_actualizacion) VALUES(:fecha_actualizacion)';
        $sentencia = $conexion -> prepare($sql);
        $sentencia -> bindParam(':fecha_actualizacion', $fecha, PDO::PARAM_STR);
        $fecha_insertada = $sentencia -> execute();
      } catch (PDOException $ex) {
        print 'ERROR: ' . $ex -> getMessage();
      }
      return $fecha_insertada;
    }
  }

  public static function obtener_idFecha($conexion, $fecha){
    $idFecha = '';
    try {
      // $sql = 'SELECT id FROM fechas WHERE fecha_actualizacion = fecha VALUES(:fecha)';
      $sql = 'SELECT id FROM fechas ORDER BY id DESC LIMIT 1';
      $sentencia = $conexion -> prepare($sql);
      // $sentencia -> bindParam(':fecha', $fecha, PDO::PARAM_STR);
      $sentencia -> execute();
      $resultado = $sentencia -> fetch();
      if(!empty($resultado)){
        // echo 'idfecha no vacio';
        $idFecha = $resultado['id'];
      }
    } catch (PDOException $ex) {
      print 'ERROR: ' . $ex -> getMessage();
    }
    return $idFecha;
  }

  public static function obtener_menor_id_datos($conexion){
    $id ='';
    try {
      $sql = 'SELECT id FROM datos ORDER BY id ASC LIMIT 1';
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> execute();
      $resultado = $sentencia -> fetch();
      if(!empty($resultado)){
        $id = $resultado['id'];
      }
    } catch (PDOException $ex) {
      print 'ERROR: ' . $ex -> getMessage();
    }
    return $id;
  }

  public static function obtener_mayor_id_datos($conexion){
    $id ='';
    try {
      $sql = 'SELECT id FROM datos ORDER BY id DESC LIMIT 1';
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> execute();
      $resultado = $sentencia -> fetch();
      if(!empty($resultado)){
        $id = $resultado['id'];
      }
    } catch (PDOException $ex) {
      print 'ERROR: ' . $ex -> getMessage();
    }
    return $id;
  }

  public static function agregar_deposito($conexion, $deposito){

    // Comprobamos visualmente que el deposito obtenido tiene
    // bien los datos
    // $deposito -> imprimir_datos();

    // Guardamos el nombre del deposito y la zona en variables para cada ingreso
    $nombre_deposito = $deposito -> get_nombre();
    $nombre_zona = $deposito -> get_zona();
    $servicio = $deposito -> get_servicio();
    $idfecha = $deposito -> get_idfecha();

    // Ingresar Datos de Cloro========================================
    // $tipo_dato = 'CL';
    if(!empty($deposito -> get_cloros())){
      // Añadimos cada dato del deposito a una entrada de la BBDD
      foreach ($deposito -> get_cloros() as $cloro) {
        // Aqui tratamos una lectura de cloro cada vez
        if(isset($conexion)){
          try {
            $tipo_dato = 'CL';
            $sql = 'INSERT INTO datos(zona, codigo, tipo, dato, servicio, idfecha) VALUES(:zona, :codigo, :tipo, :dato, :servicio, :idfecha)';
            $sentencia = $conexion -> prepare($sql);
            $sentencia -> bindParam(':zona', $nombre_zona, PDO::PARAM_STR);
            $sentencia -> bindParam(':codigo',$nombre_deposito, PDO::PARAM_STR);
            $sentencia -> bindParam(':tipo', $tipo_dato, PDO::PARAM_STR);
            $sentencia -> bindParam(':dato', $cloro, PDO::PARAM_STR);
            $sentencia -> bindParam(':servicio', $servicio, PDO::PARAM_STR);
            $sentencia -> bindParam(':idfecha', $idfecha, PDO::PARAM_STR);
            $dato_insertado = $sentencia -> execute();


            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // borrar_deposito_id_mas_antigua($conexion, $deposito);
            // Nos traemos la id mayor de la tabla de datos
            try {
              $sql = 'SELECT MAX(id) FROM datos';
              $sentencia = $conexion -> prepare($sql);
              $sentencia -> execute();
              $resultado = $sentencia -> fetchAll();
              // echo '<br>ultima ID: ';
              // print_r($resultado);
              $ultima_id = $resultado[0][0];
              // echo $ultima_id;
              // echo '<br>';
            } catch (PDOException $ex) {
              print "ERROR: " . $ex -> getMessage();
            }


            // Nos traemos el deposito con mismo nombre que el recien insertado
            // Pero el mas antiguo po ID
            try {
              $sql = 'SELECT * FROM datos WHERE codigo LIKE :deposito LIMIT 1';
              $sentencia = $conexion -> prepare($sql);
              $sentencia -> bindParam(':deposito', $nombre_deposito, PDO::PARAM_STR);
              $sentencia -> execute();
              $resultado = $sentencia -> fetchAll();
              // print_r($resultado);
            } catch (PDOException $ex) {
              print "ERROR: " . $ex -> getMessage();
            }

            // Si hay resultado
            if(!empty($resultado)){
              // AND si la id mas reciente y la del dep mas antiguo con mismo nombre
              // Se llevan más de 5 de diferencia
              $id_mas_antigua = $resultado[0]['id'];
              if(($ultima_id > ($id_mas_antigua+5))){
                echo '<br>La id mas moderna: ' . $ultima_id .
                      '<br>La id del mas antiguo que se borrara: ' . $id_mas_antigua . '<br>';
                // print_r($resultado);
                // echo '<br>id del deposito seleccionado para borrarlo: ' . $resultado[0]['id'] . '<br>';
                try {
                  $sql = 'DELETE FROM datos WHERE id=' . $resultado[0]['id'];
                  $sentencia = $conexion -> prepare($sql);
                  // $sentencia -> bindParam(':idBorrar', $resultado[0]['id'], PDO::PARAM_STR);
                  $sentencia -> execute();
                } catch (PDOException $ex) {
                  print 'ERROR: ' . $ex -> getMessage();
                }
              }else{
                echo '<br>La id mas moderna: ' . $ultima_id .
                      '<br>La id del mas antiguo: ' . $id_mas_antigua . '<br>NO SE BORRA<br>';
              }
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

          } catch (PDOException $ex) {
            print 'ERROR: ' . $ex -> getMessage();
          }

        }
      }
    }else{
      // echo 'sin cloro<br>';
    }

    // TESTING //////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Ingresar Datos de Turbidez========================================
    // $tipo_dato = 'TU';
    if(!empty($deposito -> get_turbis())){
      // Añadimos cada dato del deposito a una entrada de la BBDD
      foreach ($deposito -> get_turbis() as $turbi) {
        // Aqui tratamos una lectura de cloro cada vez
        if(isset($conexion)){
          try {
            $tipo_dato = 'TU';
            $sql = 'INSERT INTO datos(zona, codigo, tipo, dato, servicio, idfecha) VALUES(:zona, :codigo, :tipo, :dato, :servicio, :idfecha)';
            $sentencia = $conexion -> prepare($sql);
            $sentencia -> bindParam(':zona', $nombre_zona, PDO::PARAM_STR);
            $sentencia -> bindParam(':codigo',$nombre_deposito, PDO::PARAM_STR);
            $sentencia -> bindParam(':tipo', $tipo_dato, PDO::PARAM_STR);
            $sentencia -> bindParam(':dato', $turbi, PDO::PARAM_STR);
            $sentencia -> bindParam(':servicio', $servicio, PDO::PARAM_STR);
            $sentencia -> bindParam(':idfecha', $idfecha, PDO::PARAM_STR);
            $dato_insertado = $sentencia -> execute();


            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // borrar_deposito_id_mas_antigua($conexion, $deposito);
            // Nos traemos la id mayor de la tabla de datos
            try {
              $sql = 'SELECT MAX(id) FROM datos';
              $sentencia = $conexion -> prepare($sql);
              $sentencia -> execute();
              $resultado = $sentencia -> fetchAll();
              // echo '<br>ultima ID: ';
              // print_r($resultado);
              $ultima_id = $resultado[0][0];
              // echo $ultima_id;
              // echo '<br>';
            } catch (PDOException $ex) {
              print "ERROR: " . $ex -> getMessage();
            }


            // Nos traemos el deposito con mismo nombre que el recien insertado
            // Pero el mas antiguo po ID
            try {
              $sql = 'SELECT * FROM datos WHERE codigo LIKE :deposito LIMIT 1';
              $sentencia = $conexion -> prepare($sql);
              $sentencia -> bindParam(':deposito', $nombre_deposito, PDO::PARAM_STR);
              $sentencia -> execute();
              $resultado = $sentencia -> fetchAll();
              // print_r($resultado);
            } catch (PDOException $ex) {
              print "ERROR: " . $ex -> getMessage();
            }

            // Si hay resultado
            if(!empty($resultado)){
              // AND si la id mas reciente y la del dep mas antiguo con mismo nombre
              // Se llevan más de 5 de diferencia
              $id_mas_antigua = $resultado[0]['id'];
              if(($ultima_id > ($id_mas_antigua+5))){
                echo '<br>La id mas moderna: ' . $ultima_id .
                      '<br>La id del mas antiguo que se borrara: ' . $id_mas_antigua . '<br>';
                // print_r($resultado);
                // echo '<br>id del deposito seleccionado para borrarlo: ' . $resultado[0]['id'] . '<br>';
                try {
                  $sql = 'DELETE FROM datos WHERE id=' . $resultado[0]['id'];
                  $sentencia = $conexion -> prepare($sql);
                  // $sentencia -> bindParam(':idBorrar', $resultado[0]['id'], PDO::PARAM_STR);
                  $sentencia -> execute();
                } catch (PDOException $ex) {
                  print 'ERROR: ' . $ex -> getMessage();
                }
              }else{
                echo '<br>La id mas moderna: ' . $ultima_id .
                      '<br>La id del mas antiguo: ' . $id_mas_antigua . '<br>NO SE BORRA<br>';
              }
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

          } catch (PDOException $ex) {
            print 'ERROR: ' . $ex -> getMessage();
          }

        }
      }
    }else{
      // echo 'sin cloro<br>';
    }

    // El deposito fue añadido
    return true;
  }

  public static function guardar_datos_antiguos($conexion, $ids){
    try {
      $sql = 'SELECT * FROM datos WHERE id BETWEEN '.$ids["menor"].' AND '.$ids["mayor"];
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> execute();
      $resultado = $sentencia -> fetchAll();
    } catch (PDOException $ex) {
      print 'ERROR: ' . $ex -> getMessage();
    }
    return $resultado;
  }

  public static function borrar_datos_antiguos($conexion, $ids){
    try {
      $sql = 'DELETE FROM datos WHERE id BETWEEN '.$ids["menor"].' AND '.$ids["mayor"];
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> execute();
    } catch (PDOException $ex) {
      print 'ERROR: ' . $ex -> getMessage();
    }
  }

  public static function borrar_deposito_id_mas_antigua($conexion, $deposito){

    echo '<br>desde repositorio______________________________<br>';

    // Guardamos el nombre del deposito y la zona en variables para cada ingreso
    $nombre_deposito = $deposito -> get_nombre();

    // Nos traemos la id mayor de la tabla de datos
    try {
      $sql = 'SELECT MAX(id) FROM datos';
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> execute();
      $resultado = $sentencia -> fetchAll();
      // echo '<br>ultima ID: ';
      // print_r($resultado);
      $ultima_id = $resultado[0][0];
      // echo $ultima_id;
      // echo '<br>';
    } catch (PDOException $ex) {
      print "ERROR: " . $ex -> getMessage();
    }

    // Nos traemos el deposito con mismo nombre que el recien insertado
    // Pero el mas antiguo po ID
    try {
      $sql = 'SELECT * FROM datos WHERE codigo LIKE :deposito LIMIT 1';
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> bindParam(':deposito', $nombre_deposito, PDO::PARAM_STR);
      $sentencia -> execute();
      $resultado = $sentencia -> fetchAll();
      // print_r($resultado);
    } catch (PDOException $ex) {
      print "ERROR: " . $ex -> getMessage();
    }
    // Si hay resultado
    if(!empty($resultado)){
      // AND si la id mas reciente y la del dep mas antiguo con mismo nombre
      // Se llevan más de 5 de diferencia
      $id_mas_antigua = $resultado[0]['id'];
      if(($ultima_id > ($id_mas_antigua+5))){
        echo '<br>La id mas moderna: ' . $ultima_id .
              '<br>La id del mas antiguo que se borrara: ' . $id_mas_antigua . '<br>';
        // print_r($resultado);
        // echo '<br>id del deposito seleccionado para borrarlo: ' . $resultado[0]['id'] . '<br>';
        try {
          $sql = 'DELETE FROM datos WHERE id=' . $resultado[0]['id'];
          $sentencia = $conexion -> prepare($sql);
          // $sentencia -> bindParam(':idBorrar', $resultado[0]['id'], PDO::PARAM_STR);
          $sentencia -> execute();
        } catch (PDOException $ex) {
          print 'ERROR: ' . $ex -> getMessage();
        }
      }else{
        echo '<br>La id mas moderna: ' . $ultima_id .
              '<br>La id del mas antiguo: ' . $id_mas_antigua . '<br>NO SE BORRA<br>';
      }
    }
  }      //NO SE USA

  // Metodos para las consultas de la WEB

  public static function imprimir($conexion){
    try {
      $sql = 'SELECT * FROM datos WHERE zona = "CARTAGENA"';
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> execute();
      $resultado = $sentencia -> fetchAll();
    } catch (PDOException $ex) {
      print "ERROR: " . $ex -> getMessage();
    }
    // print_r($resultado);
    foreach ($resultado as $lectura){
      echo '<div class="row">
              <div class="col-md-12">
                <div class="panel panel-default">
                  <div class="panel-heading">';
      echo $lectura["codigo"];
      echo        '</div>
                  <div class="panel-body">';
      echo $lectura["dato"] . ' ppm';
      echo        '</div>
                </div>
              </div>
            </div>';
    }
  }

  public static function obtener_zonas($conexion){
    try{
      $sql = 'SELECT DISTINCT zona FROM datos';
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> execute();
      $resultado = $sentencia -> fetchAll();
      if(!empty($resultado)){
        return $resultado;
      }
      return "No hay zonas";
    }catch(PDOException $ex){
      print "ERROR: " . $ex -> getMessage();
    }

  }

  public static function obtener_instalaciones_por_zona($conexion, $zona){
    try{

      $sql = 'SELECT DISTINCT codigo FROM datos WHERE zona = :zona';
      $sentencia = $conexion -> prepare($sql);
      // echo("**" . $zona . "**");
      $sentencia -> bindParam(':zona', trim($zona), PDO::PARAM_STR);
      $sentencia -> execute();
      $resultado = $sentencia -> fetchAll();
      if(!empty($resultado)){
        return $resultado;
      }
      return "No hay instalaciones en " . $zona;
    }catch(PDOException $ex){
      print "ERROR: " . $ex -> getMessage();
    }
  }

  public static function obtener_datos_deposito($conexion, $deposito){
    try {
      // $deposito = '%' . $deposito;
      $sql = 'SELECT * FROM datos WHERE codigo LIKE :deposito';
      $sentencia = $conexion -> prepare($sql);
      // $sentencia -> bindParam(':zona', trim($zona), PDO::PARAM_STR);
      $sentencia -> bindParam(':deposito', $deposito, PDO::PARAM_STR);
      $sentencia -> execute();
      $resultado = $sentencia -> fetchAll();
    } catch (PDOException $ex) {
      print "ERROR: " . $ex -> getMessage();
    }
    if(!empty($resultado)){

      return $resultado;
    }
    return "No hay resultados de datos.";
  }


  // BUSQUEDA AVANZADA|||||||||||||||||||||||||||||||||||||||||||||||
  // MODO INICIAL
  public static function obtener_datos_inicial($conexion){
    try {
      $sql = 'SELECT * FROM datos';
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> execute();
      $resultado = $sentencia -> fetchAll();
    } catch (PDOException $ex) {
      print "ERROR: " . $ex -> getMessage();
    }
    // echo count($resultado);
    if(!empty($resultado)){

      $resultadoProcesado = array();
      $fechaId = '';
      $codigo = '';
      $numero = 0;
      foreach ($resultado as $fila){

        if($fila['idfecha'] > $fechaId){
          $fechaId = $fila['idfecha'];
          $numero = -1;
        }
        if($fila['codigo'] != $codigo){
          $codigo = $fila['codigo'];
          $numero = 0;
        }else{
          $numero ++;
        }
        // echo $numero;
        $resultadoProcesado[$fila['codigo'] . $numero] = $fila;
      }

      return $resultadoProcesado;
    }
    return "No hay resultados de datos.";
  }
  // POR ZONA
  public static function obtener_datos_zona($conexion, $zona){
    try {
      $sql = 'SELECT * FROM datos WHERE zona = :zona';
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> bindParam(':zona', trim($zona), PDO::PARAM_STR);
      $sentencia -> execute();
      $resultado = $sentencia -> fetchAll();
    } catch (PDOException $ex) {
      print "ERROR: " . $ex -> getMessage();
    }
    // echo count($resultado);
    if(!empty($resultado)){
      $resultadoProcesado = array();
      $fechaId = '';
      $codigo = '';
      $numero = 0;
      foreach ($resultado as $fila){

        if($fila['idfecha'] > $fechaId){
          $fechaId = $fila['idfecha'];
          $numero = -1;
        }
        if($fila['codigo'] != $codigo){
          $codigo = $fila['codigo'];
          $numero = 0;
        }else{
          $numero ++;
        }
        // echo $numero;
        $resultadoProcesado[$fila['codigo'] . $numero] = $fila;
      }
      return $resultadoProcesado;
    }
    return "No hay resultados de datos.";
  }
  // POR CONSIGNA
  public static function obtener_datos_consigna($conexion, $max, $min){
    try {
      $sql = 'SELECT * FROM datos WHERE dato > :max OR dato < :min';
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> bindParam(':max', $max, PDO::PARAM_STR);
      $sentencia -> bindParam(':min', $min, PDO::PARAM_STR);
      $sentencia -> execute();
      $resultado = $sentencia -> fetchAll();
    } catch (PDOException $ex) {
      print "ERROR: " . $ex -> getMessage();
    }
    // echo count($resultado);
    if(!empty($resultado)){
      $resultadoProcesado = array();
      $fechaId = '';
      $codigo = '';
      $numero = 0;
      foreach ($resultado as $fila){

        if($fila['idfecha'] > $fechaId){
          $fechaId = $fila['idfecha'];
          $numero = -1;
        }
        if($fila['codigo'] != $codigo){
          $codigo = $fila['codigo'];
          $numero = 0;
        }else{
          $numero ++;
        }
        // echo $numero;
        $resultadoProcesado[$fila['codigo'] . $numero] = $fila;
      }
      return $resultadoProcesado;
    }
    return "No hay resultados de datos.";
  }
  // POR ZONA Y CONSIGNA
  public static function obtener_datos_zona_consigna($conexion, $zona, $max, $min){
    try {
      $sql = 'SELECT * FROM datos WHERE zona = :zona AND (dato > :max OR dato < :min)';
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> bindParam(':zona', $zona, PDO::PARAM_STR);
      $sentencia -> bindParam(':max', $max, PDO::PARAM_STR);
      $sentencia -> bindParam(':min', $min, PDO::PARAM_STR);
      $sentencia -> execute();
      $resultado = $sentencia -> fetchAll();
    } catch (PDOException $ex) {
      print "ERROR: " . $ex -> getMessage();
    }
    // echo count($resultado);
    if(!empty($resultado)){
      $resultadoProcesado = array();
      $fechaId = '';
      $codigo = '';
      $numero = 0;
      foreach ($resultado as $fila){

        if($fila['idfecha'] > $fechaId){
          $fechaId = $fila['idfecha'];
          $numero = -1;
        }
        if($fila['codigo'] != $codigo){
          $codigo = $fila['codigo'];
          $numero = 0;
        }else{
          $numero ++;
        }
        // echo $numero;
        $resultadoProcesado[$fila['codigo'] . $numero] = $fila;
      }
      return $resultadoProcesado;
    }
    return "No hay resultados de datos.";
  }
  // POR ZONA Y DEPOSITO
  public static function obtener_datos_zona_deposito($conexion, $zona, $deposito){
    try {
      $deposito = '%' . $deposito;
      $sql = 'SELECT * FROM datos WHERE codigo LIKE :deposito';
      $sentencia = $conexion -> prepare($sql);
      // $sentencia -> bindParam(':zona', trim($zona), PDO::PARAM_STR);
      $sentencia -> bindParam(':deposito', $deposito, PDO::PARAM_STR);
      $sentencia -> execute();
      $resultado = $sentencia -> fetchAll();
    } catch (PDOException $ex) {
      print "ERROR: " . $ex -> getMessage();
    }
    // echo count($resultado);
    if(!empty($resultado)){
      $resultadoProcesado = array();
      $fechaId = '';
      $codigo = '';
      $numero = 0;
      foreach ($resultado as $fila){

        if($fila['idfecha'] > $fechaId){
          $fechaId = $fila['idfecha'];
          $numero = -1;
        }
        if($fila['codigo'] != $codigo){
          $codigo = $fila['codigo'];
          $numero = 0;
        }else{
          $numero ++;
        }
        // echo $numero;
        $resultadoProcesado[$fila['codigo'] . $numero] = $fila;
      }
      return $resultadoProcesado;
    }
    return "No hay resultados de datos.";
  }
  // POR ZONA DEPOSITO Y CONSIGNA
  public static function obtener_datos_zona_deposito_consigna($conexion, $zona, $deposito, $max, $min){
    try {
      $deposito = '%'.$deposito;
      $sql = 'SELECT * FROM datos WHERE codigo LIKE :deposito AND (dato > :max OR dato < :min)';
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> bindParam(':deposito', $deposito, PDO::PARAM_STR);
      $sentencia -> bindParam(':max', $max, PDO::PARAM_STR);
      $sentencia -> bindParam(':min', $min, PDO::PARAM_STR);
      $sentencia -> execute();
      $resultado = $sentencia -> fetchAll();
    } catch (PDOException $ex) {
      print "ERROR: " . $ex -> getMessage();
    }
    // echo count($resultado);
    if(!empty($resultado)){
      $resultadoProcesado = array();
      $fechaId = '';
      $codigo = '';
      $numero = 0;
      foreach ($resultado as $fila){

        if($fila['idfecha'] > $fechaId){
          $fechaId = $fila['idfecha'];
          $numero = -1;
        }
        if($fila['codigo'] != $codigo){
          $codigo = $fila['codigo'];
          $numero = 0;
        }else{
          $numero ++;
        }
        // echo $numero;
        $resultadoProcesado[$fila['codigo'] . $numero] = $fila;
      }
      return $resultadoProcesado;
    }
    return "No hay resultados de datos.";
  }

}

?>
