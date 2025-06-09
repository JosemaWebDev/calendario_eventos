<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin')  {
    header("Location: /CALENDARIO/APP/LOGIN");
    exit;
}
?>

<nav class="navbar bg-light fixed-top" id="navbarOG">
  <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap">
    <div id="nav-actions-admin" class="d-flex ms-auto gap-2">
      <button id="btnInicio" class="btn btn-primary">Inicio</button> <a href="/CALENDARIO/APP/GESTION" class="btn btn-success ms-2">Gestión</a> <button id="btnLogout" class="btn btn-danger">Cerrar sesión</button>
    </div>
  </div>
</nav>

<section class="añadir_evento container my-4">
  <h2>Añadir nuevo evento</h2>
  <form id="form_nuevo_evento" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="titulo_evento" class="form-label">Título del evento:</label>
      <input type="text" class="form-control" id="titulo_evento" name="titulo_evento" required>
    </div>
    <div class="mb-3">
      <label for="descripcion_evento" class="form-label">Descripción:</label>
      <textarea class="form-control" id="descripcion_evento" name="descripcion_evento" rows="3"></textarea>
    </div>
    <div class="mb-3">
      <label for="imagen_evento" class="form-label">Imagen del evento:</label>
      <input type="file" class="form-control" id="imagen_evento" name="imagen_evento" accept="image/*">
    </div>
    <div class="mb-3">
      <label for="fecha_evento" class="form-label">Fecha:</label>
      <input type="date" class="form-control" id="fecha_evento" name="fecha_evento" required>
    </div>
    <button type="submit" class="btn btn-primary">Guardar evento</button>
  </form>
  <div id="mensaje_evento" class="mt-3"></div>
</section>