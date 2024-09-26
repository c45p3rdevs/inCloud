<?php
// delete.php - Script para eliminar archivos

session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    die("No has iniciado sesión.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $file_id = $_POST['file_id'];
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
            unlink($file_path);
        }

        $sql = "DELETE FROM files WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $file_id);
        if ($stmt->execute()) {
            echo "Archivo eliminado.";
        } else {
            echo "Error al eliminar el archivo.";
        }
    } else {
        echo "No tienes permiso para eliminar este archivo.";
    }
    $stmt->close();
    $conn->close();
}
?>
