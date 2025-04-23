<?php
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');

// Obtener clientes para el select
$query_clientes = "SELECT id_cliente, nombre, apellido FROM clientes ORDER BY apellido, nombre";
$resultado_clientes = mysqli_query($conexion, $query_clientes);

// Procesar filtro
$id_cliente = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : 0;

if ($id_cliente > 0) {
    // Obtener información del cliente seleccionado
    $query_cliente = "SELECT nombre, apellido FROM clientes WHERE id_cliente = $id_cliente";
    $resultado_cliente = mysqli_query($conexion, $query_cliente);
    $cliente = mysqli_fetch_assoc($resultado_cliente);
   
    $query = "SELECT p.id_producto, p.nombre AS nombre_producto, v.fecha_venta, v.cantidad, 
                     v.precio_unitario, v.total
              FROM ventas v
              JOIN productos p ON v.id_producto = p.id_producto
              WHERE v.id_cliente = $id_cliente
              ORDER BY v.fecha_venta DESC";
    $resultado = mysqli_query($conexion, $query);
}

include('../includes/header.php');
?>

<div class="card">
    <h2>Ventas por Cliente</h2>
   
    <form method="get" action="">
        <div class="form-group">
            <label for="id_cliente">Seleccionar Cliente:</label>
            <select name="id_cliente" id="id_cliente" class="form-control" required>
                <option value="">-- Seleccione un cliente --</option>
                <?php while ($cliente_select = mysqli_fetch_assoc($resultado_clientes)): ?>
                <option value="<?php echo $cliente_select['id_cliente']; ?>" <?php echo ($cliente_select['id_cliente'] == $id_cliente) ? 'selected' : ''; ?>>
                    <?php echo $cliente_select['apellido'] . ', ' . $cliente_select['nombre']; ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        <input type="submit" value="Generar Reporte" class="btn btn-submit">
    </form>
   
    <?php if ($id_cliente > 0 && isset($resultado)): ?>
    <div class="reporte-container">
        <h3>Cliente: <?php echo $cliente['apellido'] . ', ' . $cliente['nombre']; ?></h3>
        <p>Total de ventas realizadas: <?php echo mysqli_num_rows($resultado); ?></p>
       
        <table>
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Producto</th>
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
                        <td><?php echo $fila['id_producto']; ?></td>
                        <td><?php echo $fila['nombre_producto']; ?></td>
                        <td><?php echo $fila['fecha_venta']; ?></td>
                        <td><?php echo $fila['cantidad']; ?></td>
                        <td><?php echo '$' . number_format($fila['precio_unitario'], 2); ?></td>
                        <td><?php echo '$' . number_format($fila['total'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">El cliente no tiene ventas registradas.</td>
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
if (isset($resultado_cliente)) mysqli_free_result($resultado_cliente);
mysqli_free_result($resultado_clientes);
cerrar_conexion($conexion);
?>
