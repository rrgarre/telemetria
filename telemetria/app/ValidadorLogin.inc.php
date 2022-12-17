<?php

include_once 'app/RepositorioUsuario.inc.php';

class ValidadorLogin{

  private $usuario;
  private $error;
  private $aviso_inicio;
  private $aviso_cierre;

  public function __construct($email, $password, $conexion){
    $this -> error = '';
    $this -> aviso_inicio = "<br><div class='alert alert-danger' role='alert'>";
    $this -> aviso_cierre = "</div>";
    if(!$this -> variable_iniciada($email) || !$this -> variable_iniciada($password)){
      // si no están alguna de las entradas
      $this -> usuario = null;
      $this -> error = "Debes introducir email y contraseña";
    }else{
      // si las dos entradas han sido introducidas, comprobar
      $this -> usuario = RepositorioUsuario::obtener_usuario_por_email($conexion, $email);
      // Ahora comprobamos que hemos recibido un usuario, es decir,
      // que el email facilitado corresponde a un user
      // y que la pass es correcta
      if(is_null($this -> usuario) || !password_verify($password, $this -> usuario -> get_password())){
        $this -> error = "Datos incorrectos";
      }
    }
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

  //  Método para devolver el usuario verificado
  public function get_usuario(){
    return $this -> usuario;
  }
  // Método para devolver el posible error producido
  public function get_error(){
    return $this -> error;
  }
  // Método para mostrar el error
  public function mostrar_error(){
    if($this -> error !== ''){
      echo $this -> aviso_inicio . $this -> error . $this -> aviso_cierre;
    }
  }

}

?>
