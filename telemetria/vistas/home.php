<?php
include_once 'app/Conexion.inc.php';
include_once 'app/config.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/Deposito.inc.php';
// include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/Rastreador.inc.php';
include_once 'app/RepositorioTelemetria.inc.php';

// Aqui estaba la parte de conexion a BBDD para pedir total_usuarios
// pero como es algo que usaremos en NAVBAR, una buena praxis
// consiste en llevarnos ese código a la plantilla de NAVBAR

// incluimos la apertura del documento
// definimos el título de página como parámetro para poder
// usar esa plantilla en cualquier sitio
$titulo = 'telemetría MCT';
include_once 'plantillas/documento-apertura.inc.php';
// incluimos la barra de navegacion
include_once 'plantillas/documento-navbar.inc.php';
?>

<!-- <div class="container">
  <div class="jumbotron">
     <h1>telemetría MCT</h1>
  </div>
</div> -->

<div class="container2">
  <div class="row">
    <div class="col-sm-4">


      <!-- <div class="row">
        <div class="col-sm-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
              Busqueda
            </div>
            <div class="panel-body">
              <div class="form-group">
                <input type="search" class="form-control" placeholder="¿Qué deseas buscar?">
              </div>
              <button class="form-control boton-busqueda">Buscar</button>
            </div>
          </div>
        </div>
      </div> -->


      <div class="row" id="consola-filtros">
        <div class="col-sm-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <span class="glyphicon glyphicon-filter" aria-hidden="true"></span>
              Filtro
            </div>
            <div class="panel-body">

              <!-- <div class="col-xs-6 col-sm-12"> -->
              <div class="col-sm-12">
                <div class="dropdown" id="selector-zona">
                  <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <b>Elige una Zona</b>
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li><a href="#">
                    Elige una Zona
                    </a></li>
                    <?php
                    Conexion::abrir_conexion();
                    $zonas = RepositorioTelemetria::obtener_zonas(Conexion::obtener_conexion());
                    Conexion::cerrar_conexion();
                    foreach($zonas as $zona){
                      ?>
                      <li><a href="#">
                      <?php
                      echo($zona["zona"]);
                      ?>
                      </a></li>
                      <?php
                    }
                    ?>
                  </ul>
                </div>
              </div>

              <!-- <div class="col-xs-6 col-sm-12"> -->
              <div class="col-sm-12">
                <div class="dropdown" id="selector-deposito">
                  <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <b>Instalación</b>
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu2" id='dropdownMenu2-lista'>

                  </ul>
                </div>
              </div>

              <!-- <hr> -->
              </hr>

              <div class="col-xs-12" id="consigna">


                <div id="slide-button-div">
                  <button class="btn btn-info" type="button" id="slide-button" >
                    <b>Consignas </b>
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                  </button>
                </div>

                <div id="slide-max-div" class="slidecontainer">
                  <label for="max">Máximo</label>
                  <input type="range" min="81" max="120" value="100" class="slider" id="max">
                </div>

                <div id="slide-min-div" class="slidecontainer">
                  <label for="min">mínimo</label>
                  <input type="range" min="10" max="80" value="60" class="slider" id="min">
                </div>

              </div>

              <div class="col-xs-12" id="mostrar-sin-conexion-div">

                <div id="slide-button-div">
                  <button type="button" name="button" class="btn btn-info boton-alerta" id='boton-nueva-alerta'>
                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                    <i class="fa fa-bell" aria-hidden="true"></i>
                    <input type="text" value="Elegir una Zona" hidden class="input-hidden-zona">
                    <input type="text" value="Instalación" hidden class="input-hidden-deposito">
                  </button>
                  <button class="btn btn-danger" type="button" id="alertas-boton" >
                    <b>Ocultar </b>
                    <i class="fa fa-plug" aria-hidden="true"></i>
                  </button>
                </div>

              </div>

            </div>
          </div>
        </div>

        <div class="fanTest">
          <!-- <button class="btn btn-primary" type="button" id="modalTest" >
            <b>modal </b>
            <i class="fa fa-plug" aria-hidden="true"></i>
          </button> -->
        </div>

      </div>

    </div>
    <div class="col-sm-8">
      <!-- ================================================================ -->
      <?php
      // Conexion::abrir_conexion();
      // RepositorioTelemetria::imprimir(Conexion::obtener_conexion());
      // Conexion::cerrar_conexion();
      ?>
      <div id="resultado">
        <?php
        // $zona = "BULLAS";
        // Conexion::abrir_conexion();
        // $resultado = RepositorioTelemetria::obtener_instalaciones_por_zona(Conexion::obtener_conexion(), $zona);
        // Conexion::cerrar_conexion();
        // print_r($resultado);
        ?>
      </div>

    </div>
  </div>
</div>

<!-- ============================================================== -->
<!-- scripts de JS y cierre de HTML -->
<?php
include_once 'plantillas/documento-cierre.inc.php';
include_once 'plantillas/crear-alerta.php';
include_once 'plantillas/detalle-deposito.php';
?>
