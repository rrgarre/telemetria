<?php
  include_once 'app/Usuario.inc.php';
  /**
   * Clase para trabajar con usuarios
   * Cada clase que tira de tablas de la BBDD, tiene su REPOSITORIO
   */
  class RepositorioUsuario{

      // Obtener todos los usuarios de la tabla usuarios
      public static function obtener_todos($conexion){
       // creamos array de usuarios
       $usuarios = array();
       // Comprobamos si conexion esta disponible
       if(isset($conexion)){
         try {
           // Incluimos la clase Usuario para usar sus metodos
           include_once 'Usuario.inc.php';
           // consulta SQL
           $sql = "SELECT * FROM usuarios";
           // Prevenir inyeccion SQL
           $sentencia = $conexion -> prepare($sql);
           $sentencia -> execute();
           // Volcamos el resultado de la consulta
           $resultado = $sentencia -> fetchAll();
           // comprobamos que haya algun resultado
           if(count($resultado)){
             // Si hay contenido, lo recorremos
             foreach ($resultado as $fila) {
               // Con cada fila, hacemos un $usuario
               $usuarios[] = new Usuario(
                                           $fila['id'],
                                           $fila['nombre'],
                                           $fila['email'],
                                           $fila['password'],
                                           $fila['fecha_registro'],
                                           $fila['activo']
               );
             }
           }else{
             print 'No hay ningún usuario.';
           }
         } catch (PDOException $ex) { //cambiamos tipo de la excep
           print "ERROR: " . $ex -> getMessage();
         }
       }
       // Para acabar, devolvemos el array con todos los usuarios
       return $usuarios;
      }

      // Obtener la cantidad de usuarios
      public static function obtener_numero_usuarios($conexion){
        // variable para contener el resultado
        $total_usuarios = null;
        // comprobamos la conexion
        if(isset($conexion)){
          try {
            $sql = "SELECT COUNT(*) as total FROM usuarios";
            // creamos la sentencia
            $sentencia = $conexion -> prepare($sql);
            // ejecutamos sentencia
            $sentencia -> execute();
            // obtenemos resultado
            // Sólo usamos fetch, no fetchall
            $resultado = $sentencia -> fetch();
            // $resultado['total'], total es el renombramiento en la consulta sql
            $total_usuarios = $resultado['total'];
          } catch (Exception $ex) {
            print 'ERROR: ' . $ex -> getMessage();
          }
          return $total_usuarios;
        }
      }


      // Insertar un usuario
      public static function insertar_usuario($conexion, $usuario){
        $usuario_insertado = false;
        // comprobamos la conexion COMO SIEMPRE
        if(isset($conexion)){
          try {
            //-id no lo introducimos, puesto que se autoincrementara
            //-nombre, email y password, lo pasamos por unos alias necesario en
            //-PDO y que nombramos con : y los mismos nombres que tenemos
            //-Para fecha registro usamos la variable NOW()
            // -Para activo metemos la constante 0 para dejarlo desactivado
            // hasta su verificación
            $sql = 'INSERT INTO usuarios(nombre, email, password, fecha_registro, activo)
                      VALUES(:nombre, :email, :password, NOW(), 0)';

            $sentencia = $conexion -> prepare($sql);
            // Aqui es donde vamos a dar los valores reales a los paramentros que
            // hemos usado en la sentencia sql
            $sentencia -> bindParam(':nombre', $usuario -> get_nombre(), PDO::PARAM_STR);
            $sentencia -> bindParam(':email', $usuario -> get_email(), PDO::PARAM_STR);
            $sentencia -> bindParam(':password', $usuario -> get_password(), PDO::PARAM_STR);

            $usuario_insertado = $sentencia -> execute();
          } catch (PDOException $ex) {
            print 'ERROR: ' . $ex -> getMessage();
          }
          // para saber si hemos podido insertar el usuario
          return $usuario_insertado;
        }
      }

      // Comprobar nombre es unico
      public static function nombre_existe($conexion, $nombre){
        $nombre_existe = true;
        try {
          $sql = "SELECT nombre FROM usuarios WHERE nombre = :nombre";

          $sentencia = $conexion -> prepare($sql);

          $sentencia -> bindParam(':nombre', $nombre, PDO::PARAM_STR);

          $sentencia -> execute();

          $resultado = $sentencia -> fetchAll();

          if(count($resultado)){
            $nombre_existe = true;
          }else{
            $nombre_existe = false;          }
        } catch (PDOException $ex) {
          print 'ERROR: ' . $ex -> getMessage();
        }
        if(isset($conexion)){


          return $nombre_existe;

        }
      }

      // Comprobar email unico
      public static function email_existe($conexion, $email){
        $email_existe = true;

        if(isset($conexion)){
          try {
            $sql = "SELECT email FROM usuarios WHERE email = :email";

            $sentencia = $conexion -> prepare($sql);

            $sentencia -> bindParam(':email', $email, PDO::PARAM_STR);

            $sentencia -> execute();

            $resultado = $sentencia -> fetchAll();

            if(count($resultado)){
              $email_existe = true;
            }else{
              $email_existe = false;            }
          } catch (PDOException $ex) {
            print 'ERROR: ' . $ex -> getMessage();
          }

          return $email_existe;

        }
      }

      // Obtener usuario por email y por email
      public static function obtener_usuario_por_email($conexion, $email){
        $usuario = null;

        if(isset($conexion)){
          try {

            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $sentencia = $conexion -> prepare($sql);
            $sentencia -> bindParam(':email', $email, PDO::PARAM_STR);
            $sentencia -> execute();
            // fetch coge solo un dato, lo que concuerda con la unicidad de
            // los mails en nuestra BBDD
            $resultado = $sentencia -> fetch();

            if(!empty($resultado)){
              // si no está vacío el resultado
              $usuario = new Usuario($resultado['id'],
                                      $resultado['nombre'],
                                      $resultado['email'],
                                      $resultado['password'],
                                      $resultado['fecha_registro'],
                                      $resultado['activo']);
            }

          } catch (PDOException $ex) {
            print "Error: " . $ex -> getMessage();
          }
          // Si ha ido todo bien, mandamos usuario, si no, lo mandaremos vacio
          return $usuario;
        }
      }

      public static function obtener_usuario_por_id($conexion, $id){

        $usuario = null;
        if(isset($conexion)){
          try {
            $sql = 'SELECT * FROM usuarios WHERE id = :id';
            $sentencia = $conexion -> prepare($sql);
            $sentencia -> bindParam(':id', $id, PDO::PARAM_STR);
            $sentencia -> execute();
            $resultado = $sentencia -> fetch();
            if(!empty($resultado)){
              $usuario = new Usuario($resultado['id'],
                                      $resultado['nombre'],
                                      $resultado['email'],
                                      $resultado['password'],
                                      $resultado['fecha_registro'],
                                      $resultado['activo']);
            }
          } catch (PDOException $ex) {
            print 'ERROR: ' . $ex -> getMessage();
          }
          return $usuario;
        }
      }

      // Transaccion para cambiar contraseña mediante proceso de recuperacion
      // y borrar solicitud de recuperacion en el mismo paso
      public static function actualizar_clave_usuario_por_recuperacion($conexion, $usuario){
        $usuario_actualizado = false;
        if(isset($conexion)){
          try {

            $conexion -> beginTransaction();

            // operacion 1
            $sql1 = "UPDATE usuarios SET password = :password WHERE id = :id";
            $sentencia1 = $conexion -> prepare($sql1);
            $sentencia1 -> bindParam(':password', $usuario -> get_password(), PDO::PARAM_STR);
            $sentencia1 -> bindParam(':id', $usuario -> get_id(), PDO::PARAM_STR);
            $sentencia1 -> execute();

            $resultado = $sentencia1 -> rowCount();

            if($resultado > 0){
              $usuario_actualizado = true;
            }

            // operacion 2
            $sql2 = 'DELETE FROM recuperacion_clave WHERE usuario_id = :usuario_id';
            $sentencia2 = $conexion -> prepare($sql2);
            $sentencia2 -> bindParam(':usuario_id', $usuario -> get_id(), PDO::PARAM_STR);
            $sentencia2 -> execute();

            $conexion -> commit();
          }catch(PDOException $ex){
            print 'ERROR: ' . $ex -> getMessage();
            $conexion -> rollback();
          }
        }
        return $usuario_actualizado;
      }

    }


?>
