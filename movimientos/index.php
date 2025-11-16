<?php 
$page_title = 'Movimientos';
require_once __DIR__ . '/../templates/header.php'; 
?>

<div class="container my-5">
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Lista de Movimientos</h2>
            <?php if ($rolUsu == 'gerente' || $rolUsu == 'empleado'): ?>
            <div>
                <a href="create_movimiento.php" class="btn btn-primary"><i class="fas fa-plus"></i> Registrar Movimiento</a>
                <a href="generar_pdf.php" target="_blank" class="btn btn-secondary"><i class="fas fa-file-pdf"></i> Generar PDF</a>
            </div>
            <?php endif; ?>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Tipo Movimiento</th>
                        <th>Cantidad</th>
                        <th>Fecha Movimiento</th>
                        <th>Observaciones</th>
                        <th>Usuario</th>
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
                            LEFT JOIN usuarios u ON m.id_usuario = u.id
                            ORDER BY m.fecha_movimiento DESC"; 
                    $result = $con->query($sql);
            
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["producto_nombre"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["tipo_movimiento"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["cantidad"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["fecha_movimiento"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["observaciones"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["usuario_nombre"]) . "</td>"; 
                            if ($rolUsu == 'gerente') {
                                echo "<td>";
                                echo "<a href='edit_movimiento.php?id=" . $row["id"] . "' class='btn btn-sm btn-warning me-2'><i class='fas fa-edit'></i> Editar</a>";
                                echo "<a href='delete_movimiento.php?id=" . $row["id"] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('¿Estás seguro de que quieres eliminar este movimiento?');\"><i class='fas fa-trash'></i> Eliminar</a>";
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        $colspan = ($rolUsu == 'gerente') ? 8 : 7;
                        echo "<tr><td colspan='" . $colspan . "' class='text-center'>No se encontraron movimientos</td></tr>";
                    }
                    $con->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
