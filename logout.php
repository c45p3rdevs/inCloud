<?php
// logout.php - Script para cerrar sesión

session_start();
session_destroy();
header('Location: index.html');
exit;
?>
