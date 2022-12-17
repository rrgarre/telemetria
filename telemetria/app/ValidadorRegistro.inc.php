<?php
  include_once 'RepositorioUsuario.inc.php';
  /**
   *
   */
  class ValidadorRegistro{

    // variables con las etiquetas para las alertas de errores
    private $aviso_inicio;
    private $aviso_cierre;

    private $nombre;
    private $email;
    private $password;
    // private $pass; Es algo desaconsejado por seguridad

    // Errores para usuario
    private $error_nombre;
    private $error_email;
    private $error_password1;
    private $error_password2;

    // constructor
    public function __construct($nombre, $email, $password1, $password2, $conexion){
      $this -> aviso_inicio = "<br><div class='alert alert-danger' role='alert'>";
      $this -> aviso_cierre = "</div>";
      // inicializamos en blanco, para hacerlo bien en los validadores
      $this -> nombre = '';
      $this -> email = '';
      $this -> password = '';
      // Comprobamos las variables con nuestro metodo
      $this -> error_nombre = $this -> validar_nombre($nombre, $conexion);
      $this -> error_email = $this -> validar_email($email, $conexion);
      $this -> error_password1 = $this -> validar_password1($password1);
      $this -> error_password2 = $this -> validar_password2($password1, $password2);
    }

    // comprobamos si esta variable esta iniciada y no esta vacia
    // significa que se ha usado y no tiene espacios en blanco
    private function variable_iniciada($variable){
       if(isset($variable) && !empty($variable)){
         return true;
       }else{
         return false;
       }
     }

    // Metodos para comprobar que CADA variable funciona
    // un metodo por variable
    private function validar_nombre($nombre, $conexion){

       if(!$this -> variable_iniciada($nombre)){
         // Si la variable es erronea
         return "Debes escribir un nombre de usuario.";
       }else{
         // Si es correcto inicializamos
         $this -> nombre = $nombre;
       }

       // comprobar longitud de la cadena o variable
       if(strlen($nombre) < 6 || strlen($nombre) > 24){
         return "El nombre debe contener entre 6 y 24 caracteres";
       }

      //  comprobamos si existe coincidencias con nombres registrados
      // En este contexto de ejecución, tenemos una conexio abierta disponible
      // desde que en registro, llamamos al contructor de esta clase
      if(RepositorioUsuario::nombre_existe($conexion, $nombre)){
        // Si existe, devolvemos el mensaje de error :
        return "El nombre de usuario está siendo utilizado";
      }

       // Si no sucede ningún error
       return '';
     }

    private function validar_email($email, $conexion){

       if(!$this -> variable_iniciada($email)){
         // Si la variable es erronea
         return "Debes escribir un email.";
       }else{
         // Si es correcto inicializamos
         $this -> email = $email;
       }

       //  comprobamos si existe coincidencias con emails registrados
       // En este contexto de ejecución, tenemos una conexio abierta disponible
       // desde que en registro, llamamos al contructor de esta clase
       if(RepositorioUsuario::email_existe($conexion, $email)){
         // Si existe, devolvemos el mensaje de error :
         return "El email de usuario está siendo utilizado".
                  "<br><a href='#'>¿Olvidó contraseña?</a>";
       }

       // Si no sucede ningún error
       return '';
     }

    private function validar_password1($password1){
       if(!$this -> variable_iniciada($password1)){
         return "Debes escribir una contraseña.";
       }
       // No inicializamos porque no tendremos la pssword en variables

       return '';

     }

    private function validar_password2($password1, $password2){
       // comprobamos de nuevo si la password1 era correcta
       if(!$this -> variable_iniciada($password1)){
         return "Debes introducir una contraseña antes.";
       }
       // Comprobamos la contraseña 2
       if(!$this -> variable_iniciada($password2)){
         return "Debes repetir la contraseña.";
       }
       // No inicializamos porque no tendremos la pssword en variables

       // Comprobamos si coinciden la 1 y la 2
       if($password1 != $password2){
         return "Las 2 contraseñas deben coincidir.";
       }
      //  Asignamos la password
       $this -> password = $password1;
       return '';

     }

    //  Validador del formulario en general
    public function registro_valido(){
      if($this -> error_nombre === '' &&
            $this -> error_email === '' &&
            $this -> error_password1 === '' &&
            $this -> error_password2 === ''){
        return true;
      }
      return false;
    }

    // Creamos los metodos geter
    public function get_nombre(){
      return $this -> nombre;
    }
    public function get_email(){
       return $this -> email;
     }
    public function get_password(){
       return $this -> password;
     }

    public function get_error_nombre(){
       return $this -> error_nombre;
     }
    public function get_error_email(){
       return $this -> error_email;
     }
    public function get_error_password1(){
       return $this -> error_password1;
     }
    public function get_error_password2(){
       return $this -> error_password2;
     }

    //  metodos para imprimir en html
    public function mostrar_nombre_en_registro(){
      if($this -> nombre !== ''){
        echo 'value="'.$this -> nombre.'"';
      }
    }
    public function mostrar_error_nombre_en_registro(){
      if($this -> error_nombre !== ''){
        echo $this -> aviso_inicio . $this -> error_nombre . $this -> aviso_cierre;
      }
    }
    public function mostrar_email_en_registro(){
      if($this -> email !== ''){
        echo 'value="' . $this -> email . '"';
      }
    }
    public function mostrar_error_email_en_registro(){
      if($this -> error_email !== ''){
        echo $this -> aviso_inicio . $this -> error_email . $this -> aviso_cierre;
      }
    }
    public function mostrar_error_password1_en_registro(){
      if($this -> error_password1 !== ''){
        echo $this -> aviso_inicio . $this -> error_password1 . $this -> aviso_cierre;
      }
    }
    public function mostrar_error_password2_en_registro(){
      if($this -> error_password2 !== ''){
        echo $this -> aviso_inicio . $this -> error_password2 . $this -> aviso_cierre;
      }
    }
  }

 ?>
