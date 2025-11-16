<?php 
$page_title = 'Productos';
require_once __DIR__ . '/../templates/header.php'; 
?>

<div class="container my-5">
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Lista de Productos</h2>
            <?php if ($rolUsu == 'gerente'): ?>
            <div>
                <a href="create_producto.php" class="btn btn-primary"><i class="fas fa-plus"></i> Registrar Producto</a>
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
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Stock</th>
                        <th>Stock Mínimo</th>
                        <th>Categoría</th>
                        <th>Proveedor</th>
                        <?php if ($rolUsu == 'gerente'): ?>
                        <th>Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'db.php';
            
                    $sql = "SELECT p.id, p.nombre, p.descripcion, p.precio_compra, p.precio_venta, p.stock, p.stock_minimo, c.nombre AS categoria_nombre, pr.nombre AS proveedor_nombre 
                            FROM productos p
                            LEFT JOIN categorias c ON p.id_categoria = c.id
                            LEFT JOIN proveedores pr ON p.id_proveedor = pr.id";
                    $result = $con->query($sql);
            
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["descripcion"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["precio_compra"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["precio_venta"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["stock"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["stock_minimo"]) . "</td>"; 
                            echo "<td>" . htmlspecialchars($row["categoria_nombre"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["proveedor_nombre"]) . "</td>";
                            if ($rolUsu == 'gerente') {
                                echo "<td>";
                                echo "<a href='edit_producto.php?id=" . $row["id"] . "' class='btn btn-sm btn-warning me-2'><i class='fas fa-edit'></i> Editar</a>";
                                echo "<a href='delete_producto.php?id=" . $row["id"] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('¿Estás seguro de que quieres eliminar este producto?');\"><i class='fas fa-trash'></i> Eliminar</a>";
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        $colspan = ($rolUsu == 'gerente') ? 10 : 9;
                        echo "<tr><td colspan='" . $colspan . "' class='text-center'>No se encontraron productos</td></tr>";
                    }
                    $con->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>