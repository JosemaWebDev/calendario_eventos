<?php
// ------------------------- CONEXIÓN BASE DE DATOS --------------------------------------

$servidorddbb = 'localhost';         
$usuarioddbb  = 'root';       
$claveddbb    = '';                
$nombreddbb   = 'calendario_eventos'; 

$conexion = new mysqli($servidorddbb, $usuarioddbb, $claveddbb, $nombreddbb);
$conexion->set_charset("utf8");

if ($conexion->connect_error) {
    die("ERROR de conexión: " . $conexion->connect_error);
}
?>
