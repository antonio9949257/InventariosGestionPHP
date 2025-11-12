<?php
session_start();


if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../index.php"); 
    exit();
}

include 'db.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];
    $rol = $_POST['rol'];

    
    $hashed_password = password_hash($clave, PASSWORD_DEFAULT);

    $stmt = $con->prepare("INSERT INTO usuarios (usuario, clave, rol) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $usuario, $hashed_password, $rol);

    if ($stmt->execute()) {
        header("Location: index.php?status=success");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Usuario</title>
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
            <h2 class="card-title mb-0"><i class="fas fa-user-plus"></i> Registrar Nuevo Usuario</h2>
          </div>
          <div class="card-body">
            <?php echo $message; ?>
            <form action="create_usuario.php" method="post">
              <div class="mb-3">
                <label for="usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" required>
              </div>
              <div class="mb-3">
                <label for="clave" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="clave" name="clave" required>
              </div>
              <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-control" id="rol" name="rol" required>
                  <option value="empleado">Empleado</option>
                  <option value="gerente">Gerente</option>
                </select>
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Registrar Usuario</button>
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