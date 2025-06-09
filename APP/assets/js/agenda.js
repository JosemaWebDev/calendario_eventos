


// __________________________________________________________ACTUALIZAR CALENDARIO Y NOTAS ___________________________________________________________
function actualizar_calendario(){

	var url_completa=directorioBase+"/assets/ajax/actualizar_calendario.ajax.php"; 
	var datos={'fecha':$("#fecha_calendario").val()};
 	// console.log(datos);

	$.ajax({
		url:  url_completa,
		type: 'POST',
		data: datos,
		success: function(recogida_datos){
	    	if (recogida_datos['fecha'][0]=="OK"){
	    		
	    			$(".calendario div.top h1").html('<button class="boton_y menos"><</button><p>'+recogida_datos['year']+'</p><button class="boton_y mas">></button>');
	    			$(".calendario div.top h2").html('<button class="boton_n menos"><</button><p>'+recogida_datos['mes']+'</p><button class="boton_n mas">></button>');
	    			$(".calendario div.dias").html("");

	    			for (var i = 0; i < recogida_datos['marcadoresDias'].length ; i++) {
	    				$(".calendario div.dias").append('<p class="marcador"><span>'+recogida_datos['marcadoresDias'][i]+'</span></p>');
	    			}

	    			for (var i = 1; i <= recogida_datos['cantidadDias'] ; i++) {

	    				if (i == 1) {
	    					var clase_inicio="grid-column-start:"+recogida_datos["empezar_dia"];
	    				} else{
	    					var clase_inicio="";
	    				}

						if (i == recogida_datos['d']) {
	    					var clase_seleccionado="seleccionado";
	    				} else{
	    					var clase_seleccionado="";
	    				}
	    			
	    				if(recogida_datos['diasEventos'].indexOf(i)!=-1){
	    				 	$(".calendario div.dias").append('<p style="'+clase_inicio+'"><a href="#'+i+'" data-d="'+i+'" class="evento_d evento '+clase_seleccionado+'">'+i+'</a></p>');
	    				}else{
	    				 	$(".calendario div.dias").append('<p style="'+clase_inicio+'"><span class="'+clase_seleccionado+'">'+i+'</span></p>');
	    				}
	    			}	
	    		clickear_dia();
	    		clickear_navegadorfecha();
	    	}
        },
        error: function(error){
           	console.log("No llega la recogida de datos de "+url_completa);
        }
    });
};

// __________________________________________________________funcionalidad DE LAS ENLACE DEL CALENDARIO  ___________________________________________________________
function  clickear_dia(){
	$('.agenda .dias p a').click(function(e){
		e.preventDefault();
		var dia=$(this).html();
		if (dia.length==1) {
			dia='0'+dia;
		}
		var fecha=$('#fecha_calendario').val();
		fecha=fecha.substring(0,8)+dia;
		$('#fecha_calendario').val(fecha);
		actualizar_calendario ();
	});
	$('.agenda .dias p:not(.marcador) span').click(function(){
		var dia=$(this).html();
		if (dia.length==1) {
			dia='0'+dia;
		}
		var fecha=$('#fecha_calendario').val();
		fecha=fecha.substring(0,8)+dia;
		$('#fecha_calendario').val(fecha);
		actualizar_calendario ();
	});
}

function clickear_navegadorfecha(){

	$(".boton_y.menos").click(function(){
		ajustarAnio(-1);
		actualizar_calendario();
	});

	$(".boton_y.mas").click(function(){
		ajustarAnio(1);
		actualizar_calendario ();
	
	});

	$(".boton_n.menos").click(function(){
		ajustarMes(-1)
		actualizar_calendario ();
		
	});

	$(".boton_n.mas").click(function(){
		ajustarMes(1)
		actualizar_calendario ();
		
	});
}


	function ajustarAnio(delta) {
      let fechaInput = $('#fecha_calendario');
      let valor = fechaInput.val();

      // Si no hay valor, usar la fecha actual
      let fecha = valor ? new Date(valor) : new Date();

      // Ajustar el año
      fecha.setFullYear(fecha.getFullYear() + delta);

      // Formatear la fecha a yyyy-mm-dd
      let yyyy = fecha.getFullYear();
      let mm = (fecha.getMonth() + 1).toString().padStart(2, '0');
      let dd = fecha.getDate().toString().padStart(2, '0');
      let nuevaFecha = `${yyyy}-${mm}-${dd}`;

      fechaInput.val(nuevaFecha);
    }

    function ajustarMes(delta) {
      let fechaInput = $('#fecha_calendario');
      let valor = fechaInput.val();

      // Si no hay valor, usar la fecha actual
      let fecha = valor ? new Date(valor) : new Date();

      let diaOriginal = fecha.getDate(); 
      fecha.setDate(1); 
      fecha.setMonth(fecha.getMonth() + delta);

      let ultimoDiaMes = new Date(fecha.getFullYear(), fecha.getMonth() + 1, 0).getDate();
      fecha.setDate(Math.min(diaOriginal, ultimoDiaMes));

      // Formatear la fecha a yyyy-mm-dd
      let yyyy = fecha.getFullYear();
      let mm = (fecha.getMonth() + 1).toString().padStart(2, '0');
      let dd = fecha.getDate().toString().padStart(2, '0');
      let nuevaFecha = `${yyyy}-${mm}-${dd}`;
      fechaInput.val(nuevaFecha);
    }


	
// ----------------------------------------------- ACCIONES AL CARGAR EL SCRIPT-----------------
if ($(".agenda").length>0) {

	$("#fecha_calendario").change(function(e){
		actualizar_calendario ();
	});
	clickear_dia();
	clickear_navegadorfecha();
}
