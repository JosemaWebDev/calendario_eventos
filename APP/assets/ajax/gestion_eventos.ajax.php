<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

session_start();

require_once '../../funcionalidad/conexion_ddbb.php';
require_once '../../funcionalidad/config.php'; // Usar si defines constantes como RUTA_BASE_IMAGENES

$response = ['success' => false];
if (!isset($_POST['accion'])) {
    $response['error'] = 'Acción no especificada.';
    echo json_encode($response);
    exit;
}

// Ruta base donde se guardan las imágenes, relativa a la raíz de la aplicación
// Ejemplo: CALENDARIO/APP/assets/imagenes_eventos/
// El script AJAX está en CALENDARIO/APP/assets/ajax/
// Por lo tanto, para acceder a la carpeta de imágenes desde aquí es: ../imagenes_eventos/
$directorioBaseImagenesServidor = __DIR__ . '/../imagenes_eventos/'; // __DIR__ es CALENDARIO/APP/assets/ajax/
$rutaBaseImagenesDB = 'imagenes_eventos/'; // Lo que se guarda en la BD

switch ($_POST['accion']) {

/* ---------- obtener meses ---------- */
case 'obtenerMeses':
    $sql = "SELECT DISTINCT MONTH(fecha_evento) AS mes,
                           YEAR(fecha_evento)  AS anio
            FROM agenda
            WHERE borrado = 0
            ORDER BY anio, mes";
    $result = $conexion->query($sql);
    if ($result) {
        $meses = [];
        while ($fila = $result->fetch_assoc()) {
            $meses[] = $fila;
        }
        $response['success'] = true;
        $response['meses']   = $meses;
    } else {
        $response['error'] = 'Error en la consulta de meses: ' . $conexion->error;
    }
    break;

/* ---------- obtener días de un mes ---------- */
case 'obtenerDias':
    if (empty($_POST['mes']) || empty($_POST['anio'])) {
        $response['error'] = 'Faltan datos: mes o año.';
        break;
    }
    $mes  = intval($_POST['mes']);
    $anio = intval($_POST['anio']);
    $sql  = "SELECT DISTINCT DAY(fecha_evento) AS dia
             FROM agenda
             WHERE MONTH(fecha_evento) = ? AND YEAR(fecha_evento) = ?
                   AND borrado = 0
             ORDER BY dia";
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $mes, $anio);
        $stmt->execute();
        $result = $stmt->get_result();
        $dias   = [];
        while ($f = $result->fetch_assoc()) {
            $dias[] = $f;
        }
        $response['success'] = true;
        $response['dias']    = $dias;
        $stmt->close();
    } else {
        $response['error'] = 'Error al preparar consulta de días: ' . $conexion->error;
    }
    break;

/* ---------- obtener eventos de un día ---------- */
case 'obtenerEventosDia':
    if (empty($_POST['fecha'])) {
        $response['error'] = 'Falta la fecha.';
        break;
    }
    $fecha = $_POST['fecha'];

    // Incluir la columna 'imagenes'
    $sql  = "SELECT id, titulo, descripcion, imagenes, 
                    DATE(fecha_evento) AS fecha_evento
             FROM agenda
             WHERE DATE(fecha_evento) = ?
                   AND borrado = 0
             ORDER BY created_at DESC";
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $fecha);
        $stmt->execute();
        $result  = $stmt->get_result();
        $eventos = [];
        while ($f = $result->fetch_assoc()) {
            $eventos[] = $f;
        }
        $response['success'] = true;
        $response['eventos'] = $eventos;
        $stmt->close();
    } else {
        $response['error'] = 'Error al preparar consulta de eventos: ' . $conexion->error;
    }
    break;

/* ---------- eliminar evento (borrado lógico) ---------- */
case 'eliminarEvento': // Esto es borrado lógico, la imagen en servidor no se toca aquí.
                        // Se podría añadir lógica para eliminar imagen si el borrado es permanente.
    if (empty($_POST['id'])) {
        $response['error'] = 'ID de evento no recibido.';
        break;
    }
    $id  = intval($_POST['id']);
    $sql = "UPDATE agenda
            SET borrado = 1, updated_at = NOW()
            WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $response['success'] = $stmt->execute();
        if (!$response['success']) {
            $response['error'] = 'No se pudo eliminar el evento: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['error'] = 'Error al preparar la consulta de eliminación: ' . $conexion->error;
    }
    break;

/* ---------- modificar evento (con manejo de imágenes) ---------- */
case 'modificarEvento':
    if (empty($_POST['id_evento']) || empty($_POST['titulo']) || empty($_POST['fecha'])) {
        $response['error'] = 'Faltan datos obligatorios para modificar.';
        break;
    }

    $id    = intval($_POST['id_evento']);
    $title = trim($_POST['titulo']);
    $desc  = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;
    $fecha = $_POST['fecha'];
    if ($desc === '') $desc = null;

    $eliminarImagenActual = isset($_POST['eliminar_imagen']) && $_POST['eliminar_imagen'] === '1';
    $rutaImagenParaGuardar = $_POST['imagen_actual'] ?? null; // Mantener la imagen actual por defecto

    // Primero, obtener la ruta de la imagen actual si existe, para borrarla si se sube una nueva o se pide eliminar.
    $sqlImagenActual = "SELECT imagenes FROM agenda WHERE id = ?";
    $stmtImg = $conexion->prepare($sqlImagenActual);
    $imagenAntigua = null;
    if ($stmtImg) {
        $stmtImg->bind_param("i", $id);
        $stmtImg->execute();
        $resultImg = $stmtImg->get_result();
        if ($filaImg = $resultImg->fetch_assoc()) {
            $imagenAntigua = $filaImg['imagenes'];
        }
        $stmtImg->close();
    }

    // Si se sube una nueva imagen
    if (isset($_FILES['imagen_evento']) && $_FILES['imagen_evento']['error'] === UPLOAD_ERR_OK) {
        // Eliminar la imagen antigua del servidor si existía
        if ($imagenAntigua) {
            $rutaCompletaAntigua = $directorioBaseImagenesServidor . basename($imagenAntigua);
            if (file_exists($rutaCompletaAntigua)) {
                unlink($rutaCompletaAntigua);
            }
        }

        $nombreImagenOriginal = basename($_FILES['imagen_evento']['name']);
        $extensionImagen = strtolower(pathinfo($nombreImagenOriginal, PATHINFO_EXTENSION));
        $nombreImagenUnico = uniqid() . "_" . time() . "." . $extensionImagen;
        
        $rutaDestinoCompletaServidor = $directorioBaseImagenesServidor . $nombreImagenUnico;
        $rutaImagenParaGuardar = $rutaBaseImagenesDB . $nombreImagenUnico; // e.g., 'imagenes_eventos/nombre.jpg'

        if (!file_exists($directorioBaseImagenesServidor)) {
            mkdir($directorioBaseImagenesServidor, 0777, true);
        }

        if (move_uploaded_file($_FILES['imagen_evento']['tmp_name'], $rutaDestinoCompletaServidor)) {
            // Imagen nueva subida y movida correctamente
        } else {
            $response['error'] = 'Error al mover la nueva imagen subida.';
            echo json_encode($response);
            exit;
        }
    } elseif ($eliminarImagenActual) { // Si no se sube nueva pero se marca para eliminar la actual
        if ($imagenAntigua) {
            $rutaCompletaAntigua = $directorioBaseImagenesServidor . basename($imagenAntigua);
             if (file_exists($rutaCompletaAntigua)) {
                unlink($rutaCompletaAntigua);
            }
        }
        $rutaImagenParaGuardar = null; // Poner a NULL en la BD
    }
    // Si no se sube nueva imagen y no se marca para eliminar, $rutaImagenParaGuardar ya tiene el valor de 'imagen_actual' (o null si no había).

    $sql  = "UPDATE agenda
             SET titulo = ?, descripcion = ?, fecha_evento = ?, imagenes = ?, updated_at = NOW()
             WHERE id = ? AND borrado = 0";
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        // El cuarto parámetro es 's' para la ruta de la imagen o null
        $stmt->bind_param("ssssi", $title, $desc, $fecha, $rutaImagenParaGuardar, $id);
        $response['success'] = $stmt->execute();
        if (!$response['success']) {
            $response['error'] = 'No se pudo modificar el evento: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['error'] = 'Error al preparar la modificación: ' . $conexion->error;
    }
    break;

/* ---------- obtener un evento por ID ---------- */
case 'obtenerEventoPorId':
    if (empty($_POST['id'])) {
        $response['error'] = 'ID no proporcionado.';
        break;
    }
    $id  = intval($_POST['id']);
    // Incluir la columna 'imagenes'
    $sql = "SELECT id, titulo, descripcion, imagenes, 
                   DATE(fecha_evento) AS fecha_evento
            FROM agenda
            WHERE id = ? AND borrado = 0";
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($ev = $res->fetch_assoc()) {
            $response['success'] = true;
            $response['evento']  = $ev;
        } else {
            $response['error'] = 'Evento no encontrado o ya borrado.';
        }
        $stmt->close();
    } else {
        $response['error'] = 'Error al preparar consulta por ID: ' . $conexion->error;
    }
    break;

default:
    $response['error'] = 'Acción no reconocida: ' . htmlspecialchars($_POST['accion']);
    break;
}

$conexion->close();
echo json_encode($response);
?>