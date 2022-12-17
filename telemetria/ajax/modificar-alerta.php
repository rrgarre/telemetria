<?php
include_once "../app/Conexion.inc.php";
include_once "../app/RepositorioFavoritos.inc.php";

// GUARDAMOS DATOS RELEVANTES
// $usuario_id = $_COOKIE['id_usuario'];

$zona = $_GET['zona'];
$deposito = $_GET['deposito'];
$maximo = $_GET['maximo'];
$minimo = $_GET['minimo'];
$notificar = $_GET['notificar'];
$usuario_id = $_GET['id_usuario'];


$alerta = array();
$alerta[] = $zona;
$alerta[] = $deposito;
$alerta[] = $maximo * 100;
$alerta[] = $minimo * 100;
$alerta[] = $notificar;
$alerta[] = $usuario_id;

// $datos = $_GET;

// Miramos si la alerta para dicha instalacion existia
// Para Insertar o Actualizar segun convenga
Conexion::abrir_conexion();
if(RepositorioFavoritos::alerta_existe(Conexion::obtener_conexion(), $usuario_id, $deposito)){
  // Existe ya la alerta
  $alerta[] = 'existe alerta';

  // Modificamos alerta
  $resultado = RepositorioFavoritos::actualizar_alerta(Conexion::obtener_conexion(), $alerta);
  $alerta[] = $resultado;
}else{
  // No existia la alerta
  $alerta[] = 'NO existe alerta';

  // Insertamos nueva alerta
  $resultado = RepositorioFavoritos::insetar_alerta(Conexion::obtener_conexion(), $alerta);
  $alerta[] = $resultado;
}
Conexion::cerrar_conexion();

// $alerta = 'correcto';
$alertaJSON = json_encode($alerta);
echo $alertaJSON;
?>
