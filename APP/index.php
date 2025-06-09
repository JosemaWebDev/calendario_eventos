<?php 
	//-------------------------- INICIAMOS SESION------------------------------------------
	date_default_timezone_set('Europe/Madrid');
	session_start();
	require_once "funcionalidad/config.php";

	// ------------------------- DDBB------------------------------------------------------
	require_once "funcionalidad/conexion_ddbb.php";

	// ------------------------- ROUTER ----------------------------------------------------
	require_once "funcionalidad/array_navegacion.php";
	require_once "funcionalidad/router.php";

	$router= new Router();
	$router->setDirectorioBase("{$direcctorio_raiz}");
	$router->urlRedireccion	 =$router->directorioBase."/ERROR_404/";

	for($i=0;$i<count($datosNav);$i++){
			$router->registrarRuta("{$datosNav[$i]['href']}","{$datosNav[$i]['fichero']}");
	}

	$router->navegar($_SERVER["REQUEST_URI"]);

	// -------- para poder usar las carpetas de la URL como variables----
	$router->parametros;
	$variables_url = explode("/",$_SERVER["REQUEST_URI"]);
	for($i = 0; $i < $router->getProfundidadDirectorios(); $i++){
		array_shift($variables_url);
	}
if ($variables_url[0]!="PDF" && $variables_url[0]!="ERROR_404") {
?>

<!DOCTYPE html>
<html lang="es">
	<head> 
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="author" content="José María Ortiz Román">
		<meta name="copyright" content="">
		<meta name="description" content="">
		<meta name="Keywords" content="">
		<meta name="robots" content="Index,Follow">
		<meta name="viewport" content="width=device-width, initial-sclae=1.0, user-scalable=no, shrink-to-fit=no">
		<link rel="canonical" href="index.php">
    		<script async type="text/javascript" src="<?php echo $router->directorioBase ?>/assets/js/Awesome_icons.js"></script>
    		<link rel="stylesheet" type="text/css" href="<?php echo $router->directorioBase ?>/assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $router->directorioBase ?>/assets/css/style.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $router->directorioBase ?>/assets/css/tablas.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $router->directorioBase ?>/assets/css/agenda.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $router->directorioBase ?>/assets/css/paginas/error_404.css">
		<link rel="icon" href="<?php echo $router->directorioBase ?>/favicon.ico" type="icon/ico">
		<link rel="stylesheet" type="text/css" href="<?php echo $router->directorioBase ?>/assets/css/paginas/gestion.css">

		<title>CALENDARIO </title>
	</head>
	<body id="inicio-top">
		<div id="popup"></div>
		<div id="pantalla_carga"></div>
			<div id="preloader"> 
				<div class="linea_preloader"></div>
				<h1>CARGANDO ...</h1>
				<div class="contenedor_preloader">
					<div class="spinner-border text-primary"  role="status">
  						<span class="visually-hidden">Loading...</span>
					</div>
					<div class="spinner-grow text-primary"  role="status">
  						<span class="visually-hidden">Loading...</span>
					</div>
				</div>
			</div>
<?php 
// ----------------------------------cargar la pagina -----------------------------------------
	include $router->getVista();
?>
		<script async type="text/javascript">
			var directorioBase=" <?php echo $router->directorioBase ;?>";
			var nombre_session_app="<?php echo $nombre_session_app; ?>";
		</script>
		<script type="text/javascript" src="<?php echo $router->directorioBase ?>/assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo $router->directorioBase ?>/assets/js/js.cookie.min.js"></script>
		<script  type="text/javascript" src="<?php echo $router->directorioBase ?>/assets/js/jquery-3.4.1.min.js"></script>
		<script async type="text/javascript" src="<?php echo $router->directorioBase ?>/assets/js/jquery-ui.js"></script> 	
		<script async type="text/javascript" src="<?php echo $router->directorioBase ?>/assets/js/sweetalert2.min.js"></script>
		<script async type="text/javascript" src="<?php echo $router->directorioBase ?>/assets/js/script.js"></script>
		<script async type="text/javascript" src="<?php echo $router->directorioBase ?>/assets/js/tablas.js"></script>

		<script async type="text/javascript" src="<?php echo $router->directorioBase ?>/assets/js/agenda.js"></script>
		<script async type="text/javascript" src="<?php echo $router->directorioBase ?>/assets/js/registro_usuarios.js"></script>
		<script async type="text/javascript" src="<?php echo $router->directorioBase ?>/assets/js/login_usuarios.js"></script>
		<script async type="text/javascript" src="<?php echo $router->directorioBase ?>/assets/js/gestion_eventos.js"></script>
		<script async type="text/javascript" src="<?php echo $router->directorioBase ?>/assets/js/anadir_evento.js"></script>
		<script async type="text/javascript" src="<?php echo $router->directorioBase ?>/assets/js/mostrar_eventos_usuario.js"></script>

		<script async type="text/javascript">

<?php 

?>
		</script>
	</body>
</html>

<?php
		}else{ ?>
		<?php 
			include $router->getVista();
		} 
		$conexion->close();
?>
