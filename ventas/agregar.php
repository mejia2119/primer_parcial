<?php
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');

// Obtener listas de clientes y productos
$query_clientes = "SELECT id_cliente, nombre, apellido FROM clientes ORDER BY apellido, nombre";
$resultado_clientes = mysqli_query($conexion, $query_clientes);

$query_productos = "SELECT id_producto, nombre, precio FROM productos ORDER BY nombre";
$resultado_productos = mysqli_query($conexion, $query_productos);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = intval($_POST['id_cliente']);
    $id_producto = intval($_POST['id_producto']);
    $fecha_venta = sanitizar($_POST['fecha_venta']);
    $cantidad = intval($_POST['cantidad']);
    $precio_unitario = floatval($_POST['precio_unitario']);
    $total = $cantidad * $precio_unitario;

    // Validar campos obligatorios
    $errores = array();

    if ($id_cliente <= 0) $errores[] = "Debe seleccionar un cliente.";
    if ($id_producto <= 0) $errores[] = "Debe seleccionar un producto.";
    if (empty($fecha_venta)) $errores[] = "La fecha de venta es obligatoria.";
    if ($cantidad <= 0) $errores[] = "La cantidad debe ser mayor a cero.";
    if ($precio_unitario <= 0) $errores[] = "El precio unitario debe ser mayor a cero.";

    if (empty($errores)) {
        $query = "INSERT INTO ventas (id_cliente, id_producto, fecha_venta, cantidad, precio_unitario, total)
                  VALUES ($id_cliente, $id_producto, '$fecha_venta', $cantidad, $precio_unitario, $total)";

        if (mysqli_query($conexion, $query)) {
            redireccionar("listar.php?exito=agregar");
        } else {
            $errores[] = "Error al registrar la venta: " . mysqli_error($conexion);
        }
    }
}

include('../includes/header.php');
?>

<div class="form-container">
    <h2>Registrar Nueva Venta</h2>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-group">
            <label for="id_cliente">Cliente:</label>
            <select id="id_cliente" name="id_cliente" class="form-control" required>
                <option value="">-- Seleccione un cliente --</option>
                <?php while ($cliente = mysqli_fetch_assoc($resultado_clientes)): ?>
                    <option value="<?php echo $cliente['id_cliente']; ?>"
                        <?php echo (isset($_POST['id_cliente']) && $_POST['id_cliente'] == $cliente['id_cliente']) ? 'selected' : ''; ?>>
                        <?php echo $cliente['apellido'] . ', ' . $cliente['nombre']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_producto">Producto:</label>
            <select id="id_producto" name="id_producto" class="form-control" required>
                <option value="">-- Seleccione un producto --</option>
                <?php while ($producto = mysqli_fetch_assoc($resultado_productos)): ?>
                    <option value="<?php echo $producto['id_producto']; ?>"
                        <?php echo (isset($_POST['id_producto']) && $_POST['id_producto'] == $producto['id_producto']) ? 'selected' : ''; ?>>
                        <?php echo $producto['nombre'] . ' ($' . number_format($producto['precio'], 2) . ')'; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="fecha_venta">Fecha de Venta:</label>
            <input type="date" id="fecha_venta" name="fecha_venta" class="form-control"
                   value="<?php echo isset($_POST['fecha_venta']) ? $_POST['fecha_venta'] : date('Y-m-d'); ?>" required>
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" class="form-control"
                   value="<?php echo isset($_POST['cantidad']) ? $_POST['cantidad'] : 1; ?>" min="1" required>
        </div>

        <div class="form-group">
            <label for="precio_unitario">Precio Unitario:</label>
            <input type="number" step="0.01" id="precio_unitario" name="precio_unitario" class="form-control"
                   value="<?php echo isset($_POST['precio_unitario']) ? $_POST['precio_unitario'] : ''; ?>" required>
        </div>

        <div class="form-group">
            <input type="submit" value="Registrar Venta" class="btn btn-submit">
            <a href="listar.php" class="btn btn-volver">Volver al Listado</a>
        </div>
    </form>
</div>

<?php
include('../includes/footer.php');
mysqli_free_result($resultado_clientes);
mysqli_free_result($resultado_productos);
cerrar_conexion($conexion);
?>