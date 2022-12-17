
<div id="dataModal" class="modal fade">

  <div class="col-sm-3 col-xs-1">

  </div>
  <div class="col-sm-6 col-xs-10">
    <div class="panel panel-default panel-form-alert">
      <div class="panel-heading">
        <i class="fa fa-bell" aria-hidden="true"></i>
        Nueva alerta
      </div>
      <div class="panel-body">
        <!-- BOTON ZONA -->
        <div class="col-sm-12">
          <div class="dropdown" id="selector-zona-modal">
            <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1-modal" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
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
        <!-- FIN BOTON ZONA -->
        <!-- BOTON DEPOSITO -->
        <div class="col-sm-12">
          <div class="dropdown" id="selector-deposito-modal">
            <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu2-modal" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
              <b>Instalación</b>
              <i class="fa fa-caret-down" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2" id='dropdownMenu2-lista-modal'>

            </ul>
          </div>
        </div>
        <!-- FIN BOTON DEPOSITO -->

        <!-- SLIDERS -->
        <div id="slide-max-div-modal" class="slidecontainer-modal">
          <label for="max-modal">Máximo</label>
          <input type="range" min="80" max="140" value="100" class="slider" id="max-modal">
        </div>

        <div id="slide-min-div-modal" class="slidecontainer-modal">
          <label for="min-modal">mínimo</label>
          <input type="range" min="10" max="70" value="60" class="slider" id="min-modal">
        </div>
        <!-- FIN SLIDERS -->
        <br>

        <!-- Chech para notificacion por EMAIL -->
        <div class="row">
          <div class="col-xs-12">
            <button class="btn btn-info" type="button" id="boton-notificacion">
              <b>Notificar e-mail&nbsp;&nbsp;&nbsp;</b>
              <i class="fa fa-square-o" aria-hidden="true"></i>
            </button>
          </div>
        </div>
        <!-- FIN NOTIFICACION EMAIL -->

        <!-- ALERTA ERROR -->
        <div  class='alert alert-danger' role='alert' id="selector-zona-modal-error" align="center">
          <b>
            Elige Zona e Instalación
          </b>
        </div>

        <p id="zona"></p>
        <p id="deposito"></p>

        <!-- pie del formulario -->
        <div class="row modal-footer">
          <div class="col-xs-6" align='center'>
            <button class="btn btn-warning boton-pie-modal" type="button" id="cancelar-modal" data-dismiss="modal">
              <b>Cancelar</b>
            </button>
          </div>
          <div class="col-xs-6" align='center'>
            <button class="btn btn-info boton-pie-modal" type="button" id="aceptar-modal">
              <b>Aceptar</b>
            </button>
          </div>
          <?php
          echo '<p hidden id="p-user-id">' . $_SESSION['id_usuario'] . '</p>';
          ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-3 col-xs-1">

  </div>

</div>
