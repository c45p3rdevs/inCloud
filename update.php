<?php
// update.php - Script para actualizar archivos

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
        $old_file_path = 'uploads/' . $file['file_name'];

        if (file_exists($old_file_path)) {
            unlink($old_file_path);
        }

        $new_file_name = $_FILES['file']['name'];
        $new_file_size = $_FILES['file']['size'];
        $new_file_type = $_FILES['file']['type'];
        $upload_date = date('Y-m-d H:i:s');

        $upload_dir = 'uploads/';
        $target_file = $upload_dir . basename($new_file_name);

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $sql = "UPDATE files SET file_name = ?, file_size = ?, file_type = ?, upload_date = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sissi', $new_file_name, $new_file_size, $new_file_type, $upload_date, $file_id);

            if ($stmt->execute()) {
                echo "Archivo actualizado exitosamente.";
            } else {
                echo "Error al actualizar el archivo.";
            }
        } else {
            echo "Error al mover el archivo.";
        }
    } else {
        echo "No tienes permiso para actualizar este archivo.";
    }
    $stmt->close();
    $conn->close();
}
?>
