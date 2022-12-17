<?php

  // info Base de Datos
  // define('NOMBRE_SERVIDOR', 'localhost');
  // define('NOMBRE_USUARIO', 'root');
  // define('PASSWORD', 'root');
  // define('NOMBRE_BD', 'telemetria');

  //HOST garre.000webhostapp
  // define('NOMBRE_SERVIDOR', 'localhost');
  // define('NOMBRE_USUARIO', 'id2266701_telemetria');
  // define('PASSWORD', '123qweasd');
  // define('NOMBRE_BD', 'id2266701_telemetria');

  //HOST garre.tech
  // define('NOMBRE_SERVIDOR', 'localhost');
  // define('NOMBRE_USUARIO', 'u954015972_telem');
  // define('PASSWORD', 'EgSS7P4lLXJU');
  // define('NOMBRE_BD', 'u954015972_telem');

  //HOST garre-cloros.esy.es
  //define('NOMBRE_SERVIDOR', 'localhost');
  //define('NOMBRE_USUARIO', 'u593663904_telem');
  //define('PASSWORD', 'dJssvsSJDUl2');
  //define('NOMBRE_BD', 'u593663904_telem');
  
  //HOST garre-cl.tech
  // define('NOMBRE_SERVIDOR', 'localhost');
  // define('NOMBRE_USUARIO', 'u598717880_cloros');
  // define('PASSWORD', 'a7asd7jKJH6');
  // define('NOMBRE_BD', 'u598717880_cloros');

  //HOST aqua-cl.online
  // Falló el localhost en Diciembre 2022, se cambia a 127.0.0.1
  // define('NOMBRE_SERVIDOR', 'localhost');
  define('NOMBRE_SERVIDOR', '127.0.0.1');
  define('NOMBRE_USUARIO', 'u598717880_cloros');
  define('PASSWORD', 'a7asd7jKJH6');
  define('NOMBRE_BD', 'u598717880_cloros');

  // RUTAS
  // define("SERVIDOR", "http://localhost/telemetria");
  //define("SERVIDOR", "http://garre-cloros.esy.es/telemetria");
  // define("SERVIDOR", "http://garre-cl.tech/telemetria");
  define("SERVIDOR", "http://aqua-cl.online/telemetria");
  //define("SERVIDOR", "");
  // define("SERVIDOR", "http://192.168.1.10/telemetria");

  define("RUTA_REGISTRO", SERVIDOR."/registro");
  define("RUTA_CLOROS", SERVIDOR."/cloros");
  define("RUTA_ALERTAS", SERVIDOR."/alertas");
  // define("RUTA_ENTRADAS", SERVIDOR."/entradas");
  define("RUTA_FAVORITOS", SERVIDOR."/favoritos");
  // define("RUTA_AUTORES", SERVIDOR."/autores");
  define("RUTA_REGISTRO_CORRECTO", SERVIDOR."/registro-correcto");
  define("RUTA_LOGIN", SERVIDOR."/login");
  define("RUTA_LOGOUT", SERVIDOR."/logout");
  // define("RUTA_ENTRADA", SERVIDOR."/entrada");
  // define("RUTA_GESTOR", SERVIDOR."/gestor");
  // define("RUTA_GESTOR_ENTRADAS",RUTA_GESTOR."/entradas");
  // define("RUTA_GESTOR_COMENTARIOS", RUTA_GESTOR."/comentarios");
  define("RUTA_GESTOR_FAVORITOS", RUTA_GESTOR."/favoritos");
  // define("RUTA_NUEVA_ENTRADA", SERVIDOR."/nueva-entrada");
  // define("RUTA_BORRAR_ENTRADA", SERVIDOR."/borrar-entrada");
  // define("RUTA_EDITAR_ENTRADA", SERVIDOR."/editar-entrada");
  define("RUTA_RECUPERAR_CLAVE", SERVIDOR."/recuperar-clave");
  define("RUTA_GENERAR_URL_SECRETA", SERVIDOR."/generar-recuperacion-clave");
  // define("RUTA_PRUEBA_MAIL", SERVIDOR."/mail");
  define("RUTA_RESTABLECER_CLAVE", SERVIDOR."/restablecer-clave");
  // define("RUTA_BUSCAR", SERVIDOR."/buscar");
  define("RUTA_CREAR_ALERTA", SERVIDOR."/crear-alerta");
  define("RUTA_INFO", SERVIDOR."/Documentacion");

  // RUTAS FICHEROS
  // define("CARPETA_HISTORICOS", "Historicos/");
  define("CARPETA_HISTORICOS", "D:\\Proyectos\\tele001\\historicos\\");


  // RUTAS EXTERNAS
  define("SERVIDOR_TELEMETRIA", "https://telemetria.mct.es/CC/");
  // define("SERVIDOR_TELEMETRIA", "Pagina/");

  define("RUTA_CSS", SERVIDOR.'/css/');
  define("RUTA_JS", SERVIDOR . '/js/');

 ?>
