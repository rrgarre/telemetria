<?php
include_once '../app/Conexion.inc.php';
include_once '../app/RepositorioTelemetria.inc.php';
include_once '../app/RepositorioFavoritos.inc.php';

define('ZONA_DEF', 'Elige una Zona');
define('DEPOSITO_DEF', 'Instalación');
define('CONSIGNA_DEF', 'NO');

/*************************************************************
TURBIDEZ MAXIMA
*************************************************************/
$turbidezMax = 0.7;


// RECOGEMOS PARAMETROS DE BUSQUEDA
// Y marcamos si son valores introducidos o por defecto
$zona = $_POST['zona'];
$deposito = $_POST['deposito'];
if($_POST['consigna'] == 'Consignas'){
  $consigna = 'NO';
}else{
  $consigna = 'SI';
}
$maximo = $_POST['maximo']/100;
$minimo = $_POST['minimo']/100;
$id_usuario = $_POST['id_usuario'];

// COMPROBACION DE LOS DATOS RECIBIDOS||||||||||||||||||||||||||||||||||||||
// echo 'La zona es: ' . $zona . '<br>';
// echo 'La instalacion es: ' . $deposito . '<br>';
// echo 'Consignas: ' . $consigna . '<br>';
// echo 'El máximo es: ' . $maximo . '<br>';
// echo 'El mínimo es: ' . $minimo . '<br>';
// if($zona == ZONA_DEF){
//   echo '<hr>Zona por defecto <br>';
// }else{
//   echo '<hr>Zona cambiada <br>';
// }
// if($deposito == DEPOSITO_DEF){
//   echo 'Deposito por defecto <br>';
// }else{
//   echo 'Deposito cambiado <br>';
// }
// if($consigna == CONSIGNA_DEF){
//   echo 'No aplica consignas <br>';
// }else{
//   echo 'Maximo: ' .$maximo. ' <br>';
//   echo 'Minimo: ' .$minimo. ' <br>';
// }
//FIN COMPROBACION DE LOS DATOS RECIBIDOS||||||||||||||||||||||||||||||||||||||

// Pedimos los datos al Repositorio
// Segun la cofiguracion de los parametros hacemos diferentes llamadas
Conexion::abrir_conexion();
// Según que parametros están por defecto y cuales no, Llamamos
// a obtener datos con mas o menos parametros, sobrecargando el método
if($zona == 'alertas'){
  $resultados = array();
  $contadorAlerta = 0;
  $alertasUsusario = RepositorioFavoritos::obtener_alertas_usuario(Conexion::obtener_conexion(), $id_usuario);
  // print_r($alertasUsusario);
  foreach($alertasUsusario as $fila){
    $dato = RepositorioTelemetria::obtener_datos_deposito(Conexion::obtener_conexion(), $fila['codigo']);
    foreach($dato as $deposito){
      $deposito['contador-alerta'] = $contadorAlerta;
      // echo '<br>' . $fila['max'] . '<br>';
      // echo $deposito['dato'] . '<br>';


/*******************************************************************************************************
VEMOS SI EL DATO CL o TU SON ALERTAS Y MARCAMOS FILA PARA PINTAR EN MODO ALERTA (ROJO) EN EL PANEL DE RESULTADO
*******************************************************************************************************/
      // Vemos si el dato es ALARMA
      if($deposito['tipo'] == 'CL'){
        if(($fila['max'] < $deposito['dato']*100) || ($fila['min'] > $deposito['dato']*100)){
          // echo 'alerta';
          $deposito['alarma'] = 1;
        }else{
          $deposito['alarma'] = 0;
        }
      }else if($deposito['tipo'] == 'TU'){
        /////////////// Activar para alertas de TURBIDEZ /////////////////
        if($deposito['dato'] == '-10' || $deposito['dato'] >= $turbidezMax){
          $deposito['alarma'] = 1;
        }else{
          $deposito['alarma'] = 0;
        }
      }


      // print_r($deposito);
      $resultado[] = $deposito;
    }
    $contadorAlerta++;
  }
}else if($zona == ZONA_DEF && $deposito == DEPOSITO_DEF && $consigna == CONSIGNA_DEF){
  // echo "MODO INICIAL";
  $resultado = RepositorioTelemetria::obtener_datos_inicial(Conexion::obtener_conexion());

}else if($zona != ZONA_DEF && $deposito == DEPOSITO_DEF && $consigna == CONSIGNA_DEF){
  // echo "POR ZONA";
  $resultado = RepositorioTelemetria::obtener_datos_zona(Conexion::obtener_conexion(), $zona);

}else if($zona == ZONA_DEF && $deposito == DEPOSITO_DEF && $consigna != CONSIGNA_DEF){
  // echo "POR CONSIGNA";
  $resultado = RepositorioTelemetria::obtener_datos_consigna(Conexion::obtener_conexion(), $maximo, $minimo);

}else if($zona != ZONA_DEF && $deposito == DEPOSITO_DEF && $consigna != CONSIGNA_DEF){
  // echo "POR ZONA Y CONSIGNA";
  $resultado = RepositorioTelemetria::obtener_datos_zona_consigna(Conexion::obtener_conexion(), $zona, $maximo, $minimo);

}else if($zona != ZONA_DEF && $deposito != DEPOSITO_DEF && $consigna == CONSIGNA_DEF){
  // echo "POR ZONA Y DEPOSITO";
  $resultado = RepositorioTelemetria::obtener_datos_zona_deposito(Conexion::obtener_conexion(), $zona, $deposito);

}else if($zona != ZONA_DEF && $deposito != DEPOSITO_DEF && $consigna != CONSIGNA_DEF){
  // echo "POR ZONA DEPOSITO Y CONSIGNA";
  $resultado = RepositorioTelemetria::obtener_datos_zona_deposito_consigna(Conexion::obtener_conexion(), $zona, $deposito, $maximo, $minimo);
}

// echo $zona;

// NOS TRAEMOS LAS ALERTAS DEL USUARIO
// echo $id_usuario;
$alertas = 'alertas inicializado';
$alertas = RepositorioFavoritos::obtener_alertas_usuario(Conexion::obtener_conexion(), $id_usuario);
// print_r($alertas);

Conexion::cerrar_conexion();
// print_r($resultado);
?>

<table class="table table-striped" id="tabla-home">
        <thead>
          <tr>
            <th>Depósito</th>
            <th>Código</th>
            <th>Dato</th>
            <th>Detalle</th>
            <th>Alerta</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          <?php
          $dep_anterior = "X";
          $dep_anteriorTU = "X";
          $ac = 1;
          $tu = 1;
          $lineSeparadorDepositos = 0;
          foreach($resultado as $fila){

            if($lineSeparadorDepositos > 0 && $dep_anterior!=$fila['codigo']){
              echo '<tr id="fila-separadora-depositos"><td></td><td></td><td></td><td></td><td></td></tr>';
            }

            // Creamos nueva fila si el dato es un CLORO
            if($fila['tipo'] == 'CL'){

                ?>

                <?php

                if($fila['alarma'] == 1){
                  echo '<tr class="fila-alarma">';
                }else{
                  echo '<tr>';
                }

                ?>
                <!-- <tr> -->
                  <td>
                    <?php echo substr($fila['codigo'], 0); ?>
                    <?php
                    if($fila['servicio'] == 1){
                      ?>
                      <i class="fa fa-tint" aria-hidden="true"></i>
                      <?php
                    }
                    ?>
                  </td>

                  <td>
                    <?php
                    // echo trim($dep_anterior);
                    // echo '<br>';
                    // echo trim($fila['codigo']);
                    // echo '<br>';
                    if($fila['codigo'] != $dep_anterior){
                      $ac = 1;
                      echo 'AC 0' . $ac;
                      $ac ++;
                      $dep_anterior = $fila['codigo'];
                    }else{
                      echo 'AC0' . $ac;
                      $ac ++;
                    }
                    ?>
                  </td>


                  <td>
                    <?php
                    if($fila['dato'] != "-10"){
                      echo $fila['dato'] . " ppm";
                    }else{
                      echo '<p class="sin-conexion">Sin Conexión</p>';
                    }
                    ?>
                  </td>



                  <td>
                    <a href="#" id="detalle-deposito" class="detalle-deposito">
                      <i class="fa fa-search-plus fa-2x" aria-hidden="true"></i>
                      <input type="text" value="<?php echo $fila['codigo']; ?>" hidden class="input-hidden-deposito">

                    </a>

                  </td>


                    <!-- Si existe alerta para este codigo, añadimos icono de lapiz -->
                    <?php
                    $existe_alerta = false;

                    foreach ($alertas as $alerta) {
                      if($alerta[2] == $fila['codigo']){
                        $existe_alerta = true;
                        ?>
                        <td>
                          <a href="#" id="alerta-editar" class="boton-alerta alerta-editar">
                            <i class="fa fa-pencil fa-2x" aria-hidden="true"></i>
                            <input type="text" value="<?php echo $fila['zona']; ?>" hidden class="input-hidden-zona">
                            <input type="text" value="<?php echo $fila['codigo']; ?>" hidden class="input-hidden-deposito">

                            <input class="valor-max" hidden value="<?php echo $alerta['max']; ?>">
                            <input class="valor-min" hidden value="<?php echo $alerta['min']; ?>">
                            <input class="valor-not" hidden value="<?php echo $alerta['alerta'] ?>">
                          </a>
                          <div class="div-borrar-alerta">
                            <a href="#" class="alerta-borrar boton-borrar" id="alerta-borrar">
                              <i class="fa fa-times fa-2x" aria-hidden="true"></i>
                              <input type="text" value="<?php echo $fila['codigo']; ?>" hidden class="input-hidden-deposito">
                            </a>
                          </div>
                        </td>
                        <?php
                      }
                    }
                    if(!$existe_alerta){
                      ?>
                      <td>
                        <button type="button" name="button" class="btn btn-info boton-alerta">
                          <i class="fa fa-bell" aria-hidden="true"></i>
                          <input type="text" value="<?php echo $fila['zona']; ?>" hidden class="input-hidden-zona">
                          <input type="text" value="<?php echo $fila['codigo']; ?>" hidden class="input-hidden-deposito">
                        </button>
                      </td>
                      <?php
                    }
                    ?>


                </tr>
                <?php
                /////  TURBIDEZ ////////////////////////
            }else if($fila['tipo'] == 'TU'){
              ?>
              <?php
              if($fila['alarma'] == 1){
                echo '<tr class="fila-turbi-alarma">';
              }else{
                echo '<tr class="fila-turbi">';
              }
              ?>
              <!-- <tr> -->
                <td>
                  <?php echo substr($fila['codigo'], 0); ?>
                  <?php
                  if($fila['servicio'] == 1){
                    ?>
                    <i class="fa fa-tint" aria-hidden="true"></i>
                    <?php
                  }
                  ?>
                </td>

                <td>
                  <?php

                  // echo trim($dep_anteriorTU);
                  // echo '<br>';
                  // echo trim($fila['codigo']);
                  // echo '<br>';
                  if($fila['codigo'] != $dep_anteriorTU){
                    $tu = 1;
                    echo 'TU 0' . $tu;
                    $tu ++;
                    $dep_anteriorTU = $fila['codigo'];
                  }else{
                    echo 'TU0' . $tu;
                    $tu ++;
                  }

                  // echo 'TU';
                  ?>
                </td>


                <td>
                  <?php
                  if($fila['dato'] != "-10"){
                    echo $fila['dato'] . " ntu";
                  }else{
                    echo '<p class="sin-conexion">Sin Conexión</p>';
                  }
                  ?>
                </td>



                <td>
                  <a href="#" id="detalle-deposito" class="detalle-deposito">
                    <i class="fa fa-search-plus fa-2x" aria-hidden="true"></i>
                    <input type="text" value="<?php echo $fila['codigo']; ?>" hidden class="input-hidden-deposito">

                  </a>

                </td>


                  <!-- Si existe alerta para este codigo, añadimos icono de lapiz -->
                  <?php
                  $existe_alerta = false;

                  foreach ($alertas as $alerta) {
                    if($alerta[2] == $fila['codigo']){
                      $existe_alerta = true;
                      ?>
                      <td>
                        <a href="#" id="alerta-editar" class="boton-alerta alerta-editar">
                          <i class="fa fa-pencil fa-2x" aria-hidden="true"></i>
                          <input type="text" value="<?php echo $fila['zona']; ?>" hidden class="input-hidden-zona">
                          <input type="text" value="<?php echo $fila['codigo']; ?>" hidden class="input-hidden-deposito">

                          <input class="valor-max" hidden value="<?php echo $alerta['max']; ?>">
                          <input class="valor-min" hidden value="<?php echo $alerta['min']; ?>">
                          <input class="valor-not" hidden value="<?php echo $alerta['alerta'] ?>">
                        </a>
                        <div class="div-borrar-alerta">
                          <a href="#" class="alerta-borrar boton-borrar" id="alerta-borrar">
                            <i class="fa fa-times fa-2x" aria-hidden="true"></i>
                            <input type="text" value="<?php echo $fila['codigo']; ?>" hidden class="input-hidden-deposito">
                          </a>
                        </div>
                      </td>
                      <?php
                    }
                  }
                  if(!$existe_alerta){
                    ?>
                    <td>
                      <button type="button" name="button" class="btn btn-info boton-alerta">
                        <i class="fa fa-bell" aria-hidden="true"></i>
                        <input type="text" value="<?php echo $fila['zona']; ?>" hidden class="input-hidden-zona">
                        <input type="text" value="<?php echo $fila['codigo']; ?>" hidden class="input-hidden-deposito">
                      </button>
                    </td>
                    <?php
                  }
                  ?>


              </tr>


              <?php
            }
            $lineSeparadorDepositos++;
          }

          ?>

        </tbody>

      </table>
