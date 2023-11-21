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

// Consulta SQL para obtener la suma total de los precios
$totalPrecios = 0;

$sql = "SELECT SUM(Total) as total_precios FROM reservas WHERE status = 'pagado'";

$result = $conn->query($sql);

// Obtener el resultado de la suma total de los precios


if ($result->num_rows > 0) {
$row = $result->fetch_assoc();
$totalPrecios = $row["total_precios"];
}
// Obtener la fecha y hora actual
$currentDateTime = date('Y-m-d H:i:s');

// Calcular la fecha y hora hace 24 horas
$last24HoursDateTime = date('Y-m-d H:i:s', strtotime('-24 hours'));

// Calcular la fecha y hora hace 7 días
$last7DaysDateTime = date('Y-m-d H:i:s', strtotime('-7 days'));

// Consulta SQL para obtener el total de ventas de las últimas 24 horas
$sql24Hours = "SELECT SUM(Total) as total_ventas_24h FROM reservas WHERE fecha_registro >= '$last24HoursDateTime' AND status = 'pagado'";

$result24Hours = $conn->query($sql24Hours);

// Obtener el resultado del total de ventas de las últimas 24 horas
$totalVentas24h = 0;

if ($result24Hours->num_rows > 0) {
    $row24Hours = $result24Hours->fetch_assoc();
    $totalVentas24h = $row24Hours["total_ventas_24h"];
}

// Consulta SQL para obtener el total de ventas de los últimos 7 días
$sql7Days = "SELECT SUM(Total) as total_ventas_7d FROM reservas WHERE fecha_registro >= '$last7DaysDateTime' AND status = 'pagado'";


$result7Days = $conn->query($sql7Days);

// Obtener el resultado del total de ventas de los últimos 7 días
$totalVentas7d = 0;

if ($result7Days->num_rows > 0) {
    $row7Days = $result7Days->fetch_assoc();
    $totalVentas7d = $row7Days["total_ventas_7d"];
}
// Cierre de las ventas

// Tablas para la seccion de pedidos
$limit = 5; // Número máximo de filas a mostrar
$sqlTabla = "SELECT pedido, cantidad, Total, fecha_registro, email, status FROM reservas ORDER BY fecha_registro DESC LIMIT " . $limit;

$resultPedidos = $conn->query($sqlTabla);


// ======================================== Tabla 2 ================================ //

// Obtener las fechas o días seleccionados por el usuario
$fechaInicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : date('Y-m-d');
$fechaFin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : date('Y-m-d');
// Tabla pero sin limites
$sqlTabla2 = "SELECT * FROM reservas WHERE fecha_registro BETWEEN '$fechaInicio' AND '$fechaFin'";

$result2 = $conn->query($sqlTabla2);

// Fin de tabla para inicio y fin

// ================================ Inicio para los graficos ================================

$query = "SELECT pedido, COUNT(*) as total_pedidos FROM reservas GROUP BY pedido ORDER BY total_pedidos DESC LIMIT 5";
$resultGrafics = mysqli_query($conn, $query);

// Crear un array para almacenar los datos de los productos
$productos = array();
while ($row = mysqli_fetch_assoc($resultGrafics)) {
    $productos[] = $row;
}

// Convertir los datos de los productos en formato adecuado para el gráfico
$categories = array();
$data = array();
foreach ($productos as $producto) {
    $categories[] = $producto['pedido'];
    $data[] = (int)$producto['total_pedidos'];
}

// Codificar los datos en formato JSON para pasarlo al código JavaScript
$categories_json = json_encode($categories);
$data_json = json_encode($data);

// ================================ Inicio para los graficos 2 ================================

// Consulta los datos de la tabla SQL
$sqlGrafics2 = "SELECT fecha_registro, total FROM reservas WHERE status = 'pagado'";
$resultGrafics2 = $conn->query($sqlGrafics2);
// Crea los arrays para almacenar las categorías (fechas) y los datos (totales)
$categories = [];
$data = [];

if ($resultGrafics2->num_rows > 0) {
    while ($row = $resultGrafics2->fetch_assoc()) {
        $categories[] = $row['fecha_registro'];
        $data[] = $row['total'];
    }
}

// EMPLEADOS DATOS
$sqlEmpleados = "SELECT ID, Nombre, Apellido, Email, Telefono, Cargo FROM empleados";
$resultEmpleados = $conn->query($sqlEmpleados);


    // Obtener todos los productos de la base de datos
    $sql = "SELECT * FROM productos";
    $result = $conn->query($sql);
    $productos = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    }

    // Actualizar nombre, precio e imagen del producto
    if (isset($_POST['actualizar_producto'])) {
        $id_producto = $_POST['id_producto'];
        $nuevo_nombre = $_POST['nombre_producto'];
        $nuevo_precio = $_POST['precio_producto'];
        $imagen_producto = $_FILES['imagen_producto']['tmp_name'];

        if ($imagen_producto) {
            $imagen_contenido = addslashes(file_get_contents($imagen_producto));
            $sql = "UPDATE productos SET nombre = '$nuevo_nombre', precio = $nuevo_precio, imagen = '$imagen_contenido' WHERE id = $id_producto";
        } else {
            $sql = "UPDATE productos SET nombre = '$nuevo_nombre', precio = $nuevo_precio WHERE id = $id_producto";
        }

        $conn->query($sql);
    }
    if (isset($_POST['eliminar_producto'])) {
        $id_producto = $_POST['id_producto'];

        $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $stmt->close();

        // Después de eliminar el producto, redireccionar al usuario a la misma página
        header("Location: index.php");
        exit();
    }

    // Eliminar extra
    if (isset($_POST['eliminar_extra'])) {
        $id_extra = $_POST['id_extra'];

        $stmt = $conn->prepare("DELETE FROM extras WHERE id = ?");
        $stmt->bind_param("i", $id_extra);
        $stmt->execute();
        $stmt->close();

        // Después de eliminar el extra, redireccionar al usuario a la misma página
        header("Location: index.php");
        exit();
    }

    // Obtener todos los extras de la base de datos
    $sql = "SELECT * FROM extras";
    $result = $conn->query($sql);
    $extras = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $extras[] = $row;
        }
    }

    // Actualizar nombre, precio e imagen del extra
    if (isset($_POST['actualizar_extra'])) {
        $id_extra = $_POST['id_extra'];
        $nuevo_nombre = $_POST['nombre_extra'];
        $nuevo_precio = $_POST['precio_extra'];
    
        // Verificar si se ha cargado una nueva imagen
        if ($_FILES['imagen_extra']['tmp_name']) {
            $imagen_extra = $_FILES['imagen_extra']['tmp_name'];
            $imagen_tipo = $_FILES['imagen_extra']['type'];
    
            // Verificar si el tipo de archivo es una imagen
            if (strpos($imagen_tipo, 'image') !== false) {
                $imagen_contenido = addslashes(file_get_contents($imagen_extra));
                $sql = "UPDATE extras SET nombre = '$nuevo_nombre', precio = $nuevo_precio, imagen = '$imagen_contenido' WHERE id = $id_extra";
                $conn->query($sql);
            }
        } else {
            // Si no se cargó una nueva imagen, solo actualiza el nombre y precio
            $sql = "UPDATE extras SET nombre = '$nuevo_nombre', precio = $nuevo_precio WHERE id = $id_extra";
            $conn->query($sql);
        }
    }


// Cerrar la conexión a la base de datos
$conn->close();



?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
    <title>Menú de Navegación y Secciones</title>


</head>
<body>
    <div class="container">
        <aside>
            <div class="top">
                <div class="logo">
                    <img src="images/3-lenas-polleria.ico" alt="">
                    <h2>3<span class="danger">LEÑAS</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">close</span>
                </div>
            </div>
            <!–-------------- LINKS DEL DASHBOARD ----------- –>
            <div class="sidebar">
                <a href="#section1">
                    <span class="material-icons-sharp">grid_view</span>
                    <h3>Inicio</h3>
                </a>
                <a href="#section2">
                    <span class="material-icons-sharp">person_outline</span>
                    <h3>Empleados</h3>
                </a>
                <a href="#section3">
                    <span class="material-icons-sharp">receipt_long</span>
                    <h3>Ordenes</h3>
                </a>
                <a href="#section4">
                    <span class="material-icons-sharp">insights</span>
                    <h3>Analytics</h3>
                </a>
                <a href="#section5">
                    <span class="material-icons-sharp">inventory</span>
                    <h3>Productos</h3>
                </a>
                <a href="#section6">
                    <span class="material-icons-sharp">person</span>
                    <h3>Crear Admin</h3>
                </a>
                <a href="#section1" onclick="location.href='descargar_bd.php'">
                    <span class="material-icons-sharp">download</span>
                    <h3>Descargar BD</h3>
                </a>
                <a href="#section6" onclick="location.href='logout.php'">
                    <span class="material-icons-sharp">logout</span>
                    <h3>Cerrar Sesión</h3>
                </a>
            </div>
        </aside>
        <main>
            <div id="section1" class="section active">
                <div class="date">
                    <input type="date" disabled id="fecha_inicio" name="fecha_inicio" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <!–-------------- inicio GRAFICOS ----------- –>

                <div class="insights">
                    <div class="sales">
                        <span class="material-icons-sharp">analytics</span>
                        <div class="middle">
                            <div class="left">
                                <h3>Total Sales</h3>
                                <h1>S/<?php echo $totalPrecios;?></h1>
                            </div>
                        </div>
                        <small class="text-muted">Ingresos Totales</small>
                    </div>

                    <div class="expenses">
                        <span class="material-icons-sharp">bar_chart</span>
                        <div class="middle">
                            <div class="left">
                                <h3>Total diario</h3>
                                <h1>S/ <?php echo $totalVentas24h; ?></h1>
                            </div>
                            
                        </div>
                        <small class="text-muted">Ultimas 24 horas</small>
                    </div>

                    <div class="income">
                        <span class="material-icons-sharp">stacked_line_chart</span>
                        <div class="middle">
                            <div class="left">
                                <h3>Total Semanal</h3>
                                <h1>S/<?php echo $totalVentas7d; ?></h1>
                            </div>
                        </div>
                        <small class="text-muted">Ultimos 7 dias</small>
                    </div>
                    <!–-------------- FIN DE LOS GRAFICOS ----------- –>
                </div>
                <!–-------------- Ordenes ----------- –>
                <div class="recent-orders">
                    <h2>Ordenes Recientes </h2>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php if ($resultPedidos->num_rows > 0) {
                                while ($row = $resultPedidos->fetch_assoc()) {
                                    $pedido = $row["pedido"];
                                    $cantidad = $row["cantidad"];
                                    $total = $row["Total"];
                                    $fecha = $row["fecha_registro"];
                                    $status = $row["status"];
                                    $email = $row["email"];

                                    echo '<tr>';
                                    echo '<td>' . $pedido . '</td>';
                                    echo '<td>' . $cantidad . '</td>';
                                    echo '<td>' . $total . '</td>';
                                    echo '<td>' . $fecha . '</td>';
                                    echo '<td class="' . ($status == 'pagado' ? 'success' : 'warning') . '">' . $status . '</td>';
                                    echo '<td>' . $email . '</td>';

                                    echo '</tr>';
                                }
                            } ?></td>

                        </tbody>
                    </table>
                </div>
            </div>
            <div id="section2" class="section">
                <center><h1 class="vnts5">Empleados</h1></center>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Cargo</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ($resultEmpleados->num_rows > 0) {
                            while ($row = $resultEmpleados->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["ID"] . "</td>";
                                echo "<td>" . $row["Nombre"] . "</td>";
                                echo "<td>" . $row["Apellido"] . "</td>";
                                echo "<td>" . $row["Email"] . "</td>";
                                echo "<td>" . $row["Telefono"] . "</td>";
                                echo "<td>" . $row["Cargo"] . "</td>";
                                echo "<td><a style='color:var(--color-primario)' href='editar.php?id=" . $row["ID"] . "'>Editar</a></td>";
                                echo "<td><a class='danger' href='eliminar.php?id=" . $row["ID"] . "'>Eliminar</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No se encontraron empleados</td></tr>";
                        }
                        ?>

                    </tbody>
                </table>
                <center><a href="crear.php" class="btn2">Nuevo Empleado</a></center>
            
            </div>
            <div id="section3" class="section">
                <div class="recent-orders">
                    <h2>Ordenes Recientes</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                <?php if ($result2->num_rows > 0) {
                                    while ($row = $result2->fetch_assoc()) {
                                        $pedido = $row["pedido"];
                                        $cantidad = $row["cantidad"];
                                        $total = $row["Total"];
                                        $fecha = $row["fecha_registro"];
                                        $status = $row["status"];
                                        $email = $row["email"];

                                        echo '<tr>';
                                        echo '<td>' . $pedido . '</td>';
                                        echo '<td>' . $cantidad . '</td>';
                                        echo '<td>' . $total . '</td>';
                                        echo '<td>' . $fecha . '</td>';
                                        echo '<td class="' . ($status == 'pagado' ? 'success' : 'warning') . '">' . $status . '</td>';
                                        echo '<td>' . $email . '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="6">No se encontraron ventas en el rango de fechas seleccionado.</td></tr>';
                                } ?></td>
    <!-- Formulario para ingresar las fechas -->
                        <div id="formulario">
                            <form method="POST" action="">
                                <label for="fecha_inicio">Fecha de inicio:</label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="fechainc">

                                <label for="fecha_fin">Fecha de fin:</label>
                                <input type="date" id="fecha_fin" name="fecha_fin" class="fechafin">

                                <button type="submit" class="btonForm" >Filtrar</button>
                            </form>
                        </div>
                        <p>Mostrando las ordenes : <?php echo $fechaInicio; ?>;<br> Hasta : <?php echo $fechaFin; ?></p>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="section4" class="section">
            <center><h1 class="vnts5">TOP 5 VENTAS</h1></center>  
            <div id="chart">
            </div>
            <center><h1 class="vnts5">TOP PRODUCTOS</h1></center>  

            <div id="chart2"></div>

            </div>
            <div id="section5" class="section">                 
                    <h2>Productos</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Imagen</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $producto) { ?>
                                <tr>
                                    <td><?php echo $producto['id']; ?></td>
                                    <td><?php echo $producto['nombre']; ?></td>
                                    <td><?php echo $producto['precio']; ?></td>
                                    <td>
                                        <?php if ($producto['imagen']) { ?>
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($producto['imagen']); ?>" alt="Imagen del Producto" width="100">
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <form action="" method="post" enctype="multipart/form-data" class="form-inline">
                                            <input type="hidden" name="id_producto" value="<?php echo $producto['id']; ?>">
                                            <input type="text" name="nombre_producto" value="<?php echo $producto['nombre']; ?>">
                                            <input type="number" id="Prec" step="0.01" name="precio_producto" value="<?php echo $producto['precio']; ?>">
                                            <label for="file-upload" class="custom-file-upload">Seleccionar archivo</label>
                                            <input type="file" name="imagen_producto" id="file-upload" class="file-input">
                                            <input type="submit" name="actualizar_producto" value="Actualizar Producto">
                                            <input type="submit" class="danger" name="eliminar_producto" value="Eliminar Producto">
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <h2 class="section-title">Extras</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Imagen</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($extras as $extra) { ?>
                                <tr>
                                    <td><?php echo $extra['id']; ?></td>
                                    <td><?php echo $extra['nombre']; ?></td>
                                    <td><?php echo $extra['precio']; ?></td>
                                    <td>
                                        <?php if ($extra['imagen']) { ?>
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($extra['imagen']); ?>" alt="Imagen del Extra" width="100">
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <form action="" method="post" enctype="multipart/form-data" class="form-inline">
                                            <input type="hidden" name="id_extra" value="<?php echo $extra['id']; ?>">
                                            <input type="text" name="nombre_extra" value="<?php echo $extra['nombre']; ?>">
                                            <input type="number" step="0.01" name="precio_extra" value="<?php echo $extra['precio']; ?>">
                                            <label for="file-upload2" class="custom-file-upload">Seleccionar archivo</label>

                                            <input type="file" name="imagen_extra" id="file-upload2" class="file-input">
                                            <input type="submit" name="actualizar_extra" value="Actualizar Extra">
                                            <input type="submit" class="danger" name="eliminar_extra" value="Eliminar Extra">
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
            </div>
            <div id="section6" class="section">
            <h2>Registro de Administrador</h2>
            <div id="form-container">
                <form method="POST" action="registro_administrador.php">
                <label id="usuario-label" for="usuario">Usuario:</label>
                <input id="usuario-input" type="text" name="usuario" required>
                
                <label id="contrasena-label" for="contrasena">Contraseña:</label>
                <input id="contrasena-input" type="password" name="contrasena" required>
                
                <input id="submit-btn" type="submit" value="Registrar">
                </form>
            </div>
            </div>
        </main>


        <!–-------------- FIN DEL MAIN ----------- –>
        <div class="right">
            <div class="top">
                <button id="menu-btn">
                    <span class="material-icons-sharp">menu</span>
                </button>
                <div class="theme-toggler">
                    <span class="material-icons-sharp active">light_mode</span>
                    <span class="material-icons-sharp">dark_mode</span>
                </div>
                <div class="profile">
                    <div class="info">
                        <p>Hey ,<b>Andy</b></p>
                        <small class="text-muted">Admin</small>
                    </div>
                    <div class="profile-photo">
                        <img src="images/profile-1.jpg" alt="">
                    </div>
                </div>
            </div>
        <!–-------------- FIN DEL TOP ----------- –>

        <!–-------------- INICIO DE UPDATES ----------- –>
            <div class="recent-updates">
                <h2>Actualizaciones recientes</h2>
                <div class="updates">
                    <div class="update">
                        <div class="profile-photo">
                            <img src="images/profile-2.jpg" alt="">
                        </div>
                        <div class="message">
                            <p><b>Saul Su </b> Recibiendo la orden de la noche 
                            pasada</p>
                            <small class="text-muted">Hace dos minutos</small>
                        </div>
                    </div>
                    <div class="update">
                        <div class="profile-photo">
                            <img src="images/profile-3.jpg" alt="">
                        </div>
                        <div class="message">
                            <p><b>Saul Su </b> Recibiendo la orden de la noche 
                            pasada</p>
                            <small class="text-muted">Hace dos minutos</small>
                        </div>
                    </div>
                    <div class="update">
                        <div class="profile-photo">
                            <img src="images/profile-4.jpg" alt="">
                        </div>
                        <div class="message">
                            <p><b>Saul Su </b >Recibiendo la orden de la noche 
                            pasada</p>
                            <small class="text-muted">Hace dos minutos</small>
                        </div>
                    </div>
                </div>
            </div>
        <!–-------------- FIN DE UPDATES ----------- –>

        <!–-------------- INICIO DE Analytics ----------- –>
            <div class="sales-analytics">
                <h2 class="aventas" >Analisis de ventas</h2>
                <div class="item online">
                    <div class="icon">
                        <span class="material-icons-sharp">real_estate_agent</span>
                    </div>
                    <div class="right">
                        <div class="info">
                            <h3>TOTAL VENTAS</h3>
                            <small class="text-muted">Total de ventas</small>
                        </div>
                        <h5 class="success">+39%</h5>
                        <h3>S/ <?php echo $totalPrecios;?></h3>
                    </div>
                </div>
                <div class="item offline">
                    <div class="icon">
                        <span class="material-icons-sharp">attach_money</span>
                    </div>
                    <div class="right">
                        <div class="info">
                            <h3>TOTAL VENTAS</h3>
                            <small class="text-muted">Ultimas 24 horas</small>
                        </div>
                        <h5 class="success">+19%</h5>
                        <h3>S/ <?php echo $totalVentas24h; ?></h3>
                    </div>
                </div>
                <div class="item customer">
                    <div class="icon">
                        <span class="material-icons-sharp">account_balance</span>
                    </div>
                    <div class="right">
                        <div class="info">
                            <h3>TOTAL VENTAS</h3>
                            <small class="text-muted">Ultimos 7 dias</small>
                        </div>
                        <h5 class="success">+24%</h5>
                        <h3>S/ <?php echo $totalVentas7d; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        </div>


    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="script.js"></script>
    <!–-------------- grafico 1----------- –>
    <script>
        var options = {
            chart: {
                type: 'line',
                background: 'var(--color-background)',
                foreColor: 'var(--color-blanco)',
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Ganancias Diarias S/',
                data: <?php echo json_encode($data);?>,
                style: {
                    colors: ['var(--color-blanco)']
                }
            }],
            xaxis: {
                categories: <?php echo json_encode($categories); ?>
            },
            colors: ['var(--color-primario)'],
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            markers: {
                size: 4,
                colors: ['var(--color-primario)'],
                strokeColors: 'var(--color-blanco)',
                strokeWidth: 2,
                hover: {
                    size: 7
                }
            },
            tooltip: {
                theme: 'dark' // Establece el tema de la herramienta de información en modo oscuro
            },
            grid: {
                borderColor: 'var(--color-info-light)'
            },
            yaxis: {
                axisBorder: {
                    show: true,
                    color: 'var(--color-info-dark)'
                },
                labels: {
                    style: {
                        colors: 'var(--color-negro)'
                    }
                }
            },
            xaxis: {
                axisBorder: {
                    show: true,
                    color: 'var(--color-negro)'
                },
                labels: {
                    style: {
                        colors: 'var(--color-negro)'
                    }
                }
            }
        };

        var chart2 = new ApexCharts(document.querySelector("#chart2"), options);

        chart2.render();
    </script>
    <!–-------------- grafico 2 ----------- –>
    <script>
        var options = {
            chart: {
                type: 'bar',
                toolbar: {
                    show: false
                },
                background: 'var(--color-background)',
                foreColor: 'var(--color-blanco)'
            },
            series: [{
                name: 'Ventas',
                data: <?php echo $data_json; ?>,
                style: {
                    colors: ['var(--color-blanco)']
                }
            }],
            xaxis: {
                categories: <?php echo $categories_json; ?>
            },
            colors: ['var(--color-primario)'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['var(--color-info-light)']
            },
            grid: {
                borderColor: 'var(--color-info-light)'
            },
            yaxis: {
                axisBorder: {
                    show: true,
                    color: 'var(--color-info-dark)'
                },
                labels: {
                    style: {
                        colors: 'var(--color-negro)'
                    }
                }
            },
            xaxis: {
                axisBorder: {
                    show: true,
                    color: 'var(--color-negro)'
                },
                labels: {
                    style: {
                        colors: 'var(--color-negro)'
                    }
                }
            },
            tooltip: {
                theme: 'dark' // Establece el tema de la herramienta de información en modo oscuro
            },
            states: {
                hover: {
                    filter: {
                        type: 'darken',
                        value: 0.6
                    }
                }
            },
            fill: {
                colors: ['var(--color-primario)'],
                type: 'solid',
                opacity: 1,
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#f47a42'],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 50, 100]
                }
            },
            legend: {
                labels: {
                    colors: 'var(--color-negro)'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);

        chart.render();

</script>
</body>
</html>
