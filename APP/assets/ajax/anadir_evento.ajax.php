<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../funcionalidad/conexion_ddbb.php';
// require_once '../../funcionalidad/config.php'; // Descomenta si es necesario para constantes de ruta.
header('Content-Type: application/json');
$response = ['success' => false];

/* ---------- validar datos mínimos ---------- */
if (empty($_POST['titulo_evento']) || empty($_POST['fecha_evento'])) {
    $response['error'] = 'Faltan datos obligatorios (título o fecha).';
    echo json_encode($response);
    exit;
}

/* ---------- recoger datos ---------- */
$titulo      = trim($_POST['titulo_evento']);
$descripcion = isset($_POST['descripcion_evento']) ? trim($_POST['descripcion_evento']) : null;
$fecha       = $_POST['fecha_evento'];
if ($descripcion === '') $descripcion = null;

$rutaImagenParaGuardarEnDB = null;

// Procesar imagen si se ha subido alguna
if (isset($_FILES['imagen_evento']) && $_FILES['imagen_evento']['error'] === UPLOAD_ERR_OK) {
    
    // Directorio de destino para las imágenes, relativo al script actual.
    // Script: CALENDARIO/APP/assets/ajax/anadir_evento.ajax.php
    // Destino: CALENDARIO/APP/assets/imagenes_eventos/
    $directorioDestinoServidor = __DIR__ . '/../imagenes_eventos/';

    // Crear el nombre único para la imagen, usando el formato de tu ejemplo de referencia [cite: 12, 13]
    $nombreOriginalImagen = basename($_FILES['imagen_evento']['name']);
    $nombreImagenUnico = uniqid() . "_" . $nombreOriginalImagen;
    
    $rutaCompletaDestinoServidor = $directorioDestinoServidor . $nombreImagenUnico;

    // Ruta para guardar en la base de datos. [cite: 4]
    // Se guardará como "imagenes_eventos/nombre_unico_original.jpg"
    // Esto es relativo a la carpeta 'assets' de tu aplicación, por ejemplo.
    $rutaImagenParaGuardarEnDB = 'imagenes_eventos/' . $nombreImagenUnico;

    // Crear el directorio de destino en el servidor si no existe
    if (!file_exists($directorioDestinoServidor)) {
        if (!mkdir($directorioDestinoServidor, 0777, true)) {
            $response['error'] = 'Error al crear el directorio de imágenes. Verifica los permisos.';
            $response['debug_mkdir_path'] = $directorioDestinoServidor;
            echo json_encode($response);
            exit;
        }
    }
    
    // Mover el archivo subido al directorio de destino en el servidor
    if (!move_uploaded_file($_FILES['imagen_evento']['tmp_name'], $rutaCompletaDestinoServidor)) {
        $response['error'] = 'Error al mover la imagen subida. Verifica los permisos del directorio: ' . $directorioDestinoServidor;
        $response['debug_move_path'] = $rutaCompletaDestinoServidor;
        $response['debug_tmp_name'] = $_FILES['imagen_evento']['tmp_name'];
        $response['debug_upload_error'] = $_FILES['imagen_evento']['error'];
        echo json_encode($response);
        exit;
    }
}


/* ---------- insertar ---------- */
// La columna en la BD se llama 'imagenes' según la captura de tu tabla.
$sql  = "INSERT INTO agenda (titulo, descripcion, fecha_evento, imagenes,
                             created_at, updated_at, borrado)
         VALUES (?, ?, ?, ?, NOW(), NOW(), 0)";
$stmt = $conexion->prepare($sql);

if ($stmt) {
    // 's' para la ruta de la imagen (puede ser null si no se subió imagen)
    $stmt->bind_param("ssss", $titulo, $descripcion, $fecha, $rutaImagenParaGuardarEnDB); 
    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['error'] = 'Error al guardar el evento en la base de datos: ' . $stmt->error;
    }
    $stmt->close();
} else {
    $response['error'] = 'Error al preparar la consulta SQL: ' . $conexion->error;
}

$conexion->close();
echo json_encode($response);
?>