<?php
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_producto = sanitizar($_POST['id_producto']);
    $nombre = sanitizar($_POST['nombre']);
    $descripcion = sanitizar($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $categoria = sanitizar($_POST['categoria']);
   
    // Validar campos obligatorios
    $errores = array();
   
    if (empty($id_producto)) $errores[] = "El ID del producto es obligatorio.";
    if (empty($nombre)) $errores[] = "El nombre es obligatorio.";
    if ($precio <= 0) $errores[] = "El precio debe ser mayor a cero.";
    if ($stock < 0) $errores[] = "El stock no puede ser negativo.";
    if (empty($categoria)) $errores[] = "La categoría es obligatoria.";
   
    if (empty($errores)) {
        $query = "INSERT INTO productos (id_producto, nombre, descripcion, precio, stock, categoria)
                  VALUES ('$id_producto', '$nombre', '$descripcion', $precio, $stock, '$categoria')";
       
        if (mysqli_query($conexion, $query)) {
            redireccionar("listar.php?exito=agregar");
        } else {
            $errores[] = "Error al agregar producto: " . mysqli_error($conexion);
        }
    }
}

include('../includes/header.php');
?>

<div class="form-container">
    <h2>Agregar Nuevo Producto</h2>
   
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
            <label for="id_producto">ID Producto:</label>
            <input type="text" id="id_producto" name="id_producto" class="form-control" required>
        </div>
       
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
       
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
        </div>
       
        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" step="0.01" id="precio" name="precio" class="form-control" min="0.01" required>
        </div>
       
        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" class="form-control" min="0" required>
        </div>
       
        <div class="form-group">
            <label for="categoria">Categoría:</label>
            <input type="text" id="categoria" name="categoria" class="form-control" required>
        </div>
       
        <div class="form-group">
            <input type="submit" value="Guardar Producto" class="btn btn-submit">
            <a href="listar.php" class="btn btn-volver">Volver al Listado</a>
        </div>
    </form>
</div>

<?php
include('../includes/footer.php');
cerrar_conexion($conexion);
?>
