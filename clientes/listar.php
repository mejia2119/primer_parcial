<?php
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');

// Consulta para obtener clientes
$query = "SELECT * FROM clientes ORDER BY apellido, nombre";
$resultado = mysqli_query($conexion, $query);

include('../includes/header.php');
?>

<div class="card">
    <h2>Listado de Clientes</h2>
   
    <a href="agregar.php" class="btn btn-agregar">Agregar Cliente</a>
   
    <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success">
            <?php
            if ($_GET['exito'] == 'agregar') echo "Cliente agregado correctamente.";
            elseif ($_GET['exito'] == 'editar') echo "Cliente actualizado correctamente.";
            elseif ($_GET['exito'] == 'eliminar') echo "Cliente eliminado correctamente.";
            ?>
        </div>
    <?php endif; ?>
   
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($resultado) > 0): ?>
                <?php while ($cliente = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo $cliente['id_cliente']; ?></td>
                    <td><?php echo $cliente['nombre']; ?></td>
                    <td><?php echo $cliente['apellido']; ?></td>
                    <td><?php echo $cliente['email']; ?></td>
                    <td><?php echo $cliente['telefono']; ?></td>
                    <td><?php echo $cliente['direccion']; ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $cliente['id_cliente']; ?>" class="btn btn-editar">Editar</a>
                        <a href="eliminar.php?id=<?php echo $cliente['id_cliente']; ?>" class="btn btn-eliminar" onclick="return confirm('¿Está seguro de eliminar este cliente?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No hay clientes registrados.</td>
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
