  'use strict'

// ------------------------saber si HAY CAMBIO DE TAMAÑAO EN PANTALLA-------------------

window.addEventListener("resize", function(){
   console.log('La pantalla ha cambiado de tamaño');
   return true;
});


function compruebaNavegadorMovil() {
  var navegadorMovil=null;
  /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? navegadorMovil=true : navegadorMovil=false;
    return navegadorMovil;
}
var tiponavegador = compruebaNavegadorMovil();
    console.log("Navegador Movil = "+tiponavegador);

function compruebaPanoramica() {
  var Panoramica=null;
   var porporcion_pantalla=jQuery(window).height()/jQuery(window).width();
    porporcion_pantalla<1 ? Panoramica=true : Panoramica=false;
    return Panoramica;
}
function porporcion_pantalla() {
   var porporcion_pantalla=jQuery(window).height()/jQuery(window).width();
    return porporcion_pantalla;
}
    tiponavegador = compruebaPanoramica()+" proporcion "+porporcion_pantalla();
    console.log("Pantalla Panoramica = "+tiponavegador);

    
function altura_pantalla() {
   var altura_pantalla=jQuery(window).height();
    return altura_pantalla;
}
function altura_body() {
   var altura_body=jQuery("body").height();
    return altura_body;
}
console.log("Pantalla body = "+altura_body());
console.log("Pantalla = "+(altura_pantalla()));


// -----------------------PRELOADER DE CARGA-------------------
      $( window ).on( "load", function() {
        $("#preloader").fadeOut(500);
        $("#pantalla_carga").css("opacity","0");
        $("#pantalla_carga").css("transform","translateY(-110%)");
        setTimeout(
             function(){
              $("#pantalla_carga").css("display","none");
          }, 2500);
      });

(() => {

$(document).ready(function () {
  $('#btnLogout').on('click', function () {
    $.ajax({
      url: directorioBase + '/secciones/logout.php',
      method: 'POST',
      success: function (response) {
        window.location.href = directorioBase + '/LOGIN';
      },
      error: function () {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'No se pudo cerrar sesión.'
        });
      }
    });
  });

    $('#btnInicio').on('click', function () {
    window.location.href = directorioBase; 
  });


  
});

})();
