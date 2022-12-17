<?php
include_once "app/Conexion.inc.php";
include_once "app/RepositorioTelemetria.inc.php";
include_once "app/ControlSesion.inc.php";
Conexion::abrir_conexion();

$fecha_actualizacion = RepositorioTelemetria::obtener_fecha(Conexion::obtener_conexion());

Conexion::cerrar_conexion();

?>
<nav class="navbar navbar-default navbar-static-top" id="barra-navegacion">
  <div class="container">
    <div class="navbar-header">

      <button type="button" class="navbar-toggle collapsed"
        data-toggle="collapse" data-target="#navbar"
        aria-expansed="false" aria-controls="navbar" id='boton-desplegar-navbar'>

        <span class="sr-only">Desplegar barra de navegación</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <ul>
        <li>
          <!-- <div class="" id="enlace-alertas-activas">

          </div> -->

          <?php
          if(ControlSesion::sesion_iniciada()){
            ?>
            <a class="navbar-brand" href="<?php echo RUTA_CLOROS; ?>">
              <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
              <!-- Ultima actualizacion: -->
              <?php //echo ' ' . $fecha_actualizacion ?>
              cloros
            </a>

            <a id="enlace-alertas-activas" class="navbar-brand" href="<?php echo RUTA_ALERTAS; ?>">
              <?php include_once "alertas-activas.inc.php"; ?>
              alarmas
            </a>

            <?php
          }
          ?>


        </li>
      </ul>

    </div>

    <div class="navbar-collapse collapse" id="navbar">

      <?php
      if(ControlSesion::sesion_iniciada()){

        ?>
        <ul class="nav navbar-nav navbar-right">
          <li id="fecha-actualizacion">
            <p>
              <?php echo ' ' . $fecha_actualizacion ?>
            </p>
          </li>
          <!-- <li>
            <a href="<?php echo RUTA_CLOROS; ?>">
              Cloros
            </a>
          </li> -->
          <!-- <li>
            <a href="<?php echo RUTA_ALERTAS; ?>">
              Mis Alertas
            </a>
          </li> -->
          <li>
            <a href="#">
              <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
              <?php echo ' ' . $_SESSION['nombre_usuario']; ?>
            </a>
          </li>
          <li>
            <a href="<?php echo RUTA_LOGOUT; ?>">
              <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
              Cerrar Sesión
            </a>
          </li>
        </ul>
        <?php
      }else{
        ?>
        <ul class="nav navbar-nav navbar-right">
          <li>
            <a href="<?php echo RUTA_LOGIN; ?>">
              <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Iniciar sesión
            </a>
          </li>
          <li>
            <a href="<?php echo RUTA_REGISTRO; ?>">
              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Registrarse
            </a>
          </li>
        </ul>
        <?php
      }
      ?>
    </div>
  </div>
</nav>
