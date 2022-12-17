<?php

  // info Base de Datos
  define('NOMBRE_SERVIDOR', 'localhost');
  define('NOMBRE_USUARIO', 'root');
  define('PASSWORD', 'root');
  define('NOMBRE_BD', 'blog');

  // info Base de Datos para HOST
  // define('NOMBRE_SERVIDOR', 'localhost');
  // define('NOMBRE_USUARIO', 'id2266701_localhost');
  // define('PASSWORD', 'rootroot');
  // define('NOMBRE_BD', 'id2266701_blog');

  // RUTAS
  define("SERVIDOR", "http://localhost/blog");
  // define("SERVIDOR", "https://garre.000webhostapp.com/blog");
  // define("SERVIDOR", "http://192.168.1.10/blog");
  // define("SERVIDOR", "http://95.23.196.29/blog");
  define("RUTA_REGISTRO", SERVIDOR."/registro");
  define("RUTA_ENTRADAS", SERVIDOR."/entradas");
  define("RUTA_FAVORITOS", SERVIDOR."/favoritos");
  define("RUTA_AUTORES", SERVIDOR."/autores");
  define("RUTA_REGISTRO_CORRECTO", SERVIDOR."/registro-correcto");
  define("RUTA_LOGIN", SERVIDOR."/login");
  define("RUTA_LOGOUT", SERVIDOR."/logout");
  define("RUTA_ENTRADA", SERVIDOR."/entrada");
  define("RUTA_GESTOR", SERVIDOR."/gestor");
  define("RUTA_GESTOR_ENTRADAS",RUTA_GESTOR."/entradas");
  define("RUTA_GESTOR_COMENTARIOS", RUTA_GESTOR."/comentarios");
  define("RUTA_GESTOR_FAVORITOS", RUTA_GESTOR."/favoritos");
  define("RUTA_NUEVA_ENTRADA", SERVIDOR."/nueva-entrada");
  define("RUTA_BORRAR_ENTRADA", SERVIDOR."/borrar-entrada");
  define("RUTA_EDITAR_ENTRADA", SERVIDOR."/editar-entrada");
  define("RUTA_RECUPERAR_CLAVE", SERVIDOR."/recuperar-clave");
  define("RUTA_GENERAR_URL_SECRETA", SERVIDOR."/generar-recuperacion-clave");
  define("RUTA_PRUEBA_MAIL", SERVIDOR."/mail");
  define("RUTA_RESTABLECER_CLAVE", SERVIDOR."/restablecer-clave");
  define("RUTA_BUSCAR", SERVIDOR."/buscar");
  define("RUTA_CALCULADORA", SERVIDOR."/calculadora");

  // RECURSOS
  define("RUTA_CSS", SERVIDOR.'/css/');
  define("RUTA_JS", SERVIDOR . '/js/');
 ?>
