<?php
require_once('includes/conexion.php');
require_once('includes/funciones.php');

include('includes/header.php');
?>

<div class="card">
    <h2>Bienvenido al Sistema de Ventas</h2>
   
    <p>Este sistema permite gestionar clientes, productos y ventas de una tienda.</p>
   
    <div class="stats-container">
        <!-- Clientes Registrados -->
        <div class="stat-card">
            <h3>Clientes Registrados</h3>
            <?php
            $queryClientes = "SELECT COUNT(*) as total FROM clientes";
            $resultadoClientes = mysqli_query($conexion, $queryClientes);
            $totalClientes = $resultadoClientes ? mysqli_fetch_assoc($resultadoClientes)['total'] : 0;
            mysqli_free_result($resultadoClientes);
            ?>
            <p class="stat-number"><?php echo $totalClientes; ?></p>
            <a href="clientes/listar.php" class="btn btn-editar">Ver Clientes</a>
        </div>
       
        <!-- Productos Disponibles -->
        <div class="stat-card">
            <h3>Productos Disponibles</h3>
            <?php
            $queryProductos = "SELECT COUNT(*) as total FROM productos";
            $resultadoProductos = mysqli_query($conexion, $queryProductos);
            $totalProductos = $resultadoProductos ? mysqli_fetch_assoc($resultadoProductos)['total'] : 0;
            mysqli_free_result($resultadoProductos);
            ?>
            <p class="stat-number"><?php echo $totalProductos; ?></p>
            <a href="productos/listar.php" class="btn btn-editar">Ver Productos</a>
        </div>
       
        <!-- Ventas realizadas -->
        <div class="stat-card">
            <h3>Ventas realizadas</h3>
            <?php
            $queryVentas = "SELECT COUNT(*) as total FROM ventas";
            $resultadoVentas = mysqli_query($conexion, $queryVentas);
            $totalVentas = $resultadoVentas ? mysqli_fetch_assoc($resultadoVentas)['total'] : 0;
            mysqli_free_result($resultadoVentas);
            ?>
            <p class="stat-number"><?php echo $totalVentas; ?></p>
            <a href="ventas/listar.php" class="btn btn-editar">Ver Ventas</a>
        </div>
    </div>
   
    <div class="recent-container">
        <h3>Últimas Ventas Registradas</h3>
        <?php
        $sqlVentas = "SELECT 
            v.id_venta,
            c.nombre AS nombre_cliente,
            c.apellido AS apellido_cliente,
            p.nombre AS producto,
            v.cantidad,
            v.precio_unitario,
            v.total,
            v.fecha_venta
        FROM ventas v
        JOIN clientes c ON v.id_cliente = c.id_cliente
        JOIN productos p ON v.id_producto = p.id_producto
        ORDER BY v.fecha_venta DESC
        LIMIT 5";
        $resultadoVentas = mysqli_query($conexion, $sqlVentas);
        ?>
       
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Cliente</th>
                    <th>Apellido del Cliente</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultadoVentas && mysqli_num_rows($resultadoVentas) > 0): ?>
                    <?php while ($fila = mysqli_fetch_assoc($resultadoVentas)): ?>
                        <tr>
                            <td><?php echo $fila['id_venta']; ?></td>
                            <td><?php echo htmlspecialchars($fila['nombre_cliente']); ?></td>
                            <td><?php echo htmlspecialchars($fila['apellido_cliente']); ?></td>
                            <td><?php echo htmlspecialchars($fila['producto']); ?></td>
                            <td><?php echo $fila['cantidad']; ?></td>
                            <td><?php echo '$' . number_format($fila['precio_unitario'], 2); ?></td>
                            <td><?php echo '$' . number_format($fila['total'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No hay ventas registradas.</td>
                    </tr>
                <?php endif; ?>
                <?php mysqli_free_result($resultadoVentas); ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include('includes/footer.php');
cerrar_conexion($conexion);
?>
