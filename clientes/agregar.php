<?php
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = sanitizar($_POST['id_cliente']);
    $nombre = sanitizar($_POST['nombre']);
    $apellido = sanitizar($_POST['apellido']);
    $email = sanitizar($_POST['email']);
    $telefono = sanitizar($_POST['telefono']);
    $direccion = sanitizar($_POST['direccion']);
   
    // Validar campos obligatorios
    $errores = array();
   
    if (empty($id_cliente)) $errores[] = "El ID del cliente es obligatorio";
    if (empty($nombre)) $errores[] = "El nombre es obligatorio";
    if (empty($apellido)) $errores[] = "El apellido es obligatorio";
    if (empty($email)) $errores[] = "El email es obligatorio";
   
    if (empty($errores)) {
        $query = "INSERT INTO clientes (id_cliente, nombre, apellido, email, telefono, direccion)
                  VALUES ('$id_cliente', '$nombre', '$apellido', '$email', '$telefono', '$direccion')";
       
        if (mysqli_query($conexion, $query)) {
            redireccionar("listar.php?exito=agregar");
        } else {
            $errores[] = "Error al agregar cliente: " . mysqli_error($conexion);
        }
    }
}

include('../includes/header.php');
?>

<div class="form-container">
    <h2>Agregar Nuevo Cliente</h2>
   
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
            <label for="id_cliente">ID Cliente:</label>
            <input type="text" id="id_cliente" name="id_cliente" class="form-control" required>
        </div>
       
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
       
        <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" class="form-control" required>
        </div>
       
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
       
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" class="form-control">
        </div>
       
        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <textarea id="direccion" name="direccion" class="form-control"></textarea>
        </div>
       
        <div class="form-group">
            <input type="submit" value="Guardar Cliente" class="btn btn-submit">
            <a href="listar.php" class="btn btn-volver">Volver al Listado</a>
        </div>
    </form>
</div>

<?php
include('../includes/footer.php');
cerrar_conexion($conexion);
?>
