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


$productos_res = $con->query("SELECT id, nombre FROM productos");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $observaciones = $_POST['observaciones'];
    $fecha_movimiento = $_POST['fecha_movimiento'];

    $stmt = $con->prepare("UPDATE movimientos SET observaciones=?, fecha_movimiento=? WHERE id=?");
    $stmt->bind_param("ssi", $observaciones, $fecha_movimiento, $id);

    if ($stmt->execute()) {
        header("Location: index.php?status=updated");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
} else {
    $stmt = $con->prepare("SELECT id, id_producto, tipo_movimiento, cantidad, fecha_movimiento, observaciones, id_usuario FROM movimientos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $movimiento = $result->fetch_assoc();
    $stmt->close();

    if (!$movimiento) {
        header("Location: index.php");
        exit();
    }

    
    $product_stmt = $con->prepare("SELECT nombre FROM productos WHERE id=?");
    $product_stmt->bind_param("i", $movimiento['id_producto']);
    $product_stmt->execute();
    $product_result = $product_stmt->get_result();
    $product_name = $product_result->fetch_assoc()['nombre'];
    $product_stmt->close();

    
    $user_stmt = $con->prepare("SELECT usuario FROM usuarios WHERE id=?");
    $user_stmt->bind_param("i", $movimiento['id_usuario']);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user_name = $user_result->fetch_assoc()['usuario'];
    $user_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Movimiento</title>
  <link href="../adi_bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https:
  <style>
    .btn-primary {
        background-color: 
        border-color: 
    }
    .btn-primary:hover {
        background-color: 
        border-color: 
    }
  </style>
</head>
<body class="bg-dark text-light">

  
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card bg-secondary text-light">
          <div class="card-header">
            <h2 class="card-title mb-0"><i class="fas fa-edit"></i> Editar Movimiento</h2>
          </div>
          <div class="card-body">
            <?php echo $message; ?>
            <form action="edit_movimiento.php?id=<?php echo $id; ?>" method="post">
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($movimiento['id']); ?>">
              <div class="mb-3">
                <label for="producto_nombre" class="form-label">Producto</label>
                <input type="text" class="form-control" id="producto_nombre" value="<?php echo htmlspecialchars($product_name); ?>" disabled>
              </div>
              <div class="mb-3">
                <label for="tipo_movimiento" class="form-label">Tipo de Movimiento</label>
                <input type="text" class="form-control" id="tipo_movimiento" value="<?php echo htmlspecialchars($movimiento['tipo_movimiento']); ?>" disabled>
              </div>
              <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="cantidad" value="<?php echo htmlspecialchars($movimiento['cantidad']); ?>" disabled>
              </div>
              <div class="mb-3">
                <label for="fecha_movimiento" class="form-label">Fecha de Movimiento</label>
                <input type="datetime-local" class="form-control" id="fecha_movimiento" name="fecha_movimiento" value="<?php echo date('Y-m-d\TH:i', strtotime(htmlspecialchars($movimiento['fecha_movimiento']))); ?>" required>
              </div>
              <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?php echo htmlspecialchars($movimiento['observaciones']); ?></textarea>
              </div>
              <div class="mb-3">
                <label for="usuario_nombre" class="form-label">Registrado por</label>
                <input type="text" class="form-control" id="usuario_nombre" value="<?php echo htmlspecialchars($user_name); ?>" disabled>
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Actualizar Movimiento</button>
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