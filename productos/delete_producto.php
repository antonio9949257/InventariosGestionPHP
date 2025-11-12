<?php
session_start();


if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../index.html"); 
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
    $stmt = $con->prepare("DELETE FROM productos WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php?status=deleted");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
} else {
    $stmt = $con->prepare("SELECT id, nombre FROM productos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();
    $stmt->close();

    if (!$producto) {
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
  <title>Eliminar Producto</title>
  <link href="../adi_bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https:
</head>
<body class="bg-dark text-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="
    </div>
  </nav>
  
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card bg-secondary text-light">
          <div class="card-header">
            <h2 class="card-title mb-0"><i class="fas fa-trash"></i> Eliminar Producto</h2>
          </div>
          <div class="card-body">
            <?php echo $message; ?>
            <p>¿Estás seguro de que quieres eliminar el producto <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>?</p>
            <form action="delete_producto.php?id=<?php echo $id; ?>" method="post">
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto['id']); ?>">
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-danger">Eliminar Producto</button>
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