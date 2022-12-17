<?php

/**
 *
 */
class Redireccion
{
  // metodo para redirigirnos a una URL
  public static function redirigir($url){
    header('Location: ' . $url, true, 301);
    // cortamos la ejecucion tras redirigir..por los bots xej
    exit();
  }
}

?>
