<?php
  /**Clase Estatica, para usar sus metodos sin instanciar*/
  class Conexion{

    private static $conexion;

    /**Metodo para establecer la conexion*/
    public static function abrir_conexion(){
      /**Si no esta la conexion....*/
      if(!isset(self::$conexion)){
        /**abrir conexion es delicado, por eso usamos TRY*/
        try {
          /**El include nos esta inyectando el contenido en bruto del fichero
          seleccionado en la linea de código donde se encuentra.
          De esta forma tenemos centralizado el cotenido de cara a cambios en
          todas las partes donde se usen.
          --Ponemos el include dentro del TRY porque si lo hacemos fuera no
          se vería desde el TRY por su "visibilidad" reducida

          $config['nombre_servidor'] = 'localhost';
          $config['nombre_usuario'] = 'root';
          $config['password'] = '';
          $config['nombre_base_datos'] = 'blog';
          */
          /**Alternativas a incude:
          include
          include_once
          require
          require_once

          -Si no se logra encontrar el fichero:
          include da warning, require ERROR

          -*_once nos asegura php que solo se incluye una vez,
          por si nos equivocamos y lo hacemos más veces en algun punto.
          SIEMPRE usaremos *_once, sólo tiene ventajas
          */
          include_once 'config.inc.php';
          // print 'nombre servidor: ' . NOMBRE_SERVIDOR . '<br>';
          // print 'nombre BD: ' . NOMBRE_BD . '<br>';
          // print 'nombre usuario: ' . NOMBRE_USUARIO . '<br>';
          // print 'password: ' . PASSWORD . '<br>';

          /**Opciones para BBDD
          mysqli    solo mysql y rapido
          pdo       más general para otras bases de datos, versatil

          Usamos PDO, inicializamos con tipo BBDD y nombre del servidor
          variable que se encuentra en config.inc.php que traemos con el
          include anterior*/
          self::$conexion = new PDO('mysql:host='.NOMBRE_SERVIDOR.';
                                      dbname='.NOMBRE_BD,
                                      NOMBRE_USUARIO,
                                      PASSWORD);
          // "mysql:host=$nombre_servidor;dbname=$nombre_base_datos"
          // sin espacios en los =
          self::$conexion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          self::$conexion -> exec("SET CHARACTER SET utf8");
          /**En resumen: intentamos abrir conexion y configurar los
          caracteres a UTF8*/
        } catch (Exception $ex) {
          print "ERROR002XXX: " . $ex -> getMessage() . "<br>";
          /**destruir la conexion*/
          die();
        }
      }
    }

    // Metodo para cerrar la conexion
    public static function cerrar_conexion(){
      if(isset(self::$conexion)){
        // Destruimos la conexion y liberamos su memoria
        self::$conexion = null;
      }
    }

    // Metodo auxiliar para hacer la conexion pública
    public static function obtener_conexion(){
      return self::$conexion;
    }

  }

 ?>
