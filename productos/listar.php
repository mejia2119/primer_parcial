<?php
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');

// Consulta para obtener productos
$query = "SELECT * FROM productos ORDER BY nombre";
$resultado = mysqli_query($conexion, $query);

include('../includes/header.php');
?>

<div class="card">
    <h2>Listado de Productos</h2>
   
    <a href="agregar.php" class="btn btn-agregar">Agregar Producto</a>
   
    <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success">
            <?php
            if ($_GET['exito'] == 'agregar') echo "Producto agregado correctamente.";
            elseif ($_GET['exito'] == 'editar') echo "Producto actualizado correctamente.";
            elseif ($_GET['exito'] == 'eliminar') echo "Producto eliminado correctamente.";
            ?>
        </div>
    <?php endif; ?>
   
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($resultado) > 0): ?>
                <?php while ($producto = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo $producto['id_producto']; ?></td>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo $producto['descripcion']; ?></td>
                    <td><?php echo '$' . number_format($producto['precio'], 2); ?></td>
                    <td><?php echo $producto['stock']; ?></td>
                    <td><?php echo $producto['categoria']; ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-editar">Editar</a>
                        <a href="eliminar.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-eliminar" onclick="return confirm('¿Está seguro de eliminar este producto?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No hay productos registrados.</td>
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
