<?php
// if(preg_match_all('|CL.*?</b></td><td>(.*?) ppm</td>|is', $contenido_pagina, $cap))
// {}
class Rastreador{
  // Nombre zona actual
  private $nombre_zona;
  // Nombre Deposito actual
  private $nombre_deposito;
  // Si el Deposito actual es del servidio
  private $servicio;
  // Contiene el listado de depositos del servicio
  private $contenido_servicio;
  // contien la página de los links a ZONAS
  private $contenido_pagina_zonas = '';
  // Contiene la página de los links a cada deposito
  private $contenido_pagina_depositos = '';
  // Contenido de un deposito
  private $contenido_deposito = '';
  // todas las urls de TODAS LAS ZONAS
  private $urls_zonas = '';
  // todas las urls de TODOS LOS DEPOSITOS
  private $urls_depositos = '';
  // Fecha de ultima actualizacion del Server Telemetria
  private $fecha_telemetria = '';
  // Año - Mes - Dia para fichero de Historico
  private $fecha_historico = '';
  // ID de la fecha de la ultima actualizacion
  private $id_fecha = '';
  // Ids de datos introducidos en la ultima actualizacion
  private $ids_rango = array("menor" => '', "mayor" => '');
  //contexto para saltar certificado SSL
  private $arrContextOptions=array(
    "ssl"=>array(
      "verify_peer"=>false,
      "verify_peer_name"=>false,
      'postman-token' => 'cfc4adbb-d8b1-4302-3ca3-13596940ea16',
      'cache-control' => 'no-cache',
      'content-type' => 'application/x-www-form-urlencoded'
    ),
    'http'=>array(
          'user_agent' => 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Mobile Safari/537.36',
    ),
  );

  // Constructor
  // public function __construct(){}

  // Comprueba si existe una nueva actualización de los datos
  // en caso afirmativo, ordena el ingreso de esta fecha en la BBDD
  public function existe_actualizacion($conexion){
    $numerCheck = 1;    //Eliminar
    echo '<br>CHECK: 2.' . $numerCheck++ . '<br>';    //Eliminar
    echo '<br>server:.' . SERVIDOR_TELEMETRIA . '<br>';    //Eliminar

    // Obtenemos la fecha del SERVIDOR_TELEMETRIA
    echo 'fecha_telemetria: ' . $this->fecha_telemetria; //Eliminar
    $this -> obtener_fecha_telemetria();
    echo 'fecha_telemetria: ' . $this->fecha_telemetria; //Eliminar
    echo '<br>CHECK: 2.' . $numerCheck++ . '<br>';    //Eliminar
    // La comparamos con la de la BBDD
    // echo RepositorioTelemetria::obtener_fecha($conexion) . '<br>';
    // echo $this -> fecha_telemetria . '<br>';
    if($this -> fecha_telemetria !== RepositorioTelemetria::obtener_fecha($conexion)){

      echo '<br>CHECK: 2.' . $numerCheck++ . '<br>';    //Eliminar
      // echo 'Necesita actualizar<br>';
      $fecha_insertada = RepositorioTelemetria::agregar_fecha($conexion, $this -> fecha_telemetria);
      echo '<br>CHECK: 2.' . $numerCheck++ . '<br>';    //Eliminar
      if($fecha_insertada){
        echo '<br>CHECK: 2.' . $numerCheck++ . '<br>';    //Eliminar
        // echo 'La fecha ha sido añadida<br>';
        // Obtenemos la id de la fecha añadida
        $this -> id_fecha = RepositorioTelemetria::obtener_idFecha($conexion, $this -> fecha_telemetria);
        echo '<br>CHECK: 2.' . $numerCheck++ . '<br>';    //Eliminar
        // Obtenemos la parte de la fecha sin la hora
        // Para nombrar los ficheros de historicos
        $this -> fecha_historico = $this -> obtener_fecha_historico();
        echo '<br>CHECK: 2.' . $numerCheck++ . '<br>';    //Eliminar
        // echo '<br>' . $this -> fecha_historico . '<br>';
      }
      return true;
    }
    echo 'No requiere actualizar<br>';
    return false;
  }

  // Metodo para guardar el rango de ids de los datos
  // que van a quedarse antiguo tras actualizar
  public function obtener_rango_ids_antiguas($conexion){
    $this -> ids_rango["menor"] = RepositorioTelemetria::obtener_menor_id_datos($conexion);
    $this -> ids_rango["mayor"] = RepositorioTelemetria::obtener_mayor_id_datos($conexion);
    // print_r($this -> ids_rango);
  }

  // Método que descarga todos los datos y ordena el ingreso en la BBDD
  public function reconstruir($conexion){
    // Cargamos el contenido de la lista de depositos del servicio
    $this -> contenido_servicio = file_get_contents('DepositosServicio/depositosServicio.txt');
    // echo '******' . $this -> contenido_servicio;
    $this -> descargar_urls_zonas();
    // Recorremos cada zona para descargar la lista de depositos
    foreach ($this -> urls_zonas[1] as $url_zona) {
      set_time_limit(200);
      // print_r( "<br>" . $url_zona);

      // Obtenemos urls para cada deposito de la zona actual
      // Y definimos el nombre_zona actual
      $this -> descargar_urls_depositos(SERVIDOR_TELEMETRIA . $url_zona);

      // Recorremos cada pagina de depositos para descargar cada deposito
      foreach ($this -> urls_depositos[1] as $url_dep) {
        // Aqui llamo al nuevo metodo obtener_depositos
        $deposito = $this -> obtener_deposito(SERVIDOR_TELEMETRIA . $url_dep);
        // echo '<br>-- ' . $deposito -> get_servicio() . ' --<br>';
        // Pasamos el deposito al Repositorio para ingresar los datos a BBDD
        $deposito_insertado = RepositorioTelemetria::agregar_deposito($conexion, $deposito);
        if($deposito_insertado){
          // echo 'El depósito ha sido añadido<br><br>';
          // echo '<br>FIN DE DEPOSITO_______________________________________<br><br>';
        }
      }
    }

  }

  // Metodo para almacenar datos en los historicos
  public function guardar_datos_antiguos($conexion){
    if($this -> ids_rango["menor"] !== $this -> ids_rango["mayor"]){
      // echo '<br>  guardar datos<br>';
      $resultado = RepositorioTelemetria::guardar_datos_antiguos($conexion, $this -> ids_rango);
    }else{
      // echo '<br>  No hacer nada<br>';
    }
    GestorFicheros::guardar_datos_antiguos($this -> fecha_historico, $this -> fecha_telemetria, $resultado);
  }

  // Metodo para borrar datos antiguos
  public function borrar_datos_antiguos($conexion){
    if($this -> ids_rango["menor"] !== $this -> ids_rango["mayor"]){
      // echo '  borrar datos  ';
      RepositorioTelemetria::borrar_datos_antiguos($conexion, $this -> ids_rango);
    }else{
      // echo '  No hacer nada  ';
    }
  }

  // Método para obtener la fecha de última actualizacion de los datos en el
  // servidor de telemetria
  private function obtener_fecha_telemetria(){
    $this -> descargar_urls_zonas();
    echo $this->contenido_pagina_zonas;  //Eliminar
    $this -> descargar_urls_depositos(SERVIDOR_TELEMETRIA . $this -> urls_zonas[1][0]);
    // devolvemos la fecha que obtenemos
    $this -> descargar_contenido_deposito(SERVIDOR_TELEMETRIA . $this -> urls_depositos[1][0]);
    preg_match('|Generado: (../../.......:..:..)|is', $this -> contenido_deposito, $cap);
    $this -> fecha_telemetria = $cap[1];
  }

  // Metodo para obtener la parte de la fecha referente al dia
  private function obtener_fecha_historico(){
    preg_match('|(..)/../....|is', $this -> fecha_telemetria, $cap);
    $dia = $cap[1];
    preg_match('|../(..)/....|is', $this -> fecha_telemetria, $cap);
    $mes = $cap[1];
    preg_match('|../../(....)|is', $this -> fecha_telemetria, $cap);
    $anno = $cap[1];
    return $anno . '-' . $mes . '-' . $dia;
  }

  // Método para descargar URLs para diferentes ZONAS
  private function descargar_urls_zonas(){
    // $this -> contenido_pagina_zonas = 'URLs para las zonas';




    //Eliminar lo anterior
    //Desde aqui codigo correcto sin comentar

    $this -> contenido_pagina_zonas = file_get_contents(SERVIDOR_TELEMETRIA . "index.html", false, stream_context_create($this->arrContextOptions));
    preg_match_all('|<tr> <td> <a href=(.*?)><b>|is',
                        $this -> contenido_pagina_zonas,
                        $this -> urls_zonas);
  }

  // Método para descargar URLs para diferentes depositos
  private function descargar_urls_depositos($url_pagina_dep){
    $this -> contenido_pagina_depositos = file_get_contents($url_pagina_dep, false, stream_context_create($this->arrContextOptions));
    // Detectamos el nombre_zona
    preg_match_all('|ZONA (.*?)</b>|is',
                        $this -> contenido_pagina_depositos,
                        $nombre_zona);
    $this -> nombre_zona = $nombre_zona[1][0];
    // Detectamos las urls de depositos
    preg_match_all('|<tr> <td> <a href=(.*?)><b>|is',
                        $this -> contenido_pagina_depositos,
                        $this -> urls_depositos);
    // print_r($this -> contenido_pagina_depositos . '<br>');
    // foreach ($this -> urls_depositos[1] as $url_dep) {
    //   print_r("    " . $url_dep . "<br>");
    // }
  }

  // Método para descargar el contenido de la pagina de 1 deposito
  private function descargar_contenido_deposito($url_dep){
    $this -> contenido_deposito = file_get_contents($url_dep, false, stream_context_create($this->arrContextOptions));
  }

  // Método para fabricar y rellenar el objeto Deposito
  private function obtener_deposito($url_dep){
    // Descargamos el contenido de la url del deposito
    $this -> contenido_deposito = file_get_contents($url_dep, false, stream_context_create($this->arrContextOptions));

    // Detectamos nombre_deposito
    $this -> detectar_nombre_deposito();

    // Detectamos si es del servicio
    $this -> detectar_servicio();

    // instanciamos un objeto deposito con zona y codigoDeposito
    $dep = new Deposito($this -> nombre_zona,
                        $this -> nombre_deposito,
                        $this -> servicio,
                        $this -> id_fecha);

    // Buscamos y Añadimos los cloros
    $dep -> set_cloros($this -> obtener_cloros());

    $dep -> set_turbis($this -> obtener_turbis());

    return $dep;
  }

  // Detectamos el nombre_deposito actual
  private function detectar_nombre_deposito(){
    preg_match_all('|<title>(.*?)</title>|is',
                        $this -> contenido_deposito,
                        $nombre_deposito);
    if(!empty($nombre_deposito[1][0])){
      $this -> nombre_deposito = $nombre_deposito[1][0];
    }else{
      $this -> nombre_deposito = 'Fuera de servicio';
    }
  }
  // Detectamos si pertenece al servicio
  private function detectar_servicio(){
    // $this -> servicio = strpos($this -> contenido_servicio, $this -> nombre_deposito);
    $this -> servicio = strpos($this -> contenido_servicio, substr($this -> nombre_deposito, 0, 5));
    // echo $this -> contenido_servicio;
    echo "<br>";
    if($this -> servicio){
      echo "NUESTRO";
      $this -> servicio = 1;
    }else{
      echo "No";
      $this -> servicio = 0;
    }
    echo "<br>";
    echo substr($this -> nombre_deposito, 0, 5);
    echo "<br>********************";
    // if($this -> servicio === true){
    //   echo $this -> nombre_deposito . ' esta en el servicio<br>';
    // }
  }

  // Método para sacar datos de cloro de cada pagina de deposito
  private function obtener_cloros(){

    // preg_match_all("/<td>((....)|(......)) ppm/is",
    //                     $this -> contenido_deposito,
    //                     $datos_cloros);
    preg_match_all("/<tr><td><b>CL(?:\s+\w+)?<\/b><\/td><td>((....)|(......))/",
                        $this -> contenido_deposito,
                        $datos_cloros);
    return $datos_cloros[1];
  }
  // Método para sacar datos de cloro de cada pagina de deposito
  private function obtener_turbis(){

    // preg_match_all("/<td>((....)|(......)) ntu/is",
    //                     $this -> contenido_deposito,
    //                     $datos_cloros);
    preg_match_all("/<tr><td><b>TU(?:\s+\w+)?<\/b><\/td><td>((....)|(......))/",
                        $this -> contenido_deposito,
                        $datos_cloros);
    return $datos_cloros[1];
  }



}

?>
