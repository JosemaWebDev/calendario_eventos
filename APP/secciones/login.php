<?php
$directorioBase = "/CALENDARIO/APP"; 
?>

<section class="container mt-5" id="seccion-login">
  <h2 class="mb-4">Inicio de sesión</h2>
  <form id="formLogin" method="POST">
    <div class="mb-3">
      <label for="correo" class="form-label">Correo electrónico</label>
      <input type="email" class="form-control" id="correo" name="correo" required>
    </div>
    <div class="mb-3">
      <label for="contrasena" class="form-label">Contraseña</label>
      <input type="password" class="form-control" id="contrasena" name="contrasena" required minlength="1">
    </div>
    <button type="submit" class="btn btn-primary">Iniciar sesión</button>
  </form>

  <!-- <div class="mt-3">
    <a href=" ?= $directorioBase ?>/REGISTRO">Registrarme</a>
  </div> -->
</section>
