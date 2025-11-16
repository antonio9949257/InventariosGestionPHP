<?php 
$page_title = 'Categorías';
require_once __DIR__ . '/../templates/header.php'; 
?>

<div class="container my-5">
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Lista de Categorías</h2>
            <?php if ($rolUsu == 'gerente'): ?>
            <div>
                <a href="create_categoria.php" class="btn btn-primary"><i class="fas fa-plus"></i> Registrar Categoría</a>
                <a href="generar_pdf.php" target="_blank" class="btn btn-secondary"><i class="fas fa-file-pdf"></i> Generar PDF</a>
            </div>
            <?php endif; ?>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <?php if ($rolUsu == 'gerente'): ?>
                        <th>Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'db.php';
            
                    $sql = "SELECT id, nombre, descripcion FROM categorias";
                    $result = $con->query($sql);
            
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["descripcion"]) . "</td>";
                            if ($rolUsu == 'gerente') {
                                echo "<td>";
                                echo "<a href='edit_categoria.php?id=" . $row["id"] . "' class='btn btn-sm btn-warning me-2'><i class='fas fa-edit'></i> Editar</a>";
                                echo "<a href='delete_categoria.php?id=" . $row["id"] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"¿Estás seguro de que quieres eliminar esta categoría?\");'><i class='fas fa-trash'></i> Eliminar</a>";
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        $colspan = ($rolUsu == 'gerente') ? 4 : 3;
                        echo "<tr><td colspan='" . $colspan . "' class='text-center'>No se encontraron categorías</td></tr>";
                    }
                    $con->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
