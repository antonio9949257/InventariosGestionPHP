<?php
session_start();


if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'gerente' && $_SESSION['rol'] !== 'empleado')) {
    header("Location: ../index.html"); 
    exit();
}

include 'db.php';
$message = '';


$productos_res = $con->query("SELECT id, nombre FROM productos");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST['id_producto'];
    $tipo_movimiento = $_POST['tipo_movimiento'];
    $cantidad = $_POST['cantidad'];
    $observaciones = $_POST['observaciones'];
    $id_usuario = $_SESSION['id_usuario']; 

    
    $con->begin_transaction();

    try {
        
        $stmt = $con->prepare("INSERT INTO movimientos (id_producto, tipo_movimiento, cantidad, observaciones, id_usuario) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isisi", $id_producto, $tipo_movimiento, $cantidad, $observaciones, $id_usuario);
        $stmt->execute();
        $stmt->close();

        
        if ($tipo_movimiento == 'entrada') {
            $stmt_update = $con->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
        } else { 
            $stmt_update = $con->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
        }
        $stmt_update->bind_param("ii", $cantidad, $id_producto);
        $stmt_update->execute();
        $stmt_update->close();

        $con->commit();
        header("Location: index.php?status=success");
        exit();
    } catch (mysqli_sql_exception $exception) {
        $con->rollback();
        $message = "<div class='alert alert-danger'>Error: " . $exception->getMessage() . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Movimiento</title>
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
            <h2 class="card-title mb-0"><i class="fas fa-plus-circle"></i> Registrar Nuevo Movimiento</h2>
          </div>
          <div class="card-body">
            <?php echo $message; ?>
            <form action="create_movimiento.php" method="post">
              <div class="mb-3">
                <label for="id_producto" class="form-label">Producto</label>
                <select class="form-control" id="id_producto" name="id_producto" required>
                  <option value="">Seleccione un producto</option>
                  <?php
                  if ($productos_res->num_rows > 0) {
                      while($producto = $productos_res->fetch_assoc()) {
                          echo "<option value='" . $producto['id'] . "'>" . htmlspecialchars($producto['nombre']) . "</option>";
                      }
                  }
                  ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="tipo_movimiento" class="form-label">Tipo de Movimiento</label>
                <select class="form-control" id="tipo_movimiento" name="tipo_movimiento" required>
                  <option value="">Seleccione tipo</option>
                  <option value="entrada">Entrada</option>
                  <option value="salida">Salida</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="cantidad" name="cantidad" required min="1">
              </div>
              <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Registrar Movimiento</button>
                <a href="index.php" class="btn btn-secondary">Volver a la lista</a>
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