<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "polleria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
// Verificar si se recibieron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cargo = $_POST['cargo'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $dni = $_POST['dni'];
    $genero = $_POST['genero'];
    $direccion = $_POST['direccion'];
    $nacionalidad = $_POST['nacionalidad'];
    $estado = $_POST['estado'];
    $distrito = $_POST['distrito'];

    // Realizar la inserción en la base de datos
    // Supongamos que tienes una conexión a la base de datos establecida

    // Consulta SQL para insertar un nuevo empleado
    $query = "INSERT INTO Empleados (Nombre, Apellido, Email, Cargo, Telefono, DNI, Genero, Direccion, Nacionalidad, Estado, Distrito)
            VALUES ('$nombre', '$apellido', '$email', '$cargo', '$telefono', '$dni', '$genero', '$direccion', '$nacionalidad', '$estado', '$distrito')";

    // Ejecutar la consulta
    if ($conn->query($query) === TRUE) {
        // Redireccionar a la página de empleados con un mensaje de éxito
        header("Location: index.php?mensaje=Empleado agregado correctamente");
        exit();
    } else {
        echo "Error al agregar el empleado: " . $conn->error;
    }
}

?>

<!-- Formulario para agregar un nuevo empleado -->
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="stylesforms.css">
<div id="particles-js">
    <div class="container">
        <center><header>Registro de empleado</header></center>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form first">
                <div class="details personal">
                    <span class="title">Detalles personales</span>
                    <div class="fields">
                        <div class="input-field">
                            <label>Nombre</label>
                            <input type="text" name="nombre"  placeholder="Ingrese su nombre" required autocomplete="off">
                        </div>
                        <div class="input-field">
                            <label>Apellido</label>
                            <input type="text" name="apellido" placeholder="Ingrese su apellido" required autocomplete="off">
                        </div>
                        <div class="input-field">
                            <label>Tu correo</label>
                            <input type="email" name="email"  placeholder="Ingrese su correo" required autocomplete="off">
                        </div>
                        <div class="input-field">
                            <label>Telefono</label>
                            <input type="tel" name="telefono" placeholder="Ingrese su telefono" required autocomplete="off">
                        </div>
                        <div class="input-field">
                            <label>Genero</label>
                            <select name="genero" required>
                                <option disabled selected>Seleccione su genero</option>
                                <option>Hombre</option>
                                <option>Mujer</option>
                                <option>Otro</option>
                            </select>
                        </div>
                        <div class="input-field">
                            <label>Cargo</label>
                            <select required name="cargo">
                                <option disabled selected>Seleccione su cargo</option>
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
                    <span class="title">Numero de identidad</span>
                    <div class="fields">
                        <div class="input-field">
                            <label>DNI</label>
                            <input id="dnipt" name="dni" type="text" placeholder="Ingrese su dni" required autocomplete="off">
                        </div>
                    </div>
                    <center><button class="nextBtn">
                        <span class="btnText">Next</span>
                        <i class="uil uil-navigator"></i>
                    </button></center>
                </div>
            </div>
            <div class="form second">
                <div class="details address">
                    <span class="title">Detalles Ubicacion</span>
                    <div class="fields">
                        <div class="input-field">
                            <label>Direccion</label>
                            <input type="text" name="direccion" placeholder="Direccion" required autocomplete="off">
                        </div>
                        <div class="input-field">
                            <label>Nacionalidad</label>
                            <input type="text" name="nacionalidad" placeholder="Nacionalidad" required autocomplete="off">
                        </div>
                        <div class="input-field">
                            <label>Estado</label>
                            <input type="text"  name="estado" placeholder="Ingrese su estado" required autocomplete="off">
                        </div>
                        <div class="input-field">
                            <label>Distrito</label>
                            <input type="text" name="distrito" placeholder="Ingrese su distrido" required autocomplete="off">
                        </div>
                    </div>
                    </div>
                    <div class="buttons">
                        <div class="backBtn">
                            <i class="uil uil-navigator"></i>
                            <span class="btnText">Back</span>
                        </div>
                        
                        <button class="sumbit">
                            <span class="btnText">Submit</span>
                            <i class="uil uil-navigator"></i>
                        </button>
                    </div>
                </div> 
        </form>
    </div>
</div>
    <script src="particles.min.js"></script>
    <script src="particlesjs-config.json"></script>
    <script src="Scriptform.js"></script>