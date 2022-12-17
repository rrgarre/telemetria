$(document).ready(function(){
  // detectamos si la pantalla es vertical u horizontal
  if( isMobile.any()){
    var compensarAlturaPantalla = 140;
  }else{
    var compensarAlturaPantalla = 168;
  }
  // Sacamos el alto de la pantalla para darle ese valor a la
  // zona-lectura-interior donde se muestra el contenido.
  // Asi conseguimos que el scroll sea solo de la zona de lectura
  $("#zona-lectura-interior").css("height", ($(window).height() - compensarAlturaPantalla));
  // Y hacemos que si se redimensiona la pantalla, se actualice
  // el atributo
  $(window).resize(function(event) {
    $("#zona-lectura-interior").css("height", ($(window).height() - compensarAlturaPantalla));
  });

  // Usamos .on() para que los posibles nuevos enlaces creado en la
  // zona de lectura tambien tengan el evento click
  $("#user-interfaz").on("click", "a", cargarContenido);
});

function cargarContenido(event){

  // $("h1.titulo").fadeOut(1000);
  // $("p.titulo").delay(1000).fadeIn(300);

  $("#zona-indice a").removeClass('titulo-seleccionado');
  $(event.target).addClass('titulo-seleccionado');

  var vinculo = $(event.target).attr("href");
  var tituloFichero = vinculo.split("/")[1].split(".")[0];

  $("#titulo-lectura").text(tituloFichero);
  $("#contenido-lectura").load(vinculo);

  return false;
}


// Funcion para saber si es un movil y mas cosas
var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};
