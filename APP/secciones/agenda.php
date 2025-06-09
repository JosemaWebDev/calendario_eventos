<?php


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// if (!isset($_SESSION['rol'])) {
//     header("Location: /CALENDARIO/APP/LOGIN");
//     exit;
// }

include 'includes/agenda.php';
?>

<?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') { ?>
<nav class="navbar bg-light fixed-top" id="navbarOG">
  <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap">
    <div id="nav-actions-admin" class="d-flex ms-auto gap-2">
      <a href="/CALENDARIO/APP/GESTION" class="btn btn-success ms-2">Gestión</a> <a href="/CALENDARIO/APP/ANADIR" class="btn btn-success ms-2">Añadir Evento</a> <button id="btnLogout" class="btn btn-danger">Cerrar sesión</button>
    </div>
  </div>
</nav>
<?php } ?>





<!-- <div id="logout-container">
  <button id="btnLogout" class="btn btn-danger">Cerrar sesión</button>
</div> -->

<section class="agenda container my-4">
  <div class="row">
    
    <div class="col-md-6" id="columna_calendario">
      <section class="calendario_section">
        <div class="mb-3">
          <input type="date" class="form-control" maxlength="100" placeholder="FECHA: " id="fecha_calendario" name="fecha_calendario" 
          value="<?php echo $a.'-'.$M.'-'.$D ?>">
        </div>

        <section class="calendario">
          <div class="top"> 
            <h1>
              <button class="boton_y menos"><</button> 
              <p><?php echo $fecha->format("Y"); ?></p>
              <button class="boton_y mas">></button>
            </h1>
            <h2>
              <button class="boton_n menos"><</button>
              <p><?php echo $meses[$fecha->format("n")]; ?></p>
              <button class="boton_n mas">></button>
            </h2>
          </div>

          <div class="dias">
            <?php for($i = 0; $i < count($marcadoresDias); $i++){ ?>
              <p class="marcador"><span><?php echo $marcadoresDias[$i]; ?></span></p>
            <?php } ?>

            <?php for($i = 1; $i <= $cantidadDias; $i++){ ?>
              <p style="<?php echo $i == 1 ? 'grid-column-start:'.$fecha->format('N') : ''; ?>">

                <?php if(in_array($i,$diasEventos)){ ?>
                  <a href="#<?php echo $i; ?>" data-d="<?php echo $i; ?>" class="evento_d evento <?php echo $i==$d?"seleccionado":""; ?>"><?php echo $i; ?></a>
                <?php }else{ ?>
                  <span class="<?php echo $i==$d?"seleccionado":""; ?>"><?php echo $i; ?></span>
                <?php } ?>

              </p>
            <?php } ?>
          </div>
        </section>
      </section>
    </div>

    <div class="col-md-6" id="columna_eventos">
      <div id="eventos_dia_usuario">
        <div id="contenido_eventos_dia" class="mt-3"></div>
      </div>
    </div>

<div class="modal fade" id="modalContenidoEvento" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalles del evento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
      </div>
    </div>
  </div>
</div>


  </div>
</section>
