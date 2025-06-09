$(document).ready(function () {

  /* ---------- función reutilizable ---------- */
  function cargarEventos(fechaISO) {
    $.ajax({
      url: directorioBase + '/assets/ajax/gestion_eventos.ajax.php',
      method: 'POST',
      data: { accion: 'obtenerEventosDia', fecha: fechaISO },
      dataType: 'json',
      success: function (r) {
        if (r.success && r.eventos.length) {
          let html = `<h5>Eventos para el ${fechaISO.split('-').reverse().join('/')}</h5><ul>`;
          r.eventos.forEach(ev => {
            html += `<li class="mb-3 evento_clickable" data-titulo="${ev.titulo}" data-descripcion="${ev.descripcion || 'Sin descripción'}" data-imagen="${ev.imagenes || ''}">
                       <strong>${ev.titulo}</strong><br>
                       ${ev.descripcion || 'Sin descripción'}<br>`;
            if (ev.imagenes) {
                html += `<img src="/CALENDARIO/APP/assets/${ev.imagenes}" alt="${ev.titulo}" style="max-width: 150px; height: auto; margin-top: 5px;"><br>`;
            }
            html += `</li>`;
          });
          $('#contenido_eventos_dia').html(html + '</ul>');
        } else {
          $('#contenido_eventos_dia').html('<p>No hay eventos para este día.</p>');
        }
      },
      error: () => $('#contenido_eventos_dia').html('<p>Error al cargar los eventos.</p>')
    });
  }

  /* ---------- al hacer clic en un día ---------- */
  $(document).on('click', '.evento_d', function (e) {
    e.preventDefault();

    const dia  = $(this).data('d');
    const [anio, mes] = $('#fecha_calendario').val().split('-');
    const fechaISO = `${anio}-${mes.padStart(2,'0')}-${String(dia).padStart(2,'0')}`;

    cargarEventos(fechaISO);
  });

  /* ---------- cargar automáticamente el día actual ---------- */
  const hoyISO = $('#fecha_calendario').val();   
  cargarEventos(hoyISO);

  /* ---------- abrir modal al hacer clic sobre un evento ---------- */
  $(document).on('click', '.evento_clickable', function () {
    const titulo = $(this).data('titulo');
    const descripcion = $(this).data('descripcion');
    const imagen = $(this).data('imagen');

    let contenido = `<h5>${titulo}</h5><p>${descripcion}</p>`;
    if (imagen) {
      contenido += `<img src="/CALENDARIO/APP/assets/${imagen}"class="img-fluid mt-3">`;
    }

    $('#modalContenidoEvento .modal-body').html(contenido);
    const modal = new bootstrap.Modal(document.getElementById('modalContenidoEvento'));
    modal.show();
  });

});
