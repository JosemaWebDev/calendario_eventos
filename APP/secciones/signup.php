<?php
$directorioBase = "/CALENDARIO/APP"; 
?>

<section class="container mt-1" id="seccion-registro">
  <h2 class="mb-4">Registro de Usuario</h2>
  <form id="formRegistro" method="POST">
    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre completo</label>
      <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    
    <div class="mb-3">
      <label for="telefono" class="form-label">Número de teléfono</label>
      <input type="tel" class="form-control" id="telefono" name="telefono" required pattern="[0-9]{9,}">
    </div>
    
    <div class="mb-3">
      <label for="correo" class="form-label">Correo electrónico</label>
      <input type="email" class="form-control" id="correo" name="correo" required>
    </div>
    
    <div class="mb-3">
      <label for="contrasena" class="form-label">Contraseña</label>
      <input 
        type="password" 
        class="form-control" 
        id="contrasena" 
        name="contrasena" 
        required 
        minlength="8"
        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
        title="Debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, números y símbolos especiales"
      >
    </div>

    <div class="mb-3">
      <label for="confirmar" class="form-label">Confirmar contraseña</label>
      <input 
        type="password" 
        class="form-control" 
        id="confirmar" 
        name="confirmar" 
        required 
        minlength="8"
        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
        title="Debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, números y símbolos especiales"
      >
    </div>

    <input type="hidden" name="rol" value="usuario">

    <button type="submit" class="btn btn-primary">Registrarse</button>
  </form>

  <!-- <div class="mt-3">
    <a href="*/ ?= $directorioBase ?>*//LOGIN">¿Ya tienes cuenta? Inicia sesión</a>
  </div> -->
</section>
