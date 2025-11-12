<?php
session_start();


if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../index.php"); 
    exit();
}

include 'db.php';
$message = '';
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $stmt = $con->prepare("DELETE FROM usuarios WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php?status=deleted");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
} else {
    $stmt = $con->prepare("SELECT id, usuario FROM usuarios WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();

    if (!$user_data) {
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eliminar Usuario</title>
  <link href="../adi_bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https:
</head>
<body class="bg-dark text-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="../Home.php">Sistema de Inventario</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <a class="nav-link" href="../Home.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../categorias/index.php">Categorías</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../proveedores/index.php">Proveedores</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../productos/index.php">Productos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../movimientos/index.php">Movimientos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Usuarios</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../logout.php">Cerrar Sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
  
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card bg-secondary text-light">
          <div class="card-header">
            <h2 class="card-title mb-0"><i class="fas fa-user-times"></i> Eliminar Usuario</h2>
          </div>
          <div class="card-body">
            <?php echo $message; ?>
            <p>¿Estás seguro de que quieres eliminar al usuario <strong><?php echo htmlspecialchars($user_data['usuario']); ?></strong>?</p>
            <form action="delete_usuario.php?id=<?php echo $id; ?>" method="post">
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($user_data['id']); ?>">
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-danger">Eliminar Usuario</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../adi_bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>