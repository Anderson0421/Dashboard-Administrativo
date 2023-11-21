<?php
// Conexión a la base de datos (reemplaza los valores con los tuyos)
$host = 'localhost';
$usuario_bd = 'root';
$contrasena_bd = '';
$nombre_bd = 'polleria';

$conexion = mysqli_connect($host, $usuario_bd, $contrasena_bd, $nombre_bd);
if (!$conexion) {
    die('Error de conexión: ' . mysqli_connect_error());
}

// Obtener los datos del formulario
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

// Insertar los datos en la tabla de administradores
$sql = "INSERT INTO administradores (usuario, contraseña) VALUES ('$usuario', '$contrasena')";

if (mysqli_query($conexion, $sql)) {
    echo "Administrador registrado exitosamente.";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
}

mysqli_close($conexion);
?>
