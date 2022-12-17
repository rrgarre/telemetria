<?php
include_once '../app/config.inc.php';
include_once '../app/simple_html_dom.php';

// Contexto para saltar error SSL
$arrContextOptions=array(
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

// Descargamos el contenido de la pagina del DEPOSITO
$html = file_get_contents(SERVIDOR_TELEMETRIA . substr($_GET['codigo'], 0, 5) . '.html', false, stream_context_create($arrContextOptions));
// $html = file_get_html(SERVIDOR_TELEMETRIA . substr($_GET['codigo'], 0, 5) . '.html');

// $resultado = 'MOSTRANDO DEPOSITO EN DETALLE';
$resultado = array();

$resultado["titulo"] = substr($_GET['codigo'], 6);
$resultado["datos"] = $html;

$resultadoJSON = json_encode($resultado);
echo $resultadoJSON;

?>
