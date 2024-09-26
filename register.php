<?php
// register.php - Script para registrar usuarios

include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Comprobar si el correo ya está registrado
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "El correo electrónico ya está registrado.";
    } else {
        // Insertar nuevo usuario en la base de datos
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $name, $email, $password);

        if ($stmt->execute()) {
            header('Location: index.html');
        } else {
            echo "Error al registrar el usuario.";
        }
    }

// register.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... Validaciones previas

    // Asignar rol por defecto
    $role = 'user';
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
    // ... Resto del código
}





    $stmt->close();
    $conn->close();
}
?>