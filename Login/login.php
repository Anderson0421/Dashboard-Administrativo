<?php
$localhost = "localhost";
$username = "root";
$password = "";
$database = "polleria";

$conn = mysqli_connect($localhost, $username, $password, $database);
if (!$conn) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- ICONOS (Fontawesome)-->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <!-- Iniciar sesión -->
    <div class="form-container sign-in-form">
        <div class="form-box sign-in-box">
            <h2>Bienvenido a 3 <p class="leña">leñas</p></h2>
            <form method="POST" action="">
                <div class="field">
                    <i class="uil uil-user"></i>
                    <input type="text" placeholder="Ingrese su ID" name="ID" required>
                </div>
                <div class="field">
                    <i class="uil uil-lock-alt"></i>
                    <input class="password-input" type="password" name="PASS" placeholder="Ingrese su contraseña" required>
                    <div class="eye-btn"><i class="uil uil-eye-slash"></i></div>
                </div>
                <div class="forgot-link">
                    <a href="">¿Has olvidado tu contraseña?</a>
                </div>
                <input class="submit-btn" type="submit" value="Ingresar">
                <input class="submit-btn20" type="reset" value="Reset">
                <?php           
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recopilar los datos del formulario de login
    $usuario = $_POST['ID'];
    $contraseña = $_POST['PASS'];

    // Consulta para buscar al administrador
    $consulta = "SELECT * FROM administradores WHERE usuario = '$usuario' AND contraseña = '$contraseña'";
    $resultado = mysqli_query($conn, $consulta);

    // Verificar el resultado de la consulta
    if (mysqli_num_rows($resultado) === 1) {
        // Credenciales válidas, redirigir al administrador a la página de administración
        header('Location: ../index.php');
        exit();
    } else {
        // Credenciales inválidas, mostrar mensaje de error
        echo '<p style="color: white; font-weight: bold; margin:10px">Credenciales incorrectas.</p>';

    }
}?>

            </form>
        </div>

    </div>
    <script src="script.js"></script>
</body>
</html>
