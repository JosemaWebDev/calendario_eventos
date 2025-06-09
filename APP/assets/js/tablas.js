

if ($(".contenedor_tablas").length > 0){
console.log("modulos tablas.js");
 $("#limpiar_fitros").click(function(e){
          e.preventDefault();
  });

	function marcar_lineas(){
    	$(".contenedor_tablas table tbody tr").click(function(){
      		if ($(this).hasClass("table-active")){
      			$(this).removeClass('table-active');
      		}else{
      			$(this).addClass('table-active');
      		}
    	});
	}

  function copyToClipboard(elemento) {
     
    var text_to_copy =elemento[0]['innerText'];
    if (text_to_copy!="" && text_to_copy!=null && text_to_copy!="undefined") {
     
      console.log(text_to_copy);
      if (!navigator.clipboard){

          var $temp = $("<input>")
          $temp.val(text_to_copy).select();
          document.execCommand("copy");
          $temp.remove();

          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Texto copiado',
            showConfirmButton: false,
            timer: 500
          })
      } else{
          navigator.clipboard.writeText(text_to_copy).then(
              function(){
                  Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Texto copiado',
                    showConfirmButton: false,
                    timer: 500
                  })
              })
            .catch(
               function() {
                  Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Texto NO copiado',
                    showConfirmButton: false,
                    timer: 500
                  })
            });
      }
    }  
  }

  function copiar_campo_tabla() {
     $(".contenedor_tablas table tbody tr td").dblclick(function(){
        copyToClipboard($(this));
      });
  }

  function pintar_tablas(recogida_datos){
    $(".contenedor_tablas").html("");
          $(".contenedor_tablas").append('<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">');
        $.each(recogida_datos,function(i,recogida_de_1_fila){
          if (i==0) {
           
            if (recogida_de_1_fila.pagina_seleccionada==1 && recogida_de_1_fila.cantidad_pagina>=3){
              $(".contenedor_tablas nav ul").append('<li class="page-item active"><a class="page-link" href="#" data-pagina="'+1+'">1</a></li><li class="page-item"><a class="page-link" href="#" data-pagina="'+2+'">2</a></li><li class="page-item"><a class="page-link" href="#" data-pagina="'+3+'">3</a></li>');
            }else if (recogida_de_1_fila.pagina_seleccionada==1 && recogida_de_1_fila.cantidad_pagina>=2){
              $(".contenedor_tablas nav ul").append('<li class="page-item active"><a class="page-link" href="#" data-pagina="'+1+'">1</a></li><li class="page-item"><a class="page-link" href="#" data-pagina="'+2+'">2</a></li>');
            }else if (recogida_de_1_fila.pagina_seleccionada==1 && recogida_de_1_fila.cantidad_pagina>=1){
              $(".contenedor_tablas nav ul").append('<li class="page-item active"><a class="page-link" href="#" data-pagina="'+1+'">1</a></li>');
            }else if(recogida_de_1_fila.pagina_seleccionada!=recogida_de_1_fila.cantidad_pagina){
              var pagina=recogida_de_1_fila.pagina_seleccionada;
              var pagina_mas=recogida_de_1_fila.pagina_seleccionada +1;
              var pagina_menos=recogida_de_1_fila.pagina_seleccionada -1;
               $(".contenedor_tablas nav ul").append('<li class="page-item"><a class="page-link" href="#" data-pagina="'+1+'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li><li class="page-item disabled"><a class="page-link">...</a></li><li class="page-item"><a class="page-link" href="#" data-pagina="'+pagina_menos+'">'+pagina_menos+'</a></li><li class="page-item active"><a class="page-link" href="#" data-pagina="'+pagina+'">'+pagina+'</a></li><li class="page-item"><a class="page-link" href="#" data-pagina="'+pagina_mas+'">'+pagina_mas+'</a></li>');
            }else{
              var pagina=recogida_de_1_fila.pagina_seleccionada;
              var pagina_menos=recogida_de_1_fila.pagina_seleccionada -1;
               $(".contenedor_tablas nav ul").append('<li class="page-item"><a class="page-link" href="#" data-pagina="'+1+'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li><li class="page-item disabled"><a class="page-link">...</a></li><li class="page-item"><a class="page-link" href="#" data-pagina="'+pagina_menos+'">'+pagina_menos+'</a></li><li class="page-item active"><a class="page-link" href="#" data-pagina="'+pagina+'">'+pagina+'</a></li>');
            }
            if (recogida_de_1_fila.pagina_seleccionada!=recogida_de_1_fila.cantidad_pagina) {
                $(".contenedor_tablas nav ul").append('<li class="page-item disabled"><a class="page-link">...</a></li><li class="page-item"><a class="page-link" href="#" data-pagina="'+recogida_de_1_fila.cantidad_pagina+'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>');
            }
            
          }
          
        });
        $(".contenedor_tablas").append('</ul></nav>');


    
    $(".contenedor_tablas").append('<table class="table table-hover">');
    $.each(recogida_datos,function(i,recogida_de_1_fila){
      
      if (i==1) {
        $(".contenedor_tablas table").append('<thead><tr>')

        $.each(recogida_de_1_fila,function(j,recogida_de_1_dato){
                if (j=="cod" || j=="id") {
                  $(".contenedor_tablas table thead tr").append('<th scope="col" width="70px"> <input class="form-check-input" type="checkbox" id="id_todos" value="todos"><label class="form-check-label" for="id_todos"></label></th>');
                }else{
                  $(".contenedor_tablas table thead tr").append('<th scope="col">'+j.toUpperCase()+'</th>');
                }
              });

              $(".contenedor_tablas table thead").append('</tr>');
              $(".contenedor_tablas table ").append('</thead>');
              $(".contenedor_tablas table ").append('<tbody>');
      }
      if (i>=1) {
        $(".contenedor_tablas table tbody").append('<tr>')

                  $.each(recogida_de_1_fila,function(j,recogida_de_1_dato){
                      if (typeof recogida_de_1_dato==='object') {
                          if (!recogida_de_1_dato.atributos) {
                            recogida_de_1_dato.atributos=null;
                          }
                          if (recogida_de_1_dato.datos=="SI"){
                            recogida_de_1_dato.datos='<img src="'+directorioBase+'/assets/rsc/img/iconos/SI.png">'
                          }
                          if (recogida_de_1_dato.datos=="AGREGAR"){
                            recogida_de_1_dato.datos='<img src="'+directorioBase+'/assets/rsc/img/iconos/agregar.png">'
                          }
                          if (recogida_de_1_dato.datos=="CONTRATAR"){
                            recogida_de_1_dato.datos='<img src="'+directorioBase+'/assets/rsc/img/iconos/contratado.png">'
                          }
                          else if (recogida_de_1_dato.datos=="BLOQUEADO"){
                            recogida_de_1_dato.datos='<img src="'+directorioBase+'/assets/rsc/img/iconos/bloquear.png">'
                          }
                           $(".contenedor_tablas table tbody tr:last").append('<td><a href="'+recogida_de_1_dato.href+'" '+recogida_de_1_dato.atributos+'>'+recogida_de_1_dato.datos+'</a></td>');
                      }else{
                        if (recogida_de_1_dato=="SI"){
                          recogida_de_1_dato='<img src="'+directorioBase+'/assets/rsc/img/iconos/SI.png">'
                        }else if (recogida_de_1_dato=="NO"){
                          recogida_de_1_dato='<img src="'+directorioBase+'/assets/rsc/img/iconos/NO.png">'
                        }else if (recogida_de_1_dato=="PROCESO"){
                          recogida_de_1_dato='<img src="'+directorioBase+'/assets/rsc/img/iconos/PROCESO.png">'
                        }else if (recogida_de_1_dato=="CONTRATADO"){
                          recogida_de_1_dato='<img src="'+directorioBase+'/assets/rsc/img/iconos/SI_BLOQUEADO.png">'
                        }else if (recogida_de_1_dato=="NO_BLOQUEADO"){
                          recogida_de_1_dato='<img src="'+directorioBase+'/assets/rsc/img/iconos/NO_BLOQUEADO.png">'
                        }else if (recogida_de_1_dato=="BLOQUEADO"){
                          recogida_de_1_dato='<img src="'+directorioBase+'/assets/rsc/img/iconos/bloquear.png">'
                        }

                        if (j=="cod" || j=="id") {
                          $(".contenedor_tablas table tbody tr:last").append('<th scope="row"> <input class="form-check-input" type="checkbox" id="id_'+recogida_de_1_dato+'" value="'+recogida_de_1_dato+'"><label class="form-check-label" for="id_'+recogida_de_1_dato+'"></label></th>');
                        }else{
                          $(".contenedor_tablas table tbody tr:last").append('<td>'+recogida_de_1_dato+'</td>');
                        }
                      }

              });

              $(".contenedor_tablas table tbody").append('</tr>')
      }

          if (i+1==recogida_datos.length) {
            // $(".contenedor_tablas table").append('</tbody>');
            // $(".contenedor_tablas table").append('<tfoot><tr>')
            // $.each(recogida_de_1_fila,function(j,recogida_de_1_dato){
            //       if (j=="cod" || j=="id") {
            //         $(".contenedor_tablas table tfoot tr").append('<th scope="col" width="70px"> <input class="form-check-input" type="checkbox" id="id_todos_f" value="todos"><label class="form-check-label" for="id_todos_f"></label></th>');
            //       }else{
            //         $(".contenedor_tablas table tfoot tr").append('<th scope="col">'+j.toUpperCase()+'</th>');
            //       }
            //     });
            //     $(".contenedor_tablas table tfoot").append('</tr>');
            //     $(".contenedor_tablas table ").append('</tfoot>');
          }
        });

        $(".contenedor_tablas").append('</table">');
        ancho_tabla=$(".contenedor_tablas table tbody").width();
        $(".contenedor_tablas table").css('width',ancho_tabla);
        $(".contenedor_tablas table").css('margin','0px auto');
        $(".contenedor_tablas").append('<span class="pie_tabla">cantidad de registro totales: '+recogida_datos[0]['cantidad_registros']+'</span">');
        if (recogida_datos[0]['importe_total']) {
           $(".contenedor_tablas").append('<span class="pie_tabla">IMPORTE TOTAL: '+recogida_datos[0]['importe_total']+'€</span">');
        }
        $(".contenedor_tablas .pie_tabla").css('width',ancho_tabla);
        $("#preloader").fadeOut(500);
        bloquear_paginacion();
        marcar_todos();
        actualizar_scroll_header();
        agregar_preloader();

  }

  function bloquear_paginacion(){
      $(".contenedor_tablas nav ul li a").click(function(e){
        e.preventDefault();
      });
  }
  function marcar_todos(){

      $(".contenedor_tablas table thead #id_todos").click(function(e){
        if ($(this).prop("checked")==true){
          $(".contenedor_tablas table tbody input:checkbox").prop("checked",true);
        }else{
            $(".contenedor_tablas table tbody input:checkbox").prop("checked",false);
        }
      });
       $(".contenedor_tablas table tfoot #id_todos_f").click(function(e){
        if ($(this).prop("checked")==true){
          $(".contenedor_tablas table tbody input:checkbox").prop("checked",true);
        }else{
            $(".contenedor_tablas table tbody input:checkbox").prop("checked",false);
        }
      });
  }
  function limpiar_fitros(buscador){
      $("#limpiar_fitros").click(function(e){
          e.preventDefault();
         $("#offcanvasbuscador .offcanvas-body input").val("");
         $("#offcanvasbuscador .offcanvas-body input").prop("checked",false);
         $("#offcanvasbuscador .offcanvas-body select").val("");
         $("#offcanvasbuscador .offcanvas-body select:first-child").prop('selected',true);
         $("#offcanvasbuscador .offcanvas-body input#tamano_pagina").val("50");
    
         var url_completa=directorioBase+"/assets/ajax/TABLAS/limpiar_fitros_buscadores.ajax.php"; 
         var datos={"buscador":buscador};
          // console.log(datos);
         $.ajax({
           url:  url_completa,
           type: 'POST',
           data: datos,
           success: function(recogida_datos){
                 console.log(recogida_datos);
           },
           error: function(error){
             console.log("No llega la recogida de datos de "+url_completa);
           }
         });
      });
  };


function altura_pantalla() {
   var altura_pantalla=jQuery(window).height();
    return altura_pantalla;
}

function agregar_preloader() {
    $(".contenedor_tablas nav ul li").click(function(){
       $("#preloader").fadeIn(0);
    });
    $("#aplicar_fitros").click(function(){
       $("#preloader").fadeIn(0);
    });
}

function altura_body() {
   var altura_body=jQuery("body").height();
    return altura_body;
}
function actualizar_scroll_header(){
  console.log("Pantalla body = "+altura_body());
  console.log("Pantalla = "+(altura_pantalla()));
  if (altura_pantalla()+80<=altura_body()) {
    $(window).scroll(function(event) {
      var scrollTop = $(window).scrollTop();
      if (scrollTop>5) {
          jQuery("header").addClass("scroll");
           jQuery(".volumen-header-fixed").addClass("scroll");
           if ($(".subir_inicio").length > 0) {
              $(".subir_inicio").fadeIn(500);
           }
      }else{
         jQuery("header").removeClass("scroll");
          jQuery(".volumen-header-fixed").removeClass("scroll");
           if ($(".subir_inicio").length > 0) {
              $(".subir_inicio").fadeOut(500);
           }
      }
    });
  }
}


}