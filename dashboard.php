<?php
// dashboard.php - Página del repositorio de archivos

session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}


if (isset($_GET['message'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_GET['message']) . "</div>";
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM files WHERE user_id = ? AND file_name LIKE ?";
$stmt = $conn->prepare($sql);
$searchParam = "%" . $search . "%";
$stmt->bind_param('is', $user_id, $searchParam);



$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name']; // Obtener el nombre del usuario de la sesión

$sql = "SELECT * FROM files WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-5">inCloud Archivos</h2>
        <p class="text-center">Bienvenido, <strong><?php echo htmlspecialchars($user_name); ?></strong></p>
        <!-- Mostrar el nombre del usuario -->
        <div class="row">
            <div class="col-md-12">
                <!-- Formulario para subir archivos -->
                <form action="upload.php" method="POST" enctype="multipart/form-data" class="mt-3">
                    <div class="form-group">
                        <label for="file">Subir nuevo archivo:</label>
                        <input type="file" class="form-control-file" name="file" required>
                    </div>
                    <button type="submit" class="btn btn-success">Subir</button>
                </form>
                <hr>
                <div class="form-group">
                    <form action="" method="GET">
                        <input type="text" name="search" placeholder="Buscar archivo..." class="form-control"
                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit" class="btn btn-secondary mt-2">Buscar</button>
                    </form>
                </div>


                <!-- Tabla para mostrar archivos subidos -->
                <h4>Archivos Subidos:</h4>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nombre del Archivo</th>
                                <th>Tamaño</th>
                                <th>Tipo</th>
                                <th>Fecha de Subida</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($file = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $file['file_name']; ?></td>
                                <td><?php echo number_format($file['file_size'] / 1024, 2); ?> KB</td>
                                <td><?php echo $file['file_type']; ?></td>
                                <td><?php echo $file['upload_date']; ?></td>
                                <td>


                                    <!-- Botones de acción para cada archivo -->
                                    <a href="view.php?file_id=<?php echo $file['id']; ?>"
                                        class="btn btn-info btn-sm">Ver</a>
                                    <form action="update.php" method="POST" enctype="multipart/form-data"
                                        style="display:inline-block;">
                                        <input type="file" class="form-control-file" name="file" required>
                                        <input type="hidden" name="file_id" value="<?php echo $file['id']; ?>">
                                        <button type="submit" class="btn btn-warning btn-sm">Actualizar</button>
                                    </form>
                                    <form action="delete.php" method="POST" style="display:inline-block;">
                                        <input type="hidden" name="file_id" value="<?php echo $file['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="logout.php" class="btn btn-secondary mt-4">Cerrar Sesión</a>
    </div>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>