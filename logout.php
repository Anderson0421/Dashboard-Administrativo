<?php
session_start();

// Cerrar sesión
session_unset();
session_destroy();

// Redirigir al login
header('Location: ../PaginaAdmi/Login/login.php');
exit();
?>
