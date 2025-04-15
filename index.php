<?php
require_once('includes/conexion.php');
require_once('includes/funciones.php');


include('includes/header.php');
?>


<div class="card">
    <h2>Bienvenido al Sistema de Ventas</h2>
   
    <p>Este sistema permite gestionar clientes, productos y ventas de una tienda.</p>
   
    <div class="stats-container">
        <div class="stat-card">
            <h3>Clientes Registrados</h3>
            <?php
            $query = "SELECT COUNT(*) as total FROM clientes";
            $resultado = mysqli_query($conexion, $query);
            $total = mysqli_fetch_assoc($resultado)['total'];
            mysqli_free_result($resultado);
            ?>
            <p class="stat-number"><?php echo $total; ?></p>
            <a href="clientes/listar.php" class="btn btn-editar">Ver Clientes</a>
        </div>
       
        <div class="stat-card">
            <h3>Productos Disponibles</h3>
            <?php
            $query = "SELECT COUNT(*) as total FROM productos";
            $resultado = mysqli_query($conexion, $query);
            $total = mysqli_fetch_assoc($resultado)['total'];
            mysqli_free_result($resultado);
            ?>
            <p class="stat-number"><?php echo $total; ?></p>
            <a href="productos/listar.php" class="btn btn-editar">Ver Cursos</a>
        </div>
       
        <div class="stat-card">
            <h3>Ventas Activas</h3>
            <?php
            $query = "SELECT COUNT(*) as total FROM ventas WHERE estado = 'Activa'";
            $resultado = mysqli_query($conexion, $query);
            $total = mysqli_fetch_assoc($resultado)['total'];
            mysqli_free_result($resultado);
            ?>
            <p class="stat-number"><?php echo $total; ?></p>
            <a href="ventas/listar.php" class="btn btn-editar">Ver Matrículas</a>
        </div>
    </div>
   
    <div class="recent-container">
        <h3>Últimas Ventas Registradas</h3>
        <?php
          $sql = "SELECT 
          v.id_venta,
          c.nombre AS nombre_cliente,
          c.apellido AS apellido_cliente,
          p.nombre AS producto,
          v.cantidad,
          v.precio_unitario,
          v.total,
          v.fecha_venta
      FROM ventas v
      JOIN clientes c ON v.id_cliente = c.id_cliente
      JOIN productos p ON v.id_producto = p.id_producto
      ORDER BY v.fecha_venta DESC
      LIMIT 5";
        $resultado = mysqli_query($conexion, $query);
        ?>
       
       <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre de cliente</th>
            <th>Apellido de cliente</th>
            <th>Producto</th>
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
                    <td><?php echo $fila['nombre_cliente']; ?></td>
                    <td><?php echo $fila['apellido_cliente']; ?></td>
                    <td><?php echo $fila['producto']; ?></td>
                    <td><?php echo $fila['cantidad']; ?></td>
                    <td><?php echo '$' . number_format($fila['precio_unitario'], 2); ?></td>
                    <td><?php echo '$' . number_format($fila['total'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No hay ventas registradas.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

    </div>
</div>


<?php
include('includes/footer.php');
mysqli_free_result($resultado);
cerrar_conexion($conexion);
?>
