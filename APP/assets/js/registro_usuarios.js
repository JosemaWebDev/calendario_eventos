$(document).ready(function () {
  $('#formRegistro').on('submit', function (e) {
    e.preventDefault();

    const pass = $('#contrasena').val();
    const conf = $('#confirmar').val();

    if (pass !== conf) {
      Swal.fire({
        icon: 'warning',
        title: 'Contraseñas no coinciden',
        text: 'Por favor, asegúrate de que ambas contraseñas coincidan.'
      });
      return;
    }

    const formData = $(this).serialize();

    $.ajax({
      type: 'POST',
      url: directorioBase + '/assets/ajax/registro_usuarios.ajax.php',
      data: formData,
      success: function (response) {
        try {
          const res = typeof response === 'string' ? JSON.parse(response) : response;

          if (res.success) {
            Swal.fire({
              icon: 'success',
              title: '¡Registro exitoso!',
              text: 'Usuario registrado correctamente.',
              timer: 2000,
              showConfirmButton: false
            });

            setTimeout(() => {
              location.href = directorioBase + '/LOGIN';
            }, 2100);
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: res.message || 'No se pudo registrar el usuario.'
            });
          }
        } catch (e) {
          console.error('Respuesta no válida del servidor:', response);
          Swal.fire({
            icon: 'error',
            title: 'Error inesperado',
            text: 'La respuesta del servidor no es válida.'
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: 'error',
          title: 'Error de conexión',
          text: 'No se pudo contactar con el servidor.'
        });
      }
    });
  });
});
