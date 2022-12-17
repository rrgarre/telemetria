<?php
class RepositorioFavoritos{
  // Comprobar si una alerta ya existe
  public static function alerta_existe($conexion, $id, $deposito){
    $existe_alerta = false;

    try {
      $sql = "SELECT id FROM favoritos WHERE usuario_id = :id AND codigo = :deposito";

      $sentencia = $conexion -> prepare($sql);

      $sentencia -> bindParam(':id', $id, PDO::PARAM_STR);
      $sentencia -> bindParam(':deposito', $deposito, PDO::PARAM_STR);

      $sentencia -> execute();

      $resultado = $sentencia -> fetchAll();

      if(count($resultado)){
        $existe_alerta = true;
      }else{
        $existe_alerta = false;
      }
    } catch (PDOException $ex) {
      print 'ERROR: ' . $ex -> getMessage();
    }

    return $existe_alerta;
  }

  // Obtener todas las alertas de un usuario
  public static function obtener_alertas_usuario($conexion, $id){
    try {
      $sql = 'SELECT * FROM favoritos WHERE usuario_id = :id';
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> bindParam(':id', trim($id), PDO::PARAM_STR);
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

  // Agregar alerta nueva
  public static function insetar_alerta($conexion, $alerta){
    $alerta_insertada = false;
    if(isset($conexion)){
      try {
        if($alerta[4] == 'true'){
          $notificar = 1;
        }else{
          $notificar = 0;
        }
        $sql = 'INSERT INTO favoritos(usuario_id, codigo, alerta, max, min, semaforo)
                  VALUES(:usuario_id, :codigo, :alerta, :max, :min, 0)';

        $sentencia = $conexion -> prepare($sql);
        $sentencia -> bindParam(':usuario_id', $alerta[5], PDO::PARAM_STR);
        $sentencia -> bindParam(':codigo', $alerta[1], PDO::PARAM_STR);
        $sentencia -> bindParam(':alerta', $notificar, PDO::PARAM_STR);
        $sentencia -> bindParam(':max', $alerta[2], PDO::PARAM_STR);
        $sentencia -> bindParam(':min', $alerta[3], PDO::PARAM_STR);

        // return $alerta;
        $alerta_insertada = $sentencia -> execute();
      } catch (PDOException $ex) {
        print 'ERROR: ' . $ex -> getMessage();
      }
      return $alerta_insertada;
    }
  }

  // Actualizamos laerta
  public static function actualizar_alerta($conexion, $alerta){
    $alerta_insertada = false;
    if(isset($conexion)){
      try {
        // return $alerta[4];
        if($alerta[4] == 'true'){
          // return 'notificarrr ' . $alerta[4];
          $notificar = 1;
        }else{
          // return ' NOnotificarrr ' . $alerta[4];
          $notificar = 0;
        }
        $sql = 'UPDATE favoritos SET alerta = :alerta, max = :max, min = :min
          WHERE codigo = :codigo AND usuario_id = :usuario_id';

        $sentencia = $conexion -> prepare($sql);
        $sentencia -> bindParam(':usuario_id', $alerta[5], PDO::PARAM_STR);
        $sentencia -> bindParam(':codigo', $alerta[1], PDO::PARAM_STR);
        $sentencia -> bindParam(':alerta', $notificar, PDO::PARAM_STR);
        $sentencia -> bindParam(':max', $alerta[2], PDO::PARAM_STR);
        $sentencia -> bindParam(':min', $alerta[3], PDO::PARAM_STR);

        // return $alerta;
        $alerta_insertada = $sentencia -> execute();
      } catch (PDOException $ex) {
        print 'ERROR: ' . $ex -> getMessage();
      }
      return $alerta_insertada;
    }
  }

  // Borrar alerta
  public static function borrarAlerta($conexion, $alerta){
    try {
      $sql = 'DELETE FROM favoritos WHERE usuario_id = :usuario_id AND codigo = :codigo';
      $sentencia = $conexion -> prepare($sql);
      $sentencia -> bindParam(':usuario_id', $alerta[1], PDO::PARAM_STR);
      $sentencia -> bindParam('codigo', $alerta[0], PDO::PARAM_STR);
      $sentencia -> execute();
    } catch (PDOException $ex) {
      print 'ERROR: ' . $ex -> getMessage();
    }

    return 'alerta borrada';
  }

  // activar activar_semaforo_por_id
  public static function activar_semaforo_por_id($conexion, $id){

    $alerta_actualizada = false;
    if(isset($conexion)){
      try {
        $sql = "UPDATE favoritos SET semaforo = 1 WHERE id = :id";
        $sentencia = $conexion -> prepare($sql);
        $sentencia -> bindParam(':id', $id, PDO::PARAM_STR);
        $sentencia -> execute();
        $resultado = $sentencia -> rowCount();

        if($resultado > 0){
          $alerta_actualizada = true;
        }

      }catch(PDOException $ex){
        print 'ERROR: ' . $ex -> getMessage();
      }
    }
    return $alerta_actualizada;
  }


  // desactivar_semaforo_por_id
  public static function desactivar_semaforo_por_id($conexion, $id){

    $alerta_actualizada = false;
    if(isset($conexion)){
      try {
        $sql = "UPDATE favoritos SET semaforo = 0 WHERE id = :id";
        $sentencia = $conexion -> prepare($sql);
        $sentencia -> bindParam(':id', $id, PDO::PARAM_STR);
        $sentencia -> execute();
        $resultado = $sentencia -> rowCount();

        if($resultado > 0){
          $alerta_actualizada = true;
        }

      }catch(PDOException $ex){
        print 'ERROR: ' . $ex -> getMessage();
      }
    }
    return $alerta_actualizada;
  }
}

?>
