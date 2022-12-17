<?php

class ValidadorCambioClave{

  private $aviso_inicio;
  private $aviso_cierre;

  private $password;

  private $error_password1;
  private $error_password2;

  public function __construct($password1, $password2){

    $this -> aviso_inicio = "<br><div class='alert alert-danger' role='alert'>";
    $this -> aviso_cierre = "</div>";

    // inicializamos en blanco, para hacerlo bien en los validadores
    $this -> password = '';

    // Comprobamos las variables con nuestro metodo
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

   private function validar_password1($password1){
      if(!$this -> variable_iniciada($password1)){
        return "Debes escribir una contraseña.";
      }
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
   public function cambio_clave_valido(){
     if($this -> error_password1 === '' && $this -> error_password2 === ''){
       return true;
     }
     return false;
   }

   // Creamos los metodos geter
   public function get_password(){
      return $this -> password;
    }

   public function get_error_password1(){
      return $this -> error_password1;
    }
   public function get_error_password2(){
      return $this -> error_password2;
    }

    //  metodos para imprimir en html
    public function mostrar_error_password1_en_form(){
      if($this -> error_password1 !== ''){
        echo $this -> aviso_inicio . $this -> error_password1 . $this -> aviso_cierre;
      }
    }
    public function mostrar_error_password2_en_form(){
      if($this -> error_password2 !== ''){
        echo $this -> aviso_inicio . $this -> error_password2 . $this -> aviso_cierre;
      }
    }
}

?>
