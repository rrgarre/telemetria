<?php
$titulo = 'Telemetría MCT';
include_once 'plantillas/documento-apertura.inc.php';

$ficheros = array();
$ficheros = scandir("documentos");
?>
<!doctype html>
<html>

    <div class="container">
      <div class="jumbotron">
       <h1 class="titulo"><?php echo $titulo; ?></h1>
       <p class="titulo">
         <?php echo $titulo; ?>
       </p>
      </div>
    </div>

    <div class="container">

      <div class="row" id="user-interfaz">

        <div class="col-xs-4" id="zona-indice">
          <?php
          foreach ($ficheros as $fichero) {
            if(($fichero != ".") && ($fichero != "..")){
              $fichero = substr($fichero, 0, -5);
              ?>
              <a href="documentos/<?php echo $fichero; ?>.html" class="enlace-contenido"><?php echo $fichero; ?></a>
              <br><br>
              <?php
            }
          }
          ?>

        </div>

        <div class="col-xs-8" id="zona-lectura">
          <!-- <div id="titulo-lectura"></div> -->
          <div id="zona-lectura-interior">
            <h3 id="titulo-lectura"></h3>
            <br>
            <div id="contenido-lectura">

            </div>
          </div>
        </div>

      </div>

    </div>
<?php
include_once 'plantillas/documento-cierre.inc.php';
?>
