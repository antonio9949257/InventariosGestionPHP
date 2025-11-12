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
    $usuario = $_POST['usuario'];
    $rol = $_POST['rol'];
    $clave = $_POST['clave']; 

    if (!empty($clave)) {
        
        $hashed_password = password_hash($clave, PASSWORD_DEFAULT);
        $stmt = $con->prepare("UPDATE usuarios SET usuario=?, clave=?, rol=? WHERE id=?");
        $stmt->bind_param("sssi", $usuario, $hashed_password, $rol, $id);
    } else {
        
        $stmt = $con->prepare("UPDATE usuarios SET usuario=?, rol=? WHERE id=?");
        $stmt->bind_param("ssi", $usuario, $rol, $id);
    }

    if ($stmt->execute()) {
        header("Location: index.php?status=updated");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
} else {
    $stmt = $con->prepare("SELECT id, usuario, rol FROM usuarios WHERE id=?");
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
  <title>Editar Usuario</title>
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
            <h2 class="card-title mb-0"><i class="fas fa-user-edit"></i> Editar Usuario</h2>
          </div>
          <div class="card-body">
            <?php echo $message; ?>
            <form action="edit_usuario.php?id=<?php echo $id; ?>" method="post">
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($user_data['id']); ?>">
              <div class="mb-3">
                <label for="usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo htmlspecialchars($user_data['usuario']); ?>" required>
              </div>
              <div class="mb-3">
                <label for="clave" class="form-label">Nueva Contraseña (dejar en blanco para no cambiar)</label>
                <input type="password" class="form-control" id="clave" name="clave">
              </div>
              <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-control" id="rol" name="rol" required>
                  <option value="empleado" <?php echo ($user_data['rol'] == 'empleado') ? 'selected' : ''; ?>>Empleado</option>
                  <option value="gerente" <?php echo ($user_data['rol'] == 'gerente') ? 'selected' : ''; ?>>Gerente</option>
                </select>
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
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