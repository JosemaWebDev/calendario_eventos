<?php
require_once '../../funcionalidad/conexion_ddbb.php';
session_start();
header('Content-Type: application/json');

// Validar datos
if (!isset($_POST['correo'], $_POST['contrasena'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit;
}

$correo = trim($_POST['correo']);
$contrasena = $_POST['contrasena'];

// Buscar usuario
$query = $conexion->prepare("SELECT id, nombre, contrasena, rol FROM usuarios WHERE correo = ?");
$query->bind_param("s", $correo);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    if (password_verify($contrasena, $usuario['contrasena'])) {
        // Iniciar sesión
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];

        echo json_encode([
            'success' => true,
            'nombre' => $usuario['nombre'],
            'rol' => $usuario['rol']
]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
}
?>
