<?php
session_start();

// Definir la URL base del proyecto. Asegúrate de que termine con una barra '/'.
define('BASE_URL', '/');

// Redirige si el usuario no está logueado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    header("Location: " . BASE_URL . "index.php");
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
    <title>FarmaCorp - <?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Inicio'; ?></title>
    <link href="<?php echo BASE_URL; ?>adi_bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --success-color: #198754;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
            --dark-color: #212529;
            --light-color: #f8f9fa;
            --body-bg: #f0f2f5;
        }
        body {
            background-color: var(--body-bg);
            color: var(--dark-color);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar-custom-dark {
            background-color: #343a40 !important;
        }
        .navbar {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .content-wrapper {
            flex: 1;
        }
        .footer {
            background-color: var(--dark-color);
            color: var(--light-color);
            padding: 20px 0;
        }
        /* Estilos para las páginas de tablas */
        .table-container {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        /* Estilos para Home.php */
        .welcome-header {
            background: linear-gradient(to right, var(--primary-color), #0056b3);
            color: var(--light-color);
            border-radius: 0.5rem;
        }
        .stat-card {
            border: none;
            border-radius: 0.5rem;
            padding: 1.5rem;
            color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s ease-in-out;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .stat-card-icon {
            font-size: 3rem;
            opacity: 0.7;
        }
        .stat-card-content {
            text-align: right;
        }
        .stat-card-title {
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        .stat-card-number {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .card-link {
            text-decoration: none;
            color: var(--dark-color);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            display: block;
            height: 100%;
        }
        .card-link .card {
            transition: border-color 0.2s ease-in-out;
        }
        .card-link:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
        .card-link:hover .card {
            border-color: var(--primary-color);
        }
        .card-link .card i {
            color: var(--primary-color);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>Home.php">FarmaCorp</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>categorias/">Categorías</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>proveedores/">Proveedores</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>productos/">Productos</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>movimientos/">Movimientos</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>chat_asistente.php" title="Asistente IA"><i class="fas fa-comments"></i> Asistente IA</a></li>
        <?php if ($rolUsu == 'gerente'): ?>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>usuarios/">Usuarios</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>logout.php" title="Cerrar Sesión"><i class="fas fa-sign-out-alt"></i></a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="content-wrapper">