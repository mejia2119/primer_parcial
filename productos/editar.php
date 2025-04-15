<?php
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');

// Verificar si se proporciona un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redireccionar("listar.php");
}

$id_producto = intval($_GET['id']);

// Obtener datos del producto
$query = "SELECT * FROM productos WHERE id_producto = $id_producto";
$resultado = mysqli_query($conexion, $query);
$producto = mysqli_fetch_assoc($resultado);

if (!$producto) {
    redireccionar("listar.php");
}

// Procesar el formulario al enviarlo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = sanitizar($_POST['nombre']);
    $descripcion = sanitizar($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $categoria = sanitizar($_POST['categoria']);

    // Validar campos obligatorios
    $errores = array();
    if (empty($nombre)) $errores[] = "El nombre es obligatorio.";
    if (empty($descripcion)) $errores[] = "La descripción es obligatoria.";
    if ($precio <= 0) $errores[] = "El precio debe ser mayor a cero.";
    if ($stock < 0) $errores[] = "El stock no puede ser negativo.";
    if (empty($categoria)) $errores[] = "La categoría es obligatoria.";

    // Si no hay errores, actualizar el producto
    if (empty($errores)) {
        $query = "UPDATE productos SET
                  nombre = '$nombre',
                  descripcion = '$descripcion',
                  precio = $precio,
                  stock = $stock,
                  categoria = '$categoria'
                  WHERE id_producto = $id_producto";

        if (mysqli_query($conexion, $query)) {
            redireccionar("listar.php?exito=editar");
        } else {
            $errores[] = "Error al actualizar el producto: " . mysqli_error($conexion);
        }
    }
}

include('../includes/header.php');
?>

<div class="form-container">
    <h2>Editar Producto</h2>

    <!-- Mostrar errores si los hay -->
    <?php if (!empty($errores)): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Formulario para editar producto -->
    <form method="post" action="">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control"
                   value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" class="form-control"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" step="0.01" id="precio" name="precio" class="form-control"
                   value="<?php echo $producto['precio']; ?>" min="0.01" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" class="form-control"
                   value="<?php echo $producto['stock']; ?>" min="0" required>
        </div>

        <div class="form-group">
            <label for="categoria">Categoría:</label>
            <input type="text" id="categoria" name="categoria" class="form-control"
                   value="<?php echo htmlspecialchars($producto['categoria']); ?>" required>
        </div>

        <div class="form-group">
            <input type="submit" value="Actualizar Producto" class="btn btn-submit">
            <a href="listar.php" class="btn btn-volver">Volver al Listado</a>
        </div>
    </form>
</div>

<?php
include('../includes/footer.php');
mysqli_free_result($resultado);
cerrar_conexion($conexion);
?>
