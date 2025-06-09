$(document).ready(function () {
  $('#formLogin').on('submit', function (e) {
    e.preventDefault();

    const formData = $(this).serialize();

    $.ajax({
      type: 'POST',
      url: directorioBase + '/assets/ajax/login_usuarios.ajax.php',
      data: formData,
      success: function (response) {
        try {
          if (typeof response === 'string') {
            response = JSON.parse(response);
          }

          if (response.success) {
            Swal.fire({
              icon: 'success',
              title: '¡Bienvenido!',
              text: 'Hola ' + response.nombre,
              timer: 1500,
              showConfirmButton: false
            });

            setTimeout(() => {
              if (response.rol === 'admin') {
                location.href = directorioBase + '/GESTION';
              } else {
                location.href = directorioBase + '/';
              }
            }, 1600); 

          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message || 'Credenciales incorrectas.'
            });
          }

        } catch (error) {
          console.error('Respuesta inválida:', response);
          Swal.fire({
            icon: 'error',
            title: 'Error inesperado',
            text: 'La respuesta del servidor no es JSON válido.'
          });
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error:', status, error);
        console.log('Respuesta completa:', xhr.responseText);
        Swal.fire({
          icon: 'error',
          title: 'Error de conexión',
          text: 'No se pudo conectar con el servidor.'
        });
      }
    });
  });
});
