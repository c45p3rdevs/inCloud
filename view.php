<?php
// view.php - Script para visualizar archivos

session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    die("No has iniciado sesión.");
}

if (isset($_GET['file_id'])) {
    $file_id = $_GET['file_id'];
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM files WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $file_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $file = $result->fetch_assoc();
        $file_path = 'uploads/' . $file['file_name'];

        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $file['file_type']);
            header('Content-Disposition: inline; filename="' . basename($file_path) . '"');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        } else {
            echo "El archivo no existe.";
        }
    } else {
        echo "No tienes permiso para ver este archivo.";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Archivo no especificado.";
}
?>
