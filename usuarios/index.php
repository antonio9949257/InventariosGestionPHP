<?php
session_start();


if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../index.php"); 
    exit();
} 

$nomUsu = htmlspecialchars($_SESSION['usuario']);
$rolUsu = htmlspecialchars($_SESSION['rol']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="../adi_bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https:
    <style>
        body {
            background-color: 
            color: 
        }
        .navbar {
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        .carousel-item img {
            height: 30vh; 
            object-fit: cover;
            filter: brightness(0.6);
        }
        .container {
            margin-top: 50px;
        }
        .card {
            background-color: 
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
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
        .btn-danger {
            background-color: 
            border-color: 
        }
        .btn-danger:hover {
            background-color: 
            border-color: 
        }
        .btn-secondary {
            background-color: 
            border-color: 
        }
        .btn-secondary:hover {
            background-color: 
            border-color: 
        }
    </style>
</head>
<body>
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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="text-white">Gestión de Usuarios</h1>
            <div>
                <a href="create_usuario.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Registrar Usuario</a>
                <a href="generar_pdf.php" target="_blank" class="btn btn-secondary"><i class="fas fa-file-pdf"></i> Generar PDF</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'db.php';

                    $sql = "SELECT id, usuario, rol FROM usuarios";
                    $res = $con->query($sql);

                    if ($res->num_rows > 0) {
                        while($fila = $res->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($fila["id"]) . "</td>";
                            echo "<td>" . htmlspecialchars($fila["usuario"]) . "</td>";
                            echo "<td>" . htmlspecialchars($fila["rol"]) . "</td>";
                            echo "<td>";
                            echo "<a href='edit_usuario.php?id=" . $fila["id"] . "' class='btn btn-sm btn-warning me-2'><i class='fas fa-edit'></i> Editar</a>";
                            echo "<a href='delete_usuario.php?id=" . $fila["id"] . "' class='btn btn-sm btn-danger'><i class='fas fa-trash'></i> Eliminar</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No se encontraron usuarios</td></tr>";
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