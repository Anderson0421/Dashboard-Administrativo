<?php

// Establecer conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "polleria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se recibió el parámetro ID válido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se enviaron los datos del formulario de actualización
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Obtener los datos del formulario de actualización
        $empleadoID = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $cargo = $_POST['cargo'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $genero = $_POST['genero'];
        $direccion = $_POST['direccion'];
        $distrito = $_POST['distrito'];


        // Realizar la actualización en la base de datos
        // Supongamos que tienes una conexión a la base de datos establecida

        // Consulta SQL para actualizar los datos del empleado
        $query = "UPDATE Empleados SET Nombre = '$nombre', Apellido = '$apellido', Cargo = '$cargo', Email = '$email', Telefono = '$telefono', Genero = '$genero', Direccion = '$direccion', Distrito = '$distrito' WHERE ID = $empleadoID";

        // Ejecutar la consulta
        if ($conn->query($query) === TRUE) {
            // Redireccionar a la página de empleados con un mensaje de éxito
            header("Location: index.php?mensaje=Empleado actualizado correctamente");
            exit();
        } else {
            echo "Error al actualizar el empleado: " . $conn->error;
        }
    }
}

// Verificar si se recibió el parámetro ID válido
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Obtener el ID del empleado a editar
    $empleadoID = $_GET['id'];

    // Realizar la consulta para obtener los datos del empleado
    // Supongamos que tienes una conexión a la base de datos establecida

    // Consulta SQL para obtener los datos del empleado por su ID
    $query = "SELECT * FROM Empleados WHERE ID = $empleadoID";

    // Ejecutar la consulta
    $result = $conn->query($query);

    // Verificar si se obtuvieron resultados
    if ($result->num_rows == 1) {
        // Obtener los datos del empleado
        $row = $result->fetch_assoc();
        $nombre = $row['Nombre'];
        $apellido = $row['Apellido'];
        $email = $row['Email'];
        $telefono = $row['Telefono'];
        $cargo = $row['Cargo'];
        $genero = $row['Genero'];
        $direccion = $row['Direccion'];
        $distrito = $row['Distrito'];

        // Mostrar el formulario de edición
        ?>


        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="stylesforms.css">
<div id="particles-js">
    <div class="container">
        <center><header>Editar empleado</header></center>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form first">
                <div class="details personal">
                    <span class="title">Detalles personales</span>
                    <div class="fields">
                    <input type="hidden" name="id" value="<?php echo $empleadoID; ?>">
                        <div class="input-field">
                            <label>Nombre</label>
                            <input type="text" name="nombre"  value="<?php echo $nombre; ?>">
                        </div>
                        <div class="input-field">
                            <label for="apellido">Apellido</label>
                            <input type="text" name="apellido" value="<?php echo $apellido; ?>">
                        </div>
                        <div class="input-field">
                            <label for="email">Email</label>
                            <input type="email" name="email" value="<?php echo $email; ?>">
                        </div>
                        <div class="input-field">
                            <label for="telefono">Teléfono</label>
                            <input type="text" name="telefono" value="<?php echo $telefono; ?>">
                        </div>
                        <div class="input-field">
                            <label>Genero</label>
                            <select name="genero">
                                <option disabled><?php echo $genero; ?></option>
                                <option>Hombre</option>
                                <option>Mujer</option>
                                <option>Otro</option>
                            </select>
                        </div>
                        <div class="input-field">
                            <label>Cargo</label>
                            <select name="cargo">
                                <option><?php echo $cargo; ?></option>
                                <option>Cocinero</option>
                                <option>Chef</option>
                                <option>Mesero</option>
                                <option>Cajero</option>
                                <option>Supervisor</option>
                                <option>Gerente</option>
                                <option>Repartidor</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="details ID">
                    <span class="title">Informacion</span>
                    <div class="fields">
                    <div class="input-field">
                        <label>Direccion</label>
                        <input type="text" name="direccion" value="<?php echo $direccion; ?>" placeholder="Direccion" required autocomplete="off">
                    </div>
                    <div class="input-field">
                        <label>Distrito</label>
                        <input type="text" name="distrito" value="<?php echo $distrito; ?>" placeholder="Ingrese su distrito" required autocomplete="off">
                    </div>
                    </div>
                    <center><button class="submit">
                        <span class="btnText">Actualizar</span>
                        <i class="uil uil-navigator"></i>
                    </button></center>
                </div>
            </div>
        </form>
    </div>
</div>

        <script src="particles.min.js"></script>
        <script src="particlesjs-config.json"></script>
        <script src="Scriptform.js"></script>
        <?php
    } else {
        echo "No se encontró el empleado.";
    }
} else {
    echo "ID de empleado inválido.";
}
?>
