<?php
include_once "../app/Conexion.inc.php";
include_once "../app/RepositorioTelemetria.inc.php";

$zona = $_GET['zona'];


Conexion::abrir_conexion();
$resultado = RepositorioTelemetria::obtener_instalaciones_por_zona(Conexion::obtener_conexion(), $zona);
// $resultado = RepositorioTelemetria::obtener_zonas(Conexion::obtener_conexion());
Conexion::cerrar_conexion();


$resultadoJSON = json_encode($resultado);
echo $resultadoJSON;


// $el_array = array("Rafa", "Ruiz", $zona);
// $el_array = array();
// $el_array[] = 'Ra';
// $el_array[] = 'fa';
// $el_array[] = 'Ru';
// $json = json_encode($el_array);
// echo $json;
?>
