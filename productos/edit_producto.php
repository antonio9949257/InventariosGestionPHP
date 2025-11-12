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


$categorias_res = $con->query("SELECT id, nombre FROM categorias");
$proveedores_res = $con->query("SELECT id, nombre FROM proveedores");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $stock = $_POST['stock'];
    $stock_minimo = $_POST['stock_minimo']; 
    $id_categoria = $_POST['id_categoria'];
    $id_proveedor = $_POST['id_proveedor'];

    $stmt = $con->prepare("UPDATE productos SET nombre=?, descripcion=?, precio_compra=?, precio_venta=?, stock=?, stock_minimo=?, id_categoria=?, id_proveedor=? WHERE id=?");
    $stmt->bind_param("ssddiiii", $nombre, $descripcion, $precio_compra, $precio_venta, $stock, $stock_minimo, $id_categoria, $id_proveedor, $id);

    if ($stmt->execute()) {
        header("Location: index.php?status=updated");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
} else {
    $stmt = $con->prepare("SELECT id, nombre, descripcion, precio_compra, precio_venta, stock, stock_minimo, id_categoria, id_proveedor FROM productos WHERE id=?");
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
  <title>Editar Producto</title>
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
            <h2 class="card-title mb-0"><i class="fas fa-edit"></i> Editar Producto</h2>
          </div>
          <div class="card-body">
            <?php echo $message; ?>
            <form action="edit_producto.php?id=<?php echo $id; ?>" method="post">
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto['id']); ?>">
              <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
              </div>
              <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
              </div>
              <div class="mb-3">
                <label for="precio_compra" class="form-label">Precio de Compra</label>
                <input type="number" step="0.01" class="form-control" id="precio_compra" name="precio_compra" value="<?php echo htmlspecialchars($producto['precio_compra']); ?>" required>
              </div>
              <div class="mb-3">
                <label for="precio_venta" class="form-label">Precio de Venta</label>
                <input type="number" step="0.01" class="form-control" id="precio_venta" name="precio_venta" value="<?php echo htmlspecialchars($producto['precio_venta']); ?>" required>
              </div>
              <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo htmlspecialchars($producto['stock']); ?>" required>
              </div>
              <div class="mb-3">
                <label for="stock_minimo" class="form-label">Stock Mínimo</label>
                <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" value="<?php echo htmlspecialchars($producto['stock_minimo']); ?>" required>
              </div>
              <div class="mb-3">
                <label for="id_categoria" class="form-label">Categoría</label>
                <select class="form-control" id="id_categoria" name="id_categoria" required>
                  <option value="">Seleccione una categoría</option>
                  <?php
                  if ($categorias_res->num_rows > 0) {
                      mysqli_data_seek($categorias_res, 0);
                      while($categoria = $categorias_res->fetch_assoc()) {
                          $selected = ($categoria['id'] == $producto['id_categoria']) ? 'selected' : '';
                          echo "<option value='" . $categoria['id'] . "' $selected>" . htmlspecialchars($categoria['nombre']) . "</option>";
                      }
                  }
                  ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="id_proveedor" class="form-label">Proveedor</label>
                <select class="form-control" id="id_proveedor" name="id_proveedor" required>
                  <option value="">Seleccione un proveedor</option>
                  <?php
                  if ($proveedores_res->num_rows > 0) {
                      mysqli_data_seek($proveedores_res, 0);
                      while($proveedor = $proveedores_res->fetch_assoc()) {
                          $selected = ($proveedor['id'] == $producto['id_proveedor']) ? 'selected' : '';
                          echo "<option value='" . $proveedor['id'] . "' $selected>" . htmlspecialchars($proveedor['nombre']) . "</option>";
                      }
                  }
                  ?>
                </select>
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Actualizar Producto</button>
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