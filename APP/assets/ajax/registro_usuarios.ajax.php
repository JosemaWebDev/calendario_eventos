<?php
require_once '../../funcionalidad/conexion_ddbb.php';
header('Content-Type: application/json');

if (!isset($_POST['nombre'], $_POST['telefono'], $_POST['correo'], $_POST['contrasena'], $_POST['rol'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos del formulario']);
    exit;
}

$nombre     = trim($_POST['nombre']);
$telefono   = trim($_POST['telefono']);
$correo     = trim($_POST['correo']);
$contrasena = $_POST['contrasena'];
$rol        = $_POST['rol'];

// Comprobar si ya existe un usuario con ese correo
$query = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
$query->bind_param("s", $correo);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'El correo ya está registrado']);
    exit;
}

// Cifrar la contraseña
$contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

// Insertar nuevo usuario
$insert = $conexion->prepare("INSERT INTO usuarios (nombre, telefono, correo, contrasena, rol) VALUES (?, ?, ?, ?, ?)");
$insert->bind_param("sssss", $nombre, $telefono, $correo, $contrasenaHash, $rol);

if ($insert->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al insertar usuario']);
}
?>
