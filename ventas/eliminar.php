<?php
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');

// Verificar si se proporciona un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redireccionar("listar.php");
}

$id_venta = intval($_GET['id']);

// Procesar el formulario al enviarlo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "DELETE FROM ventas WHERE id_venta = $id_venta";
   
    if (mysqli_query($conexion, $query)) {
        redireccionar("listar.php?exito=eliminar");
    } else {
        redireccionar("listar.php?error=eliminar");
    }
}

include('../includes/header.php');
?>

<div class="form-container">
    <h2>Eliminar Venta</h2>
   
    <p>¿Está seguro que desea eliminar esta venta? Esta acción no se puede deshacer.</p>
   
    <form method="post" action="">
        <div class="form-group">
            <input type="submit" value="Confirmar Eliminación" class="btn btn-eliminar">
            <a href="listar.php" class="btn btn-volver">Cancelar</a>
        </div>
    </form>
</div>

<?php
include('../includes/footer.php');
cerrar_conexion($conexion);
?>