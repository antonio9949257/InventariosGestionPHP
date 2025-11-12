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
    $nombre = $_POST['nombre'];
    $contacto = $_POST['contacto'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];

    $stmt = $con->prepare("UPDATE proveedores SET nombre=?, contacto=?, telefono=?, email=?, direccion=? WHERE id=?");
    $stmt->bind_param("sssssi", $nombre, $contacto, $telefono, $email, $direccion, $id);

    if ($stmt->execute()) {
        header("Location: index.php?status=updated");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
} else {
    $stmt = $con->prepare("SELECT id, nombre, contacto, telefono, email, direccion FROM proveedores WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $proveedor = $result->fetch_assoc();
    $stmt->close();

    if (!$proveedor) {
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
  <title>Editar Proveedor</title>
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
            <h2 class="card-title mb-0"><i class="fas fa-edit"></i> Editar Proveedor</h2>
          </div>
          <div class="card-body">
            <?php echo $message; ?>
            <form action="edit_proveedor.php?id=<?php echo $id; ?>" method="post">
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($proveedor['id']); ?>">
              <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Proveedor</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($proveedor['nombre']); ?>" required>
              </div>
              <div class="mb-3">
                <label for="contacto" class="form-label">Persona de Contacto</label>
                <input type="text" class="form-control" id="contacto" name="contacto" value="<?php echo htmlspecialchars($proveedor['contacto']); ?>">
              </div>
              <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($proveedor['telefono']); ?>">
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($proveedor['email']); ?>">
              </div>
              <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <textarea class="form-control" id="direccion" name="direccion" rows="3"><?php echo htmlspecialchars($proveedor['direccion']); ?></textarea>
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Actualizar Proveedor</button>
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