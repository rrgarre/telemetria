<?php

include_once 'app/Conexion.inc.php';
include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/RepositorioTelemetria.inc.php';
include_once 'app/RepositorioFavoritos.inc.php';
include_once 'app/config.inc.php';
include_once 'app/Deposito.inc.php';
include_once 'app/Rastreador.inc.php';
include_once 'app/GestorFicheros.inc.php';

$tiempo = time();
$contadorEmailsEnviados = 0;

Conexion::abrir_conexion();


// Traemos todos los usuarios |||||||||||||||||||||||||||||||||||
$usuarios = RepositorioUsuario::obtener_todos(Conexion::obtener_conexion());

// Para cada usuario comprobamos las alarmas|||||||||||||||||||
foreach ($usuarios as $usuario){
  $mensajeEmail = '';
  $alertasUsusario = RepositorioFavoritos::obtener_alertas_usuario(Conexion::obtener_conexion(), $usuario -> get_id());

  // Para cada Alerta de cada USUARIO  |||||||||||||||||||||||||

  foreach($alertasUsusario as $alertaUsuario){

    //Si ya estaba activo el semaforo de esta alerta, esta iteracion no
    //generara mensaje
    //gracias a esta variable
    $estabaActivoElSemaforo = False;
    if($alertaUsuario['semaforo'] == 1)
      $estabaActivoElSemaforo = True;





    //variable NO LOCAL para el for de datos
    //Si algun dato esta fuera de consigna se ACTIVA
    //Y ya no permite que una dato posterior desactive el semaforo
    $algunaAlarma = 0;
    echo '--inicializamos algunaAlarma = ' . $algunaAlarma . '<br>';





    // SI hay que notificar
    // Y SI el semaforo aun esta apagado
    // TRAEMOS DATOS del Deposito
    // if($alertaUsuario['alerta'] == 1 && $alertaUsuario['semaforo'] == 0){
    $datos = RepositorioTelemetria::obtener_datos_deposito(Conexion::obtener_conexion(), $alertaUsuario['codigo']);
    echo 'tenemos los datos relacionados con la alerta:<br>';
    var_dump($datos);
    echo '<br><br><br>';

    // Para cada dato, comparamos las consignas
    foreach($datos as $filaDato){
      echo 'dato ' . $filaDato['dato'] . " ppm<br>";

      $max = $alertaUsuario['max']/100;
      $min = $alertaUsuario['min']/100;
      $dato = $filaDato['dato'];

      if(($max < $dato) || ($min > $dato)){

        echo 'el dato esta fuera de consignas<br>';

        // if($alertaUsuario['alerta'] == 1 && $alertaUsuario['semaforo'] == 0){
        if($alertaUsuario['alerta'] == 1){

          //ya hay una alarma y tocamos la variable no local
          //para impedir la ejecucion de "desactivarSemaforo"
          $algunaAlarma = 1;

          // Ponemos a 1 el semaforo
          echo '   activamos semaforo<br>';
          RepositorioFavoritos::activar_semaforo_por_id(Conexion::obtener_conexion(), $alertaUsuario['id']);

          // Construimos el cuerpo del mensaje SI LA ALERTA NO TENIA SEMAFORO A 1
          if(!$estabaActivoElSemaforo){
            $mensajeEmail .= "----------------------\n";
            $mensajeEmail .= "Alarma!\n";
            $mensajeEmail .= $filaDato['codigo'] . "\n";
            if($filaDato['dato'] == -10){
                $mensajeEmail .= "Sin Conexión\n";
            }else{
                $mensajeEmail .= "cloro: " . $filaDato['dato'] . " ppm\n";
            }
            $mensajeEmail .= "----------------------\n";
          }
        }

      }else{
        // Si no estamos rebasando consignas
        // Ponemos el semaforo a 0 si es necesario
        if($alertaUsuario['semaforo'] == 1 && $algunaAlarma==0){
          echo '   desactivamos semaforo<br>';
          RepositorioFavoritos::desactivar_semaforo_por_id(Conexion::obtener_conexion(), $alertaUsuario['id']);
        }
      }
    }

    // }

  }
  // ENVIAMOS EL EMAIL para cada usuario
  if($mensajeEmail != ''){
    $ahora = getdate();
    $minutos = 3;
    echo '++++ ' . str_pad($minutos, 3, '0', STR_PAD_LEFT) . '+++++';
    $fecha = str_pad($ahora['mday'], 2, "0", STR_PAD_LEFT) . '/' .
              str_pad($ahora['mon'], 2, "0", STR_PAD_LEFT) . '/' .
              $ahora['year'] . ' a las ' .
              str_pad($ahora['hours'], 2, "0", STR_PAD_LEFT) . ':' .
              str_pad($ahora['minutes'], 2, "0", STR_PAD_LEFT) . ':' .
              str_pad($ahora['seconds'], 2, "0", STR_PAD_LEFT) . "\n";
    $mensajeEmail = $fecha . $mensajeEmail;
    print_r($mensajeEmail);
    $email_enviado = false;
    $asunto = 'Alarma de cloro';
    $email_enviado = mail($usuario -> get_email(), $asunto, $mensajeEmail);
    // $email_enviado = True;
    if($email_enviado){
      echo 'email enviado a ' . $usuario -> get_email() . '<br>';
    }else{
      echo 'FALLO en el envio del email a ' . $usuario -> get_email() . '<br>';
    }
    $contadorEmailsEnviados++;
  }

  // echo '<br>cambio de usuario<br>';
}

echo '<br>Total de envios: ' . $contadorEmailsEnviados;





Conexion::cerrar_conexion();




$tiempo = time() - $tiempo;
echo '<br>' . $tiempo . ' seg';



?>
