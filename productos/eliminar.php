<?php
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');

// Verificar si se proporciona un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redireccionar("listar.php");
}

$id_producto = intval($_GET['id']);

// Verificar si el producto tiene ventas asociadas
$query_ventas = "SELECT COUNT(*) as total FROM ventas WHERE id_producto = $id_producto";
$resultado_ventas = mysqli_query($conexion, $query_ventas);
$total_ventas = mysqli_fetch_assoc($resultado_ventas)['total'];

if ($total_ventas > 0) {
    // No se puede eliminar, tiene ventas asociadas
    redireccionar("listar.php?error=ventas");
}

// Procesar eliminación si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "DELETE FROM productos WHERE id_producto = $id_producto";
   
    if (mysqli_query($conexion, $query)) {
        redireccionar("listar.php?exito=eliminar");
    } else {
        redireccionar("listar.php?error=eliminar");
    }
}

include('../includes/header.php');
?>

<div class="form-container">
    <h2>Eliminar Producto</h2>
   
    <?php if ($total_ventas > 0): ?>
        <div class="alert alert-error">
            No se puede eliminar este producto porque tiene ventas asociadas.
        </div>
        <a href="listar.php" class="btn btn-volver">Volver al Listado</a>
    <?php else: ?>
        <p>¿Está seguro que desea eliminar este producto? Esta acción no se puede deshacer.</p>
       
        <form method="post" action="">
            <div class="form-group">
                <input type="submit" value="Confirmar Eliminación" class="btn btn-eliminar">
                <a href="listar.php" class="btn btn-volver">Cancelar</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php
include('../includes/footer.php');
mysqli_free_result($resultado_ventas);
cerrar_conexion($conexion);
?>
