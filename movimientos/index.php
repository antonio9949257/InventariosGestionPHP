<?php
session_start();


if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    header("Location: ../index.html");
    exit();
}

$rolUsu = htmlspecialchars($_SESSION['rol']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Movimientos</title>
  <link href="../adi_bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https:
  <style>
    .carousel-item img {
        height: 30vh; 
        object-fit: cover;
        filter: brightness(0.6);
    }
    .btn-primary {
        background-color: 
        border-color: 
    }
    .btn-primary:hover {
        background-color: 
        border-color: 
    }
    .btn-warning {
        background-color: 
        border-color: 
        color: 
    }
    .btn-warning:hover {
        background-color: 
        border-color: 
        color: 
    }
  </style>
</head>
<body class="bg-dark text-light">
  <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="../img/logo.svg" class="d-block w-100" alt="Gestión de Inventario">
      </div>
      <div class="carousel-item">
        <img src="../img/logosinfonfo.png" class="d-block w-100" alt="Optimización de Procesos">
      </div>
      <div class="carousel-item">
        <img src="../img/logo.svg" class="d-block w-100" alt="Análisis y Reportes">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="
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
          <a class="nav-link active" aria-current="page" href="index.php">Movimientos</a>
        </li>
        <?php if ($rolUsu == 'gerente'): ?>
        <li class="nav-item">
          <a class="nav-link" href="../usuarios/index.php">Usuarios</a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="../logout.php">Cerrar Sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
  
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Lista de Movimientos</h2>
      <?php if ($rolUsu == 'gerente' || $rolUsu == 'empleado'): ?>
      <div>
        <a href="create_movimiento.php" class="btn btn-primary"><i class="fas fa-plus"></i> Registrar Movimiento</a>
        <a href="generar_pdf.php" target="_blank" class="btn btn-secondary"><i class="fas fa-file-pdf"></i> Generar PDF</a>
      </div>
      <?php endif; ?>
    </div>
    <div class="table-responsive">
      <table class="table table-dark table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Tipo Movimiento</th>
            <th>Cantidad</th>
            <th>Fecha Movimiento</th>
            <th>Observaciones</th>
            <th>Usuario</th> <!-- Added User column header -->
            <?php if ($rolUsu == 'gerente'): ?>
            <th>Acciones</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php
          include 'db.php';
  
          $sql = "SELECT m.id, p.nombre AS producto_nombre, m.tipo_movimiento, m.cantidad, m.fecha_movimiento, m.observaciones, u.usuario AS usuario_nombre
                  FROM movimientos m
                  JOIN productos p ON m.id_producto = p.id
                  LEFT JOIN usuarios u ON m.id_usuario = u.id"; 
          $res = $con->query($sql);
  
          if ($res->num_rows > 0) {
              while($fila = $res->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($fila["id"]) . "</td>";
                  echo "<td>" . htmlspecialchars($fila["producto_nombre"]) . "</td>";
                  echo "<td>" . htmlspecialchars($fila["tipo_movimiento"]) . "</td>";
                  echo "<td>" . htmlspecialchars($fila["cantidad"]) . "</td>";
                  echo "<td>" . htmlspecialchars($fila["fecha_movimiento"]) . "</td>";
                  echo "<td>" . htmlspecialchars($fila["observaciones"]) . "</td>";
                  echo "<td>" . htmlspecialchars($fila["usuario_nombre"]) . "</td>"; 
                  if ($rolUsu == 'gerente') {
                      echo "<td>";
                      echo "<a href='edit_movimiento.php?id=" . $fila["id"] . "' class='btn btn-sm btn-warning me-2'><i class='fas fa-edit'></i> Editar</a>";
                      echo "<a href='delete_movimiento.php?id=" . $fila["id"] . "' class='btn btn-sm btn-danger'><i class='fas fa-trash'></i> Eliminar</a>";
                      echo "</td>";
                  }
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='" . ($rolUsu == 'gerente' ? '8' : '7') . "' class='text-center'>No se encontraron movimientos</td></tr>";
          }
          $con->close();
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="../adi_bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>