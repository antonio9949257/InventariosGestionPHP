<?php
session_start();


if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    header("Location: index.html");
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
    <title>Sistema de Inventario</title>
    <link href="./adi_bootstrap/css/bootstrap.min.css" rel="stylesheet">
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
        .welcome-card {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(img/prin_medico.jpg) no-repeat center center;
            background-size: cover;
            border: none;
            box-shadow: 0 8px 16px rgba(0,0,0,0.5);
        }
        .card-link {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .card-link:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.7);
        }
        .footer {
            background-color: 
            padding: 20px 0;
            margin-top: 40px;
            border-top: 1px solid 
        }
    </style>
</head>
<body>

<div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="
    <button type="button" data-bs-target="
    <button type="button" data-bs-target="
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="img/medico1.jpg" class="d-block w-100" alt="Gestión de Inventario">
      <div class="carousel-caption d-none d-md-block">
        <h5>Gestión de Inventario</h5>
        <p>Control total sobre sus productos y movimientos.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="img/medico2.jpg" class="d-block w-100" alt="Optimización de Procesos">
      <div class="carousel-caption d-none d-md-block">
        <h5>Optimización de Procesos</h5>
        <p>Mejore la eficiencia de su cadena de suministro.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="img/medico3.jpg" class="d-block w-100" alt="Análisis y Reportes">
      <div class="carousel-caption d-none d-md-block">
        <h5>Análisis y Reportes</h5>
        <p>Tome decisiones informadas con datos precisos.</p>
      </div>
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
          <a class="nav-link" href="
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./categorias/index.php">Categorías</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./proveedores/index.php">Proveedores</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./productos/index.php">Productos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./movimientos/index.php">Movimientos</a>
        </li>
        <?php if ($rolUsu == 'gerente'): ?>
        <li class="nav-item">
          <a class="nav-link" href="./usuarios/index.php">Usuarios</a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Cerrar Sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-5">
    <div class="p-5 mb-4 rounded-3 welcome-card">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-5 fw-bold">Bienvenido, <?php echo $nomUsu; ?> (<?php echo $rolUsu; ?>)</h1>
            <p class="fs-4">Gestión eficiente de su inventario.</p>
        </div>
    </div>

    <div class="row text-center">
        <div class="col-md-6 mb-4">
            <a href="./categorias/index.php" class="text-decoration-none text-white">
                <div class="card bg-dark card-link h-100">
                    <div class="card-body">
                        <i class="fas fa-tags fa-3x mb-3"></i>
                        <h5 class="card-title">Gestión de Categorías</h5>
                        <p class="card-text">Administre las categorías de sus productos.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 mb-4">
            <a href="./productos/index.php" class="text-decoration-none text-white">
                <div class="card bg-dark card-link h-100">
                    <div class="card-body">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <h5 class="card-title">Gestión de Productos</h5>
                        <p class="card-text">Administre el catálogo de sus productos.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 mb-4">
            <a href="./proveedores/index.php" class="text-decoration-none text-white">
                <div class="card bg-dark card-link h-100">
                    <div class="card-body">
                        <i class="fas fa-truck fa-3x mb-3"></i>
                        <h5 class="card-title">Gestión de Proveedores</h5>
                        <p class="card-text">Administre la información de sus proveedores.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 mb-4">
            <a href="./movimientos/index.php" class="text-decoration-none text-white">
                <div class="card bg-dark card-link h-100">
                    <div class="card-body">
                        <i class="fas fa-exchange-alt fa-3x mb-3"></i>
                        <h5 class="card-title">Gestión de Movimientos</h5>
                        <p class="card-text">Registre las entradas y salidas de productos.</p>
                    </div>
                </div>
            </a>
        </div>
        <?php if ($rolUsu == 'gerente'): ?>
        <div class="col-md-6 mb-4">
            <a href="./usuarios/index.php" class="text-decoration-none text-white">
                <div class="card bg-dark card-link h-100">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <h5 class="card-title">Gestión de Usuarios</h5>
                        <p class="card-text">Administre los usuarios del sistema.</p>
                    </div>
                </div>
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<footer class="footer text-white">
    <div class="container text-center">
        <p>&copy; 2025 Sistema de Inventario. Todos los derechos reservados.</p>
        <p>
            <a href="
            <a href="
            <a href="
        </p>
    </div>
</footer>

<script src="./adi_bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
