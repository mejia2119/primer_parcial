<?php
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');

// Obtener productos distintos
$query_productos = "SELECT DISTINCT id_producto, nombre FROM productos ORDER BY nombre";
$resultado_productos = mysqli_query($conexion, $query_productos);

// Procesar filtro
$id_producto = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : 0;

if ($id_producto > 0) {
    $query = "SELECT v.id_venta, v.fecha_venta, v.cantidad, v.precio_unitario, v.total,
                     c.id_cliente, c.nombre AS nombre_cliente, c.apellido AS apellido_cliente,
                     p.id_producto, p.nombre AS nombre_producto
              FROM ventas v
              JOIN clientes c ON v.id_cliente = c.id_cliente
              JOIN productos p ON v.id_producto = p.id_producto
              WHERE v.id_producto = $id_producto
              ORDER BY v.fecha_venta DESC";
    $resultado = mysqli_query($conexion, $query);

    // Obtener información del producto seleccionado
    $query_producto = "SELECT nombre FROM productos WHERE id_producto = $id_producto";
    $resultado_producto = mysqli_query($conexion, $query_producto);
    $producto = mysqli_fetch_assoc($resultado_producto);
}

include('../includes/header.php');
?>

<div class="card">
    <h2>Ventas por Producto</h2>
   
    <form method="get" action="">
        <div class="form-group">
            <label for="id_producto">Seleccionar Producto:</label>
            <select name="id_producto" id="id_producto" class="form-control" required>
                <option value="">-- Seleccione un producto --</option>
                <?php while ($producto_select = mysqli_fetch_assoc($resultado_productos)): ?>
                <option value="<?php echo $producto_select['id_producto']; ?>" <?php echo ($producto_select['id_producto'] == $id_producto) ? 'selected' : ''; ?>>
                    <?php echo $producto_select['nombre']; ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        <input type="submit" value="Generar Reporte" class="btn btn-submit">
    </form>
    <?php if ($id_producto > 0 && isset($resultado)): ?>
    <div class="reporte-container">
        <h3>Producto: <?php echo $producto['nombre']; ?></h3>
        <p>Total de ventas: <?php echo mysqli_num_rows($resultado); ?></p>
       
        <table>
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Cliente</th>
                    <th>Fecha Venta</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo $fila['id_venta']; ?></td>
                        <td><?php echo $fila['nombre_cliente'] . ' ' . $fila['apellido_cliente']; ?></td>
                        <td><?php echo $fila['fecha_venta']; ?></td>
                        <td><?php echo $fila['cantidad']; ?></td>
                        <td><?php echo '$' . number_format($fila['precio_unitario'], 2); ?></td>
                        <td><?php echo '$' . number_format($fila['total'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay ventas registradas para este producto.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php
include('../includes/footer.php');
if (isset($resultado)) mysqli_free_result($resultado);
if (isset($resultado_producto)) mysqli_free_result($resultado_producto);
mysqli_free_result($resultado_productos);
cerrar_conexion($conexion);
?>
