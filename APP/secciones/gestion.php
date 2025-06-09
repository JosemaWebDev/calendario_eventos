<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");



if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    
    header("Location: /CALENDARIO/APP/LOGIN"); 
    exit;
}


$directorioBase = '/CALENDARIO/APP'; 
?>

<nav class="navbar bg-light fixed-top" id="navbarOG">
  <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap">
    <div id="nav-actions-admin" class="d-flex ms-auto gap-2">
      <button id="btnInicio" class="btn btn-primary">Inicio</button> <a href="/CALENDARIO/APP/ANADIR" class="btn btn-success ms-2">Añadir Evento</a> <button id="btnLogout" class="btn btn-danger">Cerrar sesión</button>
    </div>
  </div>
</nav>

<section class="lista_eventos container mt-5 pt-5"> <h2 class="mb-4">Gestión de Eventos</h2>
  
  <div id="meses_eventos" class="mb-3">
    </div>
  
  <div id="dias_eventos" class="mb-3">
    </div>
  
  <div id="eventos_dia">
    </div>
</section>

<div class="modal fade" id="modalEditarEvento" tabindex="-1" aria-labelledby="modalEditarEventoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="formEditarEvento" class="modal-content" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarEventoLabel">Modificar Evento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editar_id_evento" name="id_evento">
        <input type="hidden" id="editar_imagen_actual" name="imagen_actual">

        <div class="mb-3">
          <label for="editar_titulo_evento" class="form-label">Título</label>
          <input type="text" class="form-control" id="editar_titulo_evento" name="titulo" required>
        </div>

        <div class="mb-3">
          <label for="editar_descripcion_evento" class="form-label">Descripción</label>
          <textarea class="form-control" id="editar_descripcion_evento" name="descripcion"></textarea>
        </div>

        <div class="mb-3">
          <label for="editar_fecha_evento" class="form-label">Fecha</label>
          <input type="date" class="form-control" id="editar_fecha_evento" name="fecha" required>
        </div>

        <div class="mb-3">
            <label for="editar_imagen_evento" class="form-label">Cambiar imagen del evento (opcional)</label>
            <input type="file" class="form-control" id="editar_imagen_evento" name="imagen_evento" accept="image/*">
        </div>
        
        <div id="contenedor_imagen_actual_modal" class="mb-3" style="display: none;">
            <label class="form-label">Imagen actual:</label><br>
            <img id="imagen_actual_preview_modal" src="" alt="Imagen actual" style="max-width: 200px; max-height: 200px; margin-bottom: 10px; border:1px solid #ccc; padding:5px;">
            <br>
            <button type="button" id="btnEliminarImagenActual" class="btn btn-sm btn-warning">Eliminar imagen actual</button>
            <input type="hidden" name="eliminar_imagen" id="input_eliminar_imagen" value="0">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
      </div>
    </form>
  </div>
</div>