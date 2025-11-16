<?php 
$page_title = 'Inicio';
require_once 'templates/header.php'; 

// --- Lógica para obtener estadísticas ---
require __DIR__ . '/categorias/db.php'; 

$total_productos = $con->query("SELECT COUNT(*) as count FROM productos")->fetch_assoc()['count'];
$total_categorias = $con->query("SELECT COUNT(*) as count FROM categorias")->fetch_assoc()['count'];
$total_proveedores = $con->query("SELECT COUNT(*) as count FROM proveedores")->fetch_assoc()['count'];
$total_movimientos = $con->query("SELECT COUNT(*) as count FROM movimientos")->fetch_assoc()['count'];
?>

<div class="container my-5">
    <div class="p-5 mb-5 rounded-3 welcome-header">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-5 fw-bold">Bienvenido al Inventario de FarmaCorp</h1>
            <p class="fs-4">Usuario: <?php echo $nomUsu; ?> (<?php echo $rolUsu; ?>)</p>
        </div>
    </div>

    <!-- Dashboard de Estadísticas -->
    <div class="row mb-5">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card bg-primary">
                <div class="stat-card-body">
                    <div class="stat-card-icon"><i class="fas fa-box-open"></i></div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Productos</div>
                        <div class="stat-card-number"><?php echo $total_productos; ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card bg-success">
                <div class="stat-card-body">
                    <div class="stat-card-icon"><i class="fas fa-tags"></i></div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Categorías</div>
                        <div class="stat-card-number"><?php echo $total_categorias; ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card bg-warning text-dark">
                <div class="stat-card-body">
                    <div class="stat-card-icon"><i class="fas fa-truck"></i></div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Proveedores</div>
                        <div class="stat-card-number"><?php echo $total_proveedores; ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card bg-info">
                <div class="stat-card-body">
                    <div class="stat-card-icon"><i class="fas fa-exchange-alt"></i></div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Movimientos</div>
                        <div class="stat-card-number"><?php echo $total_movimientos; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos Directos -->
    <h2 class="mb-4 text-center">Accesos Directos</h2>
    <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="./categorias/" class="card-link">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-tags fa-3x mb-3"></i>
                        <h5 class="card-title">Gestión de Categorías</h5>
                        <p class="card-text">Administre las categorías de sus productos.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="./productos/" class="card-link">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <h5 class="card-title">Gestión de Productos</h5>
                        <p class="card-text">Administre el catálogo de sus productos.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="./proveedores/" class="card-link">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-truck fa-3x mb-3"></i>
                        <h5 class="card-title">Gestión de Proveedores</h5>
                        <p class="card-text">Administre la información de sus proveedores.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="./movimientos/" class="card-link">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-exchange-alt fa-3x mb-3"></i>
                        <h5 class="card-title">Gestión de Movimientos</h5>
                        <p class="card-text">Registre las entradas y salidas de productos.</p>
                    </div>
                </div>
            </a>
        </div>
        <?php if ($rolUsu == 'gerente'): ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="./usuarios/" class="card-link">
                <div class="card h-100 text-center">
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

<?php require_once 'templates/footer.php'; ?>