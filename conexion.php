<?php
$host = "localhost";
$usuario = "root";
$contrasena = ""; // Por defecto está vacío en XAMPP
$bd = "sistema1";

$conn = new mysqli($host, $usuario, $contrasena, $bd);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
