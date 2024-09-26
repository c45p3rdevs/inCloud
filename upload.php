<?php
// upload.php - Script para subir archivos

session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    die("No has iniciado sesión.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $file_name = $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $file_type = $_FILES['file']['type'];
    $upload_date = date('Y-m-d H:i:s');

    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $target_file = $upload_dir . basename($file_name);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO files (user_id, file_name, file_size, file_type, upload_date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isiss', $user_id, $file_name, $file_size, $file_type, $upload_date);

        if ($stmt->execute()) {
            echo "Archivo subido exitosamente.";
        } else {
            echo "Error al subir el archivo.";
        }
    } else {
        echo "Error al mover el archivo.";
    }
    $stmt->close();
    $conn->close();

    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Validar el tipo de archivo
$allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'doc', 'docx', 'xls'];
       if (!in_array($fileType, $allowedTypes)) {
    echo "<div class='alert alert-danger'>Tipo de archivo no permitido. Solo se permiten JPG, PNG y PDF.</div>";
    exit;
}




}
?>
