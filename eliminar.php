<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "polleria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
// Verificar si se recibió el ID del empleado a eliminar
if (isset($_GET['id'])) {
    $empleadoID = $_GET['id'];

    // Realizar la eliminación en la base de datos
    // Supongamos que tienes una conexión a la base de datos establecida

    // Consulta SQL para eliminar el empleado
    $query = "DELETE FROM Empleados WHERE ID = $empleadoID";

    // Ejecutar la consulta
    if ($conn->query($query) === TRUE) {
        // Redireccionar a la página de empleados con un mensaje de éxito
        header("Location: index.php?mensaje=Empleado eliminado correctamente");
        exit();
    } else {
        echo "Error al eliminar el empleado: " . $conn->error;
    }
} else {
    echo "Acceso inválido.";
}
?>
