
$(document).ready(function(){
  // alert();
  // Fijamos o no la consola de filtros según el modo de la pagina
  // grande o movil
  redimensionVentana();

  // Llamamos a la funcion que envia parametros de busqueda y carga
  // resultados
  realizarBusquedaAvanzada();

  // SELECTORES ZONA y DEPOSITOS/////////////////////////////////////////////
  $("#selector-zona a").click(seleccionZona);
  $("#selector-zona-modal a").click(seleccionZonaModal);

  $("#selector-deposito").on("click", "a", seleccionInstalacion);
  $("#selector-deposito-modal").on("click", "a", seleccionInstalacionModal);
  //////////////////////////////////////////////////////////////////////////

  // SELECTORES CONSIGNAS/////////////////////////////////////////////
  $("#slide-button").click(mostrarSliders);
  //////////////////////////////////////////////////////////////////////////

  // PONEMOS A LA ESCUCHA AL BOTON DE DETALLE LUPA//////////////////////
  $(".detalle-deposito").on("click", mostrarDetalleDeposito);
  ///////////////////////////////////////////////////////////////////////

  // NOTIFICACIONES EMAIL MODAL/////////////////////////////////////////////
  $("#boton-notificacion").click(notificacionEmailModal);
  //////////////////////////////////////////////////////////////////////////

  // ACEPTAR FORMULARIO MODAL ///////////////////////////////////////////////
  $("#aceptar-modal").click(enviarDatosAlerta);
  //////////////////////////////////////////////////////////////////////////////

  // OCULTAR SIN CONEXION/////////////////////////////////////////////
  // $("#alertas-boton").click(trasCargarTabla);
  //////////////////////////////////////////////////////////////////////////

  // SLIDERS//////////////////////////////////////////////////////////////////
  // Con esto colocamos el valor del selector al cargar la pagina
  $("#slide-max-div label").text("Máximo: " + ($("#max").val())/100 + " ppm");
  $("#slide-min-div label").text("mínimo: " + ($("#min").val())/100 + " ppm");
  // Y en el MODAL
  $("#slide-max-div-modal label").text("Máximo: " + ($("#max-modal").val())/100 + " ppm");
  $("#slide-min-div-modal label").text("mínimo: " + ($("#min-modal").val())/100 + " ppm");


  // EVENTO
  // PARA ACTUALIZAR VALOR DEL SLIDE AL ARRASTRAR
  var sliderMax = document.getElementById("max");
  sliderMax.oninput = function() {
    // output.innerHTML = this.value;
    $("#slide-max-div label").text("Máximo: " + (this.value)/100 + " ppm");
  }
  var sliderMin = document.getElementById("min");
  sliderMin.oninput = function() {
    // output.innerHTML = this.value;
    $("#slide-min-div label").text("mínimo: " + (this.value)/100 + " ppm");
  }
  // Y en max-modal
  var sliderMaxModal = document.getElementById("max-modal");
  sliderMaxModal.oninput = function() {
    // output.innerHTML = this.value;
    $("#slide-max-div-modal label").text("Máximo: " + (this.value)/100 + " ppm");
  }
  var sliderMinModal = document.getElementById("min-modal");
  sliderMinModal.oninput = function() {
    // output.innerHTML = this.value;
    $("#slide-min-div-modal label").text("mínimo: " + (this.value)/100 + " ppm");
  }


  // EVENTO
  // PARA OBTENER RESULTADO AL DEJAR DE ARRASTRAR
  // NOS SERVIRA PARA TENER DATO PARA BUSCAR RESULTADOS
  $("#max, #min").change(function(){
    // Llamamos a la funcion que envia parametros de busqueda y carga
    // resultados
    realizarBusquedaAvanzada();
  });
  /////////////////////////////////////////////////////////////////////////////

  // Apariencia y estilos
  // aplicarEstilo();

  // DETECTAR SI LA PAGINA SE REDIMENSIONA
  $(window).resize(redimensionVentana);
});
// ||||||||||||FIN DEL READY() |||||||||||||||||||||||||||||||||


// Seleccion de zona en el desplegable///////////////////////////////////////
function seleccionZona(event){

  // Volvemos a cambiar el boton instalacion
  $("#selector-deposito button").html('<b>Instalación </b> <span class="caret"></span>');

  // Cambiamos el nombre al boton de ZONA

  var zonaSeleccionada = $(event.target).text();
  $("#selector-zona button").html('<b>' + zonaSeleccionada +
                                  '</b> <span class="caret"></span>');

  // Enviamos la zona por ajax para traer las instalaciones de esa zona
  var datos = {
                zona: zonaSeleccionada
              }
  $.getJSON("ajax/instalaciones-por-zona.php",
    datos,
    function(resultado){

      $("#dropdownMenu2-lista").html('<li><a href="#">Instalación</a></li>');
      for(var i=0; i < resultado.length; i++){

        if(i == 0){
          $("#dropdownMenu2-lista").append('<li><a href="#">' + resultado[i][0].substr(6) + '</a></li>');
        }else{
          $("#dropdownMenu2-lista").append('<li><a href="#">' + resultado[i][0].substr(6) + '</a></li>');
        }

      }
    });

  // Hacer visible el selector de deposito
  // $("#selector-deposito").fadeIn();
  if($(event.target).text().trim() == 'Elige una Zona'){
    $("#selector-deposito").fadeOut();
  }else{
    $("#selector-deposito").fadeIn();
  }

  // Llamamos a la funcion que envia parametros de busqueda y carga
  // resultados
  realizarBusquedaAvanzada();

}

function seleccionZonaModal(event){
  // Volvemos a cambiar el boton instalacion
  $("#selector-deposito-modal button").html('<b>Instalación </b> <span class="caret"></span>');

  // Cambiamos el nombre al boton de ZONA
  var zonaSeleccionada = $(event.target).text();
  $("#selector-zona-modal button").html('<b>' + zonaSeleccionada +
                                  '</b> <span class="caret"></span>');

  // Enviamos la zona por ajax para traer las instalaciones de esa zona
  var datos = {
                zona: zonaSeleccionada
              }
  $.getJSON(
    "ajax/instalaciones-por-zona.php",
    datos,
    function(resultado){

      $("#dropdownMenu2-lista-modal").html('<li><a href="#">Instalación</a></li>');
      for(var i=0; i < resultado.length; i++){

        if(i == 0){
          $("#dropdownMenu2-lista-modal").append('<li><a href="#">' + resultado[i][0].substr(6) + '</a><p hidden>' + resultado[i][0] + '</p></li>');
        }else{
          $("#dropdownMenu2-lista-modal").append('<li><a href="#">' + resultado[i][0].substr(6) + '</a><p hidden>' + resultado[i][0] + '</p></li>');
        }

      }
    }
  );

  // Hacer visible el selector de deposito
  // $("#selector-deposito").fadeIn();
  if($(event.target).text().trim() == 'Elige una Zona'){
    $("#selector-deposito-modal").fadeOut();
  }else{
    $("#selector-deposito-modal").fadeIn();
  }
}


// Seleccion de la Instalacion en el desplegable
function seleccionInstalacion(event){
  // Cambiamos el nombre al boton de INSTALACION
  var instalacionSeleccionada = $(event.target).text();
  $("#selector-deposito button").html('<b>' + instalacionSeleccionada +
                                  '</b> <span class="caret"></span>');

  // Llamamos a la funcion que envia parametros de busqueda y carga
  // resultados
  realizarBusquedaAvanzada();
}

function seleccionInstalacionModal(event){
  // Cambiamos el nombre al boton de INSTALACION
  var instalacionSeleccionada = $(event.target).text();
  // alert($(event.target).next('p').text());
  var codigoCompleto = $(event.target).next('p').text();
  $("#selector-deposito-modal button").html('<b>' + instalacionSeleccionada +
                                  '</b><p hidden>'+ codigoCompleto + '</p><span class="caret"></span>');
}



//Muestra u oculta los sliders o el boton de CONSIGNAS
// SLIDERS
function mostrarSliders(event){
  $(".slidecontainer").slideToggle();
  // alert($(event.target).text());
  if($(event.target).text() == "Consignas "){
    $("#slide-button").html('<b>Anular </b><i class="fa fa-caret-up" aria-hidden="true"></i>');
  }else{
    $("#slide-button").html('<b>Consignas </b><i class="fa fa-caret-down" aria-hidden="true"></i>');
  }
  // Llamamos a la funcion que envia parametros de busqueda y carga
  // resultados
  realizarBusquedaAvanzada();
}

function arrastreSlide(event){
  // alert($(event.target).val());
  $("#slide-max-div label").text("Máximo: " + ($(event.target).val())/100 + " ppm");
}

// ACTIVA O DESACTIVA NOTIFICACIONES EMAIL DE ALERTAS EN MODAL
function notificacionEmailModal(event){
  // Cambiamos el icono a marcado o desmarcado
  $("#boton-notificacion").find("i").toggleClass('fa-square-o').toggleClass('fa-check-square-o');
}

// BUSQUEDA/////////////////////////////////////////////
function realizarBusquedaAvanzada(){

  var parametros = {
    zona: $("#selector-zona button").text().trim(),
    deposito: $("#selector-deposito button").text().trim(),
    consigna: $("#slide-button").text().trim(),
    maximo: $("#max").val(),
    minimo: $("#min").val(),
    id_usuario: $("#p-user-id").text().trim()
  }
  // Inutilizamos el boton de mostrar alertas hasta que carga la tabla

  $("#alertas-boton").off();
  $("#resultado").load(
      'plantillas/resultado-home.inc.php',
      parametros,
      function(){
        // cambiarAparienciaBotonAlertas();
        cambiarAparienciaBotonAlertas();
        $("#alertas-boton").on("click",trasCargarTabla).fadeIn();

        // CARGAMOS EL FORMULARIO MODAL
        $(".boton-alerta").on("click", function(event){
          // Cortamos el comportamiento del enlace para evitar desplazamiento
          // en la pagina de resultados
          event.preventDefault();
          // Ocultamos el boton instalacion por defecto
          $("#selector-deposito-modal").fadeOut();

          // AVERIGUAMOS SI ESTAMOS CREANDO O EDITANDO
          if($(this).hasClass('alerta-editar')){
            // Ajustamos sliders a valor ESTABLECIDO
            var valor_max = $(this).find("input.valor-max").val();
            var valor_min = $(this).find("input.valor-min").val();
            var valor_alerta = $(this).find("input.valor-not").val();
            $("#max-modal").val(valor_max);
            $("#slide-max-div-modal label").text("Máximo: " + valor_max/100 + " ppm");
            $("#min-modal").val(valor_min);
            $("#slide-min-div-modal label").text("mínimo: " + valor_min/100 + " ppm");

            // Marcamos notificar
            if(valor_alerta == 0){
              $("#boton-notificacion").find("i").removeClass('fa-check-square-o').addClass('fa-square-o');
            }else{
              $("#boton-notificacion").find("i").removeClass('fa-square-o').addClass('fa-check-square-o');
            }
          }else{
            // Ajustamos sliders a valor inicial
            $("#max-modal").val("100");
            $("#slide-max-div-modal label").text("Máximo: 1 ppm");
            $("#min-modal").val("60");
            $("#slide-min-div-modal label").text("mínimo: 0.60 ppm");

            // Marcamos como no notificar por eamil por defecto
            $("#boton-notificacion").find("i").removeClass('fa-check-square-o').addClass('fa-square-o');
          }



          var zonaSeleccionada = $(this).find("input.input-hidden-zona").val();
          var instalacionSeleccionada = $(this).find("input.input-hidden-deposito").val();

          // $("#dataModal #zona").text(zonaSeleccionada);
          // $("#dataModal #deposito").text(instalacionSeleccionada);

          // PRECARGAMOS ZONA EN FORM

          $("#selector-zona-modal button").html('<b>' + zonaSeleccionada +
                                          '</b> <span class="caret"></span>');

          // PRECARGAMOS DEPOSITO EN FORMULARIO
          if(instalacionSeleccionada == 'Instalación'){
            instalacionSeleccionadaCortada = instalacionSeleccionada;
          }else{
            instalacionSeleccionadaCortada = instalacionSeleccionada.substr(6);
          }
          $("#selector-deposito-modal button").html('<b>' + instalacionSeleccionadaCortada +
                                          '</b><p hidden>'+ instalacionSeleccionada + '</p><span class="caret"></span>');

          // Enviamos la zona por ajax para traer las instalaciones de esa zona
          var datos = {
                        zona: zonaSeleccionada
                      }
          $.getJSON(
            "ajax/instalaciones-por-zona.php",
            datos,
            function(resultado){

              $("#dropdownMenu2-lista-modal").html('<li><a href="#">Instalación</a></li>');
              for(var i=0; i < resultado.length; i++){

                if(i == 0){
                  $("#dropdownMenu2-lista-modal").append('<li><a href="#">' + resultado[i][0].substr(6) + '</a><p hidden>' + resultado[i][0] + '</p></li>');
                }else{
                  $("#dropdownMenu2-lista-modal").append('<li><a href="#">' + resultado[i][0].substr(6) + '</a><p hidden>' + resultado[i][0] + '</p></li>');
                }

              }
            }
          );

          // MOSTRAMOS BOTON SELECCION DEPOSITO SI YA DEBE TENER VALOR CARGADO
          if($(this).attr("id") != 'boton-nueva-alerta'){
            $("#selector-deposito-modal").fadeIn();
          }

          $("#dataModal").modal("show");
        });
        // FIN CARGAMOS EL FORMULARIO MODAL ||||||||||||||||||||||||

        // PONEMOS A LA ESCUCHA LOS BOTONES BORRAR
        $(".div-borrar-alerta").on("click", "a", borrarAlerta);

        // PONEMOS A LA ESCUCHA AL BOTON DE DETALLE LUPA
        $(".detalle-deposito").on("click", mostrarDetalleDeposito);

        // ACTUALIZAMOS MENSAJE DE ALERTAS EN NAVBAR
        actualizarAlertas();
      });

}

// Actualizamos icono de ALERTAS
function actualizarAlertas(){
  // alert("act alertas");
  var parametros = {
    codigo: 'alertas'
  }
  $("#enlace-alertas-activas").load("ajax/alertas-activas.php");
}

// Mostrar Detalles de deposito
function mostrarDetalleDeposito(event){
  event.preventDefault();
  // alert($(event.target).next("input").val());
  // alert();

  var parametros = {
    codigo: $(event.target).next("input").val()
  }

  $.getJSON(
    'ajax/detalle-deposito.php',
    parametros,
    function(resultado){
      $("#titulo-detalle-modal").html('<i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;&nbsp;'
            + resultado["titulo"]);
      $("#cuerpo-detalle-deposito-oculto").html(resultado["datos"]);

      // Limpiamos informacion del contenido de la pagina traida al MODAL
      // detalle
      // $("#cuerpo-detalle-deposito meta").remove();
      // $("#cuerpo-detalle-deposito title").next("b").remove();
      // $("#cuerpo-detalle-deposito title").remove();
      //
      // $("#cuerpo-detalle-deposito table").next("b").remove();
      // $("#cuerpo-detalle-deposito br").remove();
      // $("#cuerpo-detalle-deposito table").next("a").remove();

      // $("#cuerpo-detalle-deposito").html($("#cuerpo-detalle-deposito-oculto body"));
      $("#cuerpo-detalle-deposito").html($("#cuerpo-detalle-deposito-oculto table"));

      $("#detalleModal").modal("show");
    }
  );
}

// Envio de datos del formulario de alerta
function enviarDatosAlerta(event){
  var zona = $('#dropdownMenu1-modal').text().trim();
  var deposito = $('#dropdownMenu2-modal').find('p').text().trim();
  if($("#boton-notificacion").find("i").hasClass('fa-square-o')){
    var notificar = false;
  }else{
    var notificar = true;
  }

  if(zona != 'Elegir una Zona' && (deposito != 'Instalación' && deposito != '')){
    // DATOS CORRECTOS, LISTO PARA ENVIO
    var parametros = {
      zona: zona,
      deposito: deposito,
      // maximo: $("#max").val(),
      maximo: $("#slide-max-div-modal label").text().trim().slice(8,-4),
      // minimo: $("#min").val(),
      minimo: $("#slide-min-div-modal label").text().trim().slice(8,-4),
      notificar: notificar,
      id_usuario: $("#p-user-id").text().trim()
    }
    // ENVIO DE LA INFORMACION AL SERVIDOR
    $.getJSON(
      "ajax/modificar-alerta.php",
      parametros,
      function(resultado){
        // cerrar modal
        // alert(resultado);
        $("#dataModal").modal("hide");
        realizarBusquedaAvanzada();
      }
    );

  }else{
    // ERROR EN DATOS
    mostrarAlertaValidacionForm();
  }
}

function borrarAlerta(event){
  event.preventDefault();
  // alert('borrar alertaaaaaaa');

  var deposito = $(event.target).next("input").val();
  // alert($(event.target).next("input").val());

  var parametros = {
    deposito: deposito,
    id_usuario: $("#p-user-id").text().trim()
  }

  // ENVIO DE LA INFORMACION AL SERVIDOR
  $.getJSON(
    "ajax/borrar-alerta.php",
    parametros,
    function(resultado){
      // cerrar modal
      // alert(resultado);
      // $("#dataModal").modal("hide");
      realizarBusquedaAvanzada();
    }
  );
}

// Alertamos de que para crear una alerta debemos tener seleccionados
// Zona y deposito
function mostrarAlertaValidacionForm(){
  $("#selector-zona-modal-error").fadeIn().delay(2000).fadeOut();
}

function trasConfigurarAlerta(){
  alert('alerta Ingresada');
}
//////////////////////////////////////////////////////////
// APARIENCIA Y ESTILOS|||||||||||||||||||||||||||||||||||
function trasCargarTabla(event){
  if($("#alertas-boton").text() == "Mostrar "){
    $("#alertas-boton").html('<b>Ocultar </b>');
    $("#alertas-boton").append('<i class="fa fa-plug" aria-hidden="true"></i>');
  }else{
    $("#alertas-boton").html('<b>Mostrar </b>');
    $("#alertas-boton").append('<i class="fa fa-plug" aria-hidden="true"></i>');
  }
  $("tr").each(function(){
    if($(this).find("p").text() == "Sin Conexión"){
      $(this).fadeToggle();
    }
  });


}

function cambiarAparienciaBotonAlertas(){
  if($("#alertas-boton").text() == "Mostrar "){
    // $("#alertas-boton").html('<b>Ocultar </b>');
    // $("#alertas-boton").append('<i class="fa fa-plug" aria-hidden="true"></i>');
    $("#alertas-boton").html('<b>Ocultar </b>');
    $("#alertas-boton").append('<i class="fa fa-plug" aria-hidden="true"></i>');
  }else{
    // $("#alertas-boton").html('<b>Mostrar </b>');
    // $("#alertas-boton").append('<i class="fa fa-plug" aria-hidden="true"></i>');
  }
}

function redimensionVentana(){
  if($("#boton-desplegar-navbar").css("display") == "none"){
    // fijamos la consola de filtros
    $("#consola-filtros").addClass('consola-filtros-estaticos');
  }else{
    $("#consola-filtros").removeClass('consola-filtros-estaticos');
  }
}
//////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////
