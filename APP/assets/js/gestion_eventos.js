$(document).ready(function () {
    
    const prefijoApp = '/CALENDARIO/APP'; 

    obtenerMeses();
    /* ---------- meses ---------- */
    function obtenerMeses() {
        $.ajax({
            url: directorioBase + '/assets/ajax/gestion_eventos.ajax.php',
            method: 'POST',
            data: { accion: 'obtenerMeses' },
            dataType: 'json',
            success: function (r) {
                if (!r.success) {
                    $('#meses_eventos').html('<p class="text-danger">No se pudieron cargar los meses.</p>');
                    return;
                }
                let html = '<h3>Meses con eventos</h3><ul class="list-inline">';
                r.meses.forEach(m => {
                    html += `<li class="list-inline-item mb-2">
                                <button class="btn btn-outline-primary mes"
                                         data-mes="${m.mes}" data-anio="${m.anio}">
                                        ${String(m.mes).padStart(2,'0')}/${m.anio}
                                </button>
                           </li>`;
                });
                $('#meses_eventos').html(html + '</ul>');
            },
            error: () => $('#meses_eventos').html('<p class="text-danger">Error al conectar con el servidor.</p>')
        });
    }

    /* ---------- clic mes ---------- */
    $('#meses_eventos').on('click', '.mes', function () {
        const mes  = $(this).data('mes');
        const anio = $(this).data('anio');

        $('.mes').removeClass('active');
        $(this).addClass('active');
        $('#dias_eventos,#eventos_dia').empty();

        $.ajax({
            url: directorioBase + '/assets/ajax/gestion_eventos.ajax.php',
            method: 'POST',
            data: { accion: 'obtenerDias', mes, anio },
            dataType: 'json',
            success: function (r) {
                if (!r.success || !r.dias.length) {
                    $('#dias_eventos').html('<p>No hay días con eventos para este mes.</p>');
                    return;
                }
                let html = '<h4>Días con eventos</h4><ul class="list-inline">';
                r.dias.forEach(d => {
                    html += `<li class="list-inline-item mb-2">
                                 <button class="btn btn-outline-secondary dia"
                                        data-fecha="${anio}-${String(mes).padStart(2,'0')}-${String(d.dia).padStart(2,'0')}">
                                Día ${d.dia}
                                </button>
                             </li>`;
                });
                $('#dias_eventos').html(html + '</ul>');
            },
            error: () => $('#dias_eventos').html('<p class="text-danger">Error al conectar con el servidor.</p>')
        });
    });

    /* ---------- clic día ---------- */
    $('#dias_eventos').on('click', '.dia', function () {
        $('.dia').removeClass('btn-primary active').addClass('btn-outline-secondary');
        $(this).removeClass('btn-outline-secondary').addClass('btn-primary active');

        const fecha = $(this).data('fecha');

        $.ajax({
            url: directorioBase + '/assets/ajax/gestion_eventos.ajax.php',
            method: 'POST',
            data: { accion: 'obtenerEventosDia', fecha },
            dataType: 'json',
            success: function (r) {
                if (!r.success) {
                    $('#eventos_dia').html(`<p class="text-danger">${r.error || 'No se pudieron cargar los eventos.'}</p>`);
                    return;
                }

                const f = fecha.split('-').reverse().join('/');
                let html = `<h5>Eventos para ${f}</h5>`;

                if (!r.eventos.length) {
                    html += '<p>No hay eventos este día.</p>';
                    $('#eventos_dia').html(html);
                    return;
                }

                html += '<ul class="list-unstyled">';
                r.eventos.forEach(e => {
                    html += `<li class="mb-3 p-3 border rounded shadow-sm">
                               <strong>${e.titulo}</strong><br>
                               ${e.descripcion || 'Sin descripción'}<br>`;
                    if (e.imagenes) { 
                  
                        html += `<img src="${prefijoApp}/assets/${e.imagenes}" alt="Imagen del evento" style="max-width: 150px; max-height: 150px; margin-top: 5px; margin-bottom: 10px;"><br>`;
                    }
                    html += `<button class="btn btn-warning btn-sm modificar-evento" data-id="${e.id}">Modificar</button>
                               <button class="btn btn-danger  btn-sm eliminar-evento"  data-id="${e.id}">Eliminar</button>
                             </li><hr>`;
                });
                $('#eventos_dia').html(html + '</ul>');
            },
            error: () => $('#eventos_dia').html('<p class="text-danger">Error al conectar con el servidor.</p>')
        });
    });

    /* ---------- eliminar evento ---------- */
    $('#eventos_dia').on('click', '.eliminar-evento', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción marcará el evento como eliminado.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if (!result.isConfirmed) return;

            $.post(directorioBase + '/assets/ajax/gestion_eventos.ajax.php',
                {accion:'eliminarEvento',id},'json')
             .done(r=>{
                 if(!r.success) return Swal.fire('Error',r.error||'No se pudo eliminar','error');
                 Swal.fire({icon:'success',title:'Evento eliminado',timer:1500,showConfirmButton:false});
                 $('.dia.active').click();
             })
             .fail(()=>Swal.fire('Error del servidor','No se pudo eliminar el evento','error'));
        });
    });

    $('#eventos_dia').on('click', '.modificar-evento', function () {
        const eventoId = $(this).data('id'); 

        if (typeof eventoId === 'undefined') {
            Swal.fire('Error', 'No se pudo obtener el ID del evento. El atributo data-id podría faltar en el botón.', 'error');
            return;
        }

        $('#input_eliminar_imagen').val('0'); 
        $('#editar_imagen_evento').val(''); 

        $.post(directorioBase + '/assets/ajax/gestion_eventos.ajax.php', {
            accion: 'obtenerEventoPorId',
            id: eventoId 
        }, 'json')
        .done(function(r) {
            if (!r.success) {
                Swal.fire('Error', r.error || 'No se pudo obtener el evento', 'error');
                return;
            }

            const e = r.evento;
            $('#editar_id_evento').val(e.id);
            $('#editar_titulo_evento').val(e.titulo);
            $('#editar_descripcion_evento').val(e.descripcion);
            $('#editar_fecha_evento').val(e.fecha_evento);
            
            // Manejo de la imagen actual en el modal
            if (e.imagenes) {
                $('#imagen_actual_preview_modal').attr('src', `${prefijoApp}/assets/${e.imagenes}`);
                $('#editar_imagen_actual').val(e.imagenes); // Guardar ruta de imagen actual
                $('#contenedor_imagen_actual_modal').show();
            } else {
                $('#imagen_actual_preview_modal').attr('src', '');
                $('#editar_imagen_actual').val('');
                $('#contenedor_imagen_actual_modal').hide();
            }

            // Mostrar el modal
            var modalEditar = new bootstrap.Modal(document.getElementById('modalEditarEvento'));
            modalEditar.show();
        })
        .fail(function() {
            Swal.fire('Error del servidor', 'No se pudo conectar para obtener los datos del evento.', 'error');
        });
    });

    $('#btnEliminarImagenActual').on('click', function() {
        $('#input_eliminar_imagen').val('1'); 
        $('#contenedor_imagen_actual_modal').hide(); 
        Swal.fire({
            icon: 'info',
            title: 'Imagen marcada para eliminar',
            text: 'La imagen actual se eliminará al guardar los cambios.',
            showConfirmButton: false,
            timer: 2000
        });
    });

    /* ---------- guardar cambios ---------- */
    $('#formEditarEvento').on('submit', function (e) {
        e.preventDefault();
        const fd = new FormData(this);
        fd.append('accion','modificarEvento');

        $.ajax({
            url: directorioBase + '/assets/ajax/gestion_eventos.ajax.php',
            method:'POST',
            data:fd,
            contentType:false,
            processData:false,
            dataType:'json'
        })
        .done(r=>{
            if(!r.success) return Swal.fire('Error',r.error||'No se pudo guardar','error');
            Swal.fire({icon:'success',title:'Evento modificado',timer:1500,showConfirmButton:false});
            bootstrap.Modal.getInstance(document.getElementById('modalEditarEvento')).hide();
            $('.dia.active').click();
        })
        .fail(()=>Swal.fire('Error del servidor','No se pudo guardar el evento','error'));
    });
});