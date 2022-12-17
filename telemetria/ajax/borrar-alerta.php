<?php
include_once '../app/Conexion.inc.php';
include_once '../app/RepositorioFavoritos.inc.php';

$usuario_id = $_GET['id_usuario'];
$deposito = $_GET['deposito'];

$resultado = array();
$resultado[] = $deposito;
$resultado[] = $usuario_id;

Conexion::abrir_conexion();
$alertaBorrada = RepositorioFavoritos::borrarAlerta(Conexion::obtener_conexion(), $resultado);
Conexion::cerrar_conexion();

// $resultado[] = $alertaBorrada;

$resultadoJSON = json_encode($resultado);
echo $resultadoJSON;

?>
