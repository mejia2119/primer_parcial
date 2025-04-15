<?php
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');

// Consulta para obtener ventas con información de clientes y productos
$query = "SELECT v.id_venta, v.fecha_venta, v.cantidad, v.precio_unitario, v.total,
                 c.id_cliente, c.nombre as nombre_cliente, c.apellido as apellido_cliente,
                 p.id_producto, p.nombre as nombre_producto
          FROM ventas v
          JOIN clientes c ON v.id_cliente = c.id_cliente
          JOIN productos p ON v.id_producto = p.id_producto
          ORDER BY v.fecha_venta DESC";
$resultado = mysqli_query($conexion, $query);

include('../includes/header.php');
?>

<div class="card">
    <h2>Listado de Ventas</h2>
   
    <a href="agregar.php" class="btn btn-agregar">Agregar Venta</a>
   
    <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success">
            <?php
            if ($_GET['exito'] == 'agregar') echo "Venta agregada correctamente.";
            elseif ($_GET['exito'] == 'editar') echo "Venta actualizada correctamente.";
            elseif ($_GET['exito'] == 'eliminar') echo "Venta eliminada correctamente.";
            ?>
        </div>
    <?php endif; ?>
   
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Fecha</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($resultado) > 0): ?>
                <?php while ($venta = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo $venta['id_venta']; ?></td>
                    <td>
                        <?php echo $venta['nombre_cliente'] . ' ' . $venta['apellido_cliente']; ?>
                    </td>
                    <td>
                        <?php echo $venta['nombre_producto']; ?>
                    </td>
                    <td><?php echo $venta['fecha_venta']; ?></td>
                    <td><?php echo $venta['cantidad']; ?></td>
                    <td><?php echo '$' . number_format($venta['precio_unitario'], 2); ?></td>
                    <td><?php echo '$' . number_format($venta['total'], 2); ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $venta['id_venta']; ?>" class="btn btn-editar">Editar</a>
                        <a href="eliminar.php?id=<?php echo $venta['id_venta']; ?>" class="btn btn-eliminar" onclick="return confirm('¿Está seguro de eliminar esta venta?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No hay ventas registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
include('../includes/footer.php');
mysqli_free_result($resultado);
cerrar_conexion($conexion);
?>
