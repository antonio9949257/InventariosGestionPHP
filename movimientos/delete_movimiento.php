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

    
    $con->begin_transaction();

    try {
        
        $stmt_get = $con->prepare("SELECT id_producto, tipo_movimiento, cantidad FROM movimientos WHERE id=?");
        $stmt_get->bind_param("i", $id);
        $stmt_get->execute();
        $result_get = $stmt_get->get_result();
        $movimiento_to_delete = $result_get->fetch_assoc();
        $stmt_get->close();

        if ($movimiento_to_delete) {
            $id_producto = $movimiento_to_delete['id_producto'];
            $tipo_movimiento = $movimiento_to_delete['tipo_movimiento'];
            $cantidad = $movimiento_to_delete['cantidad'];

            
            if ($tipo_movimiento == 'entrada') {
                
                $stmt_update = $con->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
            } else { 
                
                $stmt_update = $con->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
            }
            $stmt_update->bind_param("ii", $cantidad, $id_producto);
            $stmt_update->execute();
            $stmt_update->close();

            
            $stmt_delete = $con->prepare("DELETE FROM movimientos WHERE id=?");
            $stmt_delete->bind_param("i", $id);
            $stmt_delete->execute();
            $stmt_delete->close();

            $con->commit();
            header("Location: index.php?status=deleted");
            exit();
        } else {
            throw new Exception("Movimiento no encontrado.");
        }
    } catch (Exception $e) {
        $con->rollback();
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
} else {
    $stmt = $con->prepare("SELECT m.id, p.nombre AS producto_nombre, m.tipo_movimiento, m.cantidad, m.fecha_movimiento FROM movimientos m JOIN productos p ON m.id_producto = p.id WHERE m.id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $movimiento = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eliminar Movimiento</title>
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
            <h2 class="card-title mb-0"><i class="fas fa-trash"></i> Eliminar Movimiento</h2>
          </div>
          <div class="card-body">
            <?php echo $message; ?>
            <?php if ($movimiento): ?>
            <p>¿Estás seguro de que quieres eliminar el movimiento de <strong><?php echo htmlspecialchars($movimiento['cantidad']); ?></strong> unidades de <strong><?php echo htmlspecialchars($movimiento['producto_nombre']); ?></strong> (Tipo: <?php echo htmlspecialchars($movimiento['tipo_movimiento']); ?>)?</p>
            <form action="delete_movimiento.php?id=<?php echo $id; ?>" method="post">
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($movimiento['id']); ?>">
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-danger">Eliminar Movimiento</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
              </div>
            </form>
            <?php else: ?>
                <p class="text-danger">Movimiento no encontrado.</p>
                <a href="index.php" class="btn btn-secondary">Volver a la lista</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../adi_bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>