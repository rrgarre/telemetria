<?php

// include_once 'app/Conexion.inc.php';
// include_once 'app/config.inc.php';
// include_once 'app/Redireccion.inc.php';
// include_once 'app/Deposito.inc.php';
// include_once 'app/RepositorioUsuario.inc.php';
// include_once 'app/Rastreador.inc.php';
// include_once 'app/RepositorioTelemetria.inc.php';

$titulo = 'telemetría MCT';
include_once 'plantillas/documento-apertura.inc.php';
// incluimos la barra de navegacion
include_once 'plantillas/documento-navbar.inc.php';

?>


<h1>Vista ALERTAS</h1>

<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="">
        <table class="table table-striped" id="tabla-home">
          <thead>
            <tr>
              <th>Depósito</th>
              <th>Código</th>
              <th>Cloro</th>
              <th>Detalle</th>
              <th>Alerta</th>
            </tr>
          </thead>

          <tbody>
            <?php
            $resultados = array();
            Conexion::abrir_conexion();
            $contadorAlerta = 0;
            foreach($alertasUsusario as $fila){
              $dato = RepositorioTelemetria::obtener_datos_deposito(Conexion::obtener_conexion(), $fila['codigo']);
              foreach($dato as $deposito){
                $deposito['contador-alerta'] = $contadorAlerta;
                // print_r($deposito);
                $resultado[] = $deposito;
              }
              $contadorAlerta++;
            }
            Conexion::cerrar_conexion();

            // IMPRIMIMOS RESULTADOS
            $dep_anterior = "X";
            $ac = 1;
            foreach($resultado as $deposito){
              ?>
              <tr>
                <td>
                  <?php echo substr($deposito['codigo'], 6); ?>
                  <?php
                  if($deposito['servicio'] == 1){
                    ?>
                    <i class="fa fa-tint" aria-hidden="true"></i>
                    <?php
                  }
                  ?>
                </td>

                <td>
                  <?php
                  if($deposito['codigo'] != $dep_anterior){
                    $ac = 1;
                    echo 'AC 0' . $ac;
                    $ac ++;
                    $dep_anterior = $deposito['codigo'];
                  }else{
                    echo 'AC0' . $ac;
                    $ac ++;
                  }
                  ?>
                </td>

                <td>
                  <?php
                  if($deposito['dato'] != "-10"){
                    echo $deposito['dato'] . " ppm";
                  }else{
                    echo '<p class="sin-conexion">Sin Conexión</p>';
                  }
                  ?>
                </td>

                <td>
                  <a href="#" id="detalle-deposito" class="detalle-deposito">
                    <i class="fa fa-search-plus fa-2x" aria-hidden="true"></i>
                    <input type="text" value="<?php echo $deposito['codigo']; ?>" hidden class="input-hidden-deposito">

                  </a>

                </td>



                  <td>
                    <a href="#" id="alerta-editar" class="boton-alerta alerta-editar">
                      <i class="fa fa-pencil fa-2x" aria-hidden="true"></i>
                      <input type="text" value="<?php echo $deposito['zona']; ?>" hidden class="input-hidden-zona">
                      <input type="text" value="<?php echo $deposito['codigo']; ?>" hidden class="input-hidden-deposito">

                      <input class="valor-max" hidden value="<?php echo $alertasUsusario[$deposito['contador-alerta']]['max']; ?>">
                      <input class="valor-min" hidden value="<?php echo $alertasUsusario[$deposito['contador-alerta']]['min']; ?>">
                      <input class="valor-not" hidden value="<?php echo $alertasUsusario[$deposito['contador-alerta']]['alerta']; ?>">
                    </a>
                    <div class="div-borrar-alerta">
                      <a href="#" class="alerta-borrar boton-borrar" id="alerta-borrar">
                        <i class="fa fa-times fa-2x" aria-hidden="true"></i>
                        <input type="text" value="<?php echo $deposito['codigo']; ?>" hidden class="input-hidden-deposito">
                      </a>
                    </div>
                  </td>



              </tr>
              <?php
            }
            ?>
            <tr>
              <td>contenido</td>
              <td>contenido</td>
              <td>contenido</td>
              <td>contenido</td>
              <td>contenido</td>
            </tr>
            <tr>
              <td>contenido</td>
              <td>contenido</td>
              <td>contenido</td>
              <td>contenido</td>
              <td>contenido</td>
            </tr>
          </tbody>

        </table>
      </div>
    </div>
  </div>
</div>




<?php
include_once 'plantillas/documento-cierre.inc.php';
include_once 'plantillas/crear-alerta.php';
include_once 'plantillas/detalle-deposito.php';
?>
