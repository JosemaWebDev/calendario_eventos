$(document).ready(function() {
    $('#form_nuevo_evento').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this); 
        $.ajax({
            url: directorioBase + '/assets/ajax/anadir_evento.ajax.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log("Respuesta recibida:", response);

                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Evento guardado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#form_nuevo_evento')[0].reset();
                    if (typeof obtenerMeses === "function") {
                        obtenerMeses(); 
                        $('#dias_eventos').html('');
                        $('#eventos_dia').html('');
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.error || 'No se pudo guardar el evento.'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error del servidor',
                    text: 'No se pudo procesar la solicitud.'
                });
            }
        });
    });
});