<?php 
$page_title = 'Usuarios';
require_once __DIR__ . '/../templates/header.php'; 

// Solo los gerentes pueden ver esta página
if ($rolUsu !== 'gerente') {
    echo '<div class="container my-5"><div class="alert alert-danger">Acceso denegado. No tienes permiso para gestionar usuarios.</div></div>';
    require_once __DIR__ . '/../templates/footer.php';
    exit();
}
?>

<div class="container my-5">
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Gestión de Usuarios</h2>
            <div>
                <a href="create_usuario.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Registrar Usuario</a>
                <a href="generar_pdf.php" target="_blank" class="btn btn-secondary"><i class="fas fa-file-pdf"></i> Generar PDF</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
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
                    $result = $con->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["usuario"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["rol"]) . "</td>";
                            echo "<td>";
                            echo "<a href='edit_usuario.php?id=" . $row["id"] . "' class='btn btn-sm btn-warning me-2'><i class='fas fa-edit'></i> Editar</a>";
                            // Evitar que un gerente se elimine a sí mismo
                            if ($_SESSION['usuario'] !== $row['usuario']) {
                                echo "<a href='delete_usuario.php?id=" . $row["id"] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"¿Estás seguro de que quieres eliminar este usuario?\");'><i class='fas fa-trash'></i> Eliminar</a>";
                            }
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
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
