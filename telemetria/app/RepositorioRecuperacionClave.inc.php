<?php

include_once 'app/RecuperacionClave.inc.php';

class RepositorioRecuperacionClave{

  public static function insertar_recuperacion_clave($conexion, $recuperacion_clave){
    // echo $recuperacion_clave -> get_id() . '<br>';
    // echo $recuperacion_clave -> get_usuario_id() . '<br>';
    // echo $recuperacion_clave -> get_enlace() . '<br>';
    // echo $recuperacion_clave -> get_fecha() . '<br>';
    $recuperacion_insertada = false;
    // comprobamos la conexion COMO SIEMPRE
    if(isset($conexion)){
      try {
        $sql = 'INSERT INTO recuperacion_clave(usuario_id, enlace, fecha)
                  VALUES(:usuario_id, :enlace, NOW())';

        $sentencia = $conexion -> prepare($sql);

        // Aqui es donde vamos a dar los valores reales a los paramentros que
        // hemos usado en la sentencia sql
        $sentencia -> bindParam(':usuario_id', $recuperacion_clave -> get_usuario_id(), PDO::PARAM_STR);
        $sentencia -> bindParam(':enlace', $recuperacion_clave -> get_enlace(), PDO::PARAM_STR);

        $recuperacion_insertada = $sentencia -> execute();

      } catch (PDOException $ex) {
        print 'ERROR: ' . $ex -> getMessage();
      }
    }
    // para saber si hemos podido insertar la recuperacion de clave
    return $recuperacion_insertada;
  }

  public static function obtener_recuperacion_por_url($conexion, $url){

    $recuperacion = null;
    if(isset($conexion)){
      try {
        $sql = 'SELECT * FROM recuperacion_clave WHERE enlace = :url';
        $sentencia = $conexion -> prepare($sql);
        $sentencia -> bindParam(':url', $url, PDO::PARAM_STR);
        $sentencia -> execute();
        $resultado = $sentencia -> fetch();

        if(!empty($resultado)){
          $recuperacion = new RecuperacionClave(
                            $resultado['id'],
                            $resultado['usuario_id'],
                            $resultado['enlace'],
                            $resultado['fecha']
                            );
        }

      } catch (PDOException $ex) {
        print 'ERROR ' . $ex -> getMessage();
      }
    }
    
    return $recuperacion;
  }

  public static function existe_recuperacion_por_autor_id($conexion, $usuario_id){
    $existe_peticion = true;
    if(isset($conexion)){
      try{
        $sql = 'SELECT * FROM recuperacion_clave WHERE usuario_id = :usuario_id';
        $sentencia = $conexion -> prepare($sql);
        $sentencia -> bindParam(':usuario_id', $usuario_id, PDO::PARAM_STR);
        $sentencia -> execute();
        $resultado = $sentencia -> fetch();

        if(!$resultado){
          $existe_peticion = false;
        }

      }catch(PDOException $ex){
        print 'ERROR: ' . $ex -> getMessage();
      }
    }
    return $existe_peticion;
  }

}

?>
