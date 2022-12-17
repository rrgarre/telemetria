<?php

include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/ControlSesion.inc.php';
include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/RepositorioFavoritos.inc.php';

// include_once 'app/Usuario.inc.php';
// include_once 'app/Entrada.inc.php';
// include_once 'app/Comentario.inc.php';

// include_once 'app/RepositorioUsuario.inc.php';
// include_once 'app/RepositorioEntrada.inc.php';
// include_once 'app/RepositorioComentario.inc.php';
// include_once 'app/RepositorioRecuperacionClave.inc.php';
// include_once 'app/EscritorEntradas.inc.php';

// Este metodo parsea una url y devuelve las diferentes partes separadas
// Para obtener la url usamos _SERVER
// ej localhost/blog/usuarios/rafa
$componentes_url = parse_url($_SERVER['REQUEST_URI']);
// var_dump($componentes_url);
// echo '  1º<br>';

// Recuperamos solo la ruta, que es la direccion quitando la primera parte
// ej   /usuarios/rafa
$ruta = $componentes_url['path'];
// var_dump($ruta);
// echo '  2º<br>';

// metodo que parsea cada parte entre el simbolo o cadena especificada. Ahora /
// ej [0]/[1]/[2]  0='', 1='usuarios', 2='rafa'
$partes_ruta = explode("/", $ruta);
// var_dump($partes_ruta);
// echo '  3º<br>';

//Limpiamos el espacio 0 que está vacio por estar a la izq del 1ºslide
$partes_ruta = array_filter($partes_ruta);
// var_dump($partes_ruta);
// echo '  4º<br>';

// Desplazamos los indices para volver a ocupar el indice 0 con el primer
// util
$partes_ruta = array_slice($partes_ruta, 0);
// var_dump($partes_ruta);
// echo '  5º<br>';

// =================================================

// Obtenemos en ruta_elegida el nombre de la vista a cargar, y la cargamos
// con un include
$ruta_elegida = 'vistas/404.php';

// Si parte 0 es blog estamos en la nuestra pagina
if($partes_ruta[0] == 'telemetria' || $partes_ruta[0] == 'Telemetria'){
  if(count($partes_ruta) == 1){ //si solo hay una parte, es blog, vamos a home

    // Comprobamos si tenemos COOKIES validas para iniciar la sesion automaticamente
    if(isset($_COOKIE['id_usuario']) && isset($_COOKIE['nombre_usuario']) && isset($_COOKIE['hash_usuario'])){

      // TRAEMOS EL USUARIO POR ID PARA VERIFICAR PASS Y NOMBRE
      Conexion::abrir_conexion();
      $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $_COOKIE['id_usuario']);
      Conexion::cerrar_conexion();
      $hash = $usuario -> get_password();
      $nombre = $usuario -> get_nombre();
      // VERIFICAMOS
      if($nombre == $_COOKIE['nombre_usuario'] && $hash == $_COOKIE['hash_usuario']){
        // INICIAMOS SESION
        ControlSesion::iniciar_sesion(
          $_COOKIE['id_usuario'],
          $_COOKIE['nombre_usuario'],
          'OK'
        );
      }
    }

    // Comprobamos si tenemos sesion iniciada para ir a login o home
    if(ControlSesion::sesion_iniciada()){

      // Si además tenemos marcada la opcion de recordar usuario
      // Volvemos a crear las cookies con datos de usuario
      if($_SESSION['recordar_usuario'] == 'OK'){
        // echo 'crear cookies';
        $id = $_SESSION['id_usuario'];
        $nombre = $_SESSION['nombre_usuario'];

        // traemos el usuario de la BBDD por su ID
        Conexion::abrir_conexion();
        $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $id);
        Conexion::cerrar_conexion();

        $hash = $usuario -> get_password();
        // CREAMOS LAS COOKIES CON LOS DATOS QUE ACABAMOS DE TRAER
        setcookie("id_usuario", $id, time()+(60*60*24*365));
        setcookie("nombre_usuario", $nombre, time()+(60*60*24*365));
        setrawcookie("hash_usuario", $hash, time()+(60*60*24*365));
        // echo 'cookies configuradas: ' . $hash . ' y ' . $nombre;
      }else{
        // HEMOS LOGEADO SIN MARCAR RECORDAR
        // Destruimos las COOKIES
        setcookie("id_usuario", "", time()-1);
        setcookie("nombre_usuario", "", time()-1);
        setcookie("hash_usuario", "", time()-1);
      }

      // Miramos si el usuario tiene alertas configuradas
      // En caso afirmativo le llevamos a la gestion de alertas
      // si no, a la pantalla de cloros
      Conexion::abrir_conexion();
      $alertas = RepositorioFavoritos::obtener_alertas_usuario(Conexion::obtener_conexion(), $_SESSION['id_usuario']);
      // print_r($alertas);
      if($alertas != "No hay resultados de datos."){
        $ruta_elegida = "vistas/alertas.php";
      }else{
        $ruta_elegida = "vistas/home.php";
      }
      Conexion::cerrar_conexion();

    }else{
      // NO EXISTE SESSION
      // Vamos a login
      $ruta_elegida = "vistas/login.php";
    }

  }else if(count($partes_ruta) == 2){ //si hay un segundo parametro
    switch($partes_ruta[1]){
      case 'cloros':
        $ruta_elegida = 'vistas/home.php';
        break;
      case 'alertas':
        $ruta_elegida = 'vistas/alertas.php';
        break;
      case 'login':
        $ruta_elegida = 'vistas/login.php';
        break;
      case 'logout':
        $ruta_elegida = 'vistas/logout.php';
        break;
      case 'registro':
        $ruta_elegida = 'vistas/registro.php';
        break;
      case 'recuperar-clave':
        $ruta_elegida = 'vistas/recuperar-clave.php';
        break;
      case 'generar-recuperacion-clave':
        $ruta_elegida = 'scripts/generar-url-secreta.php';
        break;
      case 'crear-alerta':
        $ruta_elegida = 'vistas/crear-alerta.php';
        break;

    }
  }else if(count($partes_ruta == 3)){ //si hay un 3 parametro//////////

    switch($partes_ruta[1]){

      case 'registro-correcto':
        $nombre = $partes_ruta[2];
        $ruta_elegida = 'vistas/registro-correcto.php';
        break;

      case 'entrada':
        $url = $partes_ruta[2];
        // comprobamos que existe la url
        Conexion::abrir_conexion();
        $entrada = RepositorioEntrada::obtener_entrada_por_url(Conexion::obtener_conexion(), $url);
        if($entrada != null){ //si la entrada existe
          $ruta_elegida = 'vistas/entrada.php';
          $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $entrada -> get_autor_id());
          $entradasAzar = RepositorioEntrada::obtener_entradas_al_azar(Conexion::obtener_conexion(), 3);
          $comentarios = RepositorioComentario::obtener_comentarios_por_entrada_id(Conexion::obtener_conexion(), $entrada -> get_id());
        }
        Conexion::cerrar_conexion();
        break;

      case 'gestor':
        switch ($partes_ruta[2]){
          case 'entradas':
            $gestor_actual = 'entradas';
            $ruta_elegida = 'vistas/gestor.php';
            break;

          case 'comentarios':
            $gestor_actual = 'comentarios';
            $ruta_elegida = 'vistas/gestor.php';
            break;

          case 'favoritos':
            $gestor_actual = 'favoritos';
            $ruta_elegida = 'vistas/gestor.php';
            break;

          default:
            break;
        }
      break;

      case 'restablecer-clave':
        $url_secreta = $partes_ruta[2];
        $ruta_elegida = 'vistas/restablecer-clave.php';
        break;
    }

  }//////////////////////////////////////
}
include_once $ruta_elegida;
?>
