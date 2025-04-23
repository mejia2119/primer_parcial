<?php
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');

// Verificar si se proporcionó un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redireccionar("listar.php");
}

$id_cliente = intval($_GET['id']);

// Obtener datos del cliente
$query = "SELECT * FROM clientes WHERE id_cliente = $id_cliente";
$resultado = mysqli_query($conexion, $query);
$cliente = mysqli_fetch_assoc($resultado);

if (!$cliente) {
    redireccionar("listar.php");
}

// Procesar el formulario al enviarlo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = sanitizar($_POST['nombre']);
    $apellido = sanitizar($_POST['apellido']);
    $email = sanitizar($_POST['email']);
    $telefono = sanitizar($_POST['telefono']);
    $direccion = sanitizar($_POST['direccion']);

    // Validar campos obligatorios
    $errores = array();
    if (empty($nombre)) $errores[] = "El nombre es obligatorio.";
    if (empty($apellido)) $errores[] = "El apellido es obligatorio.";
    if (empty($email)) $errores[] = "El email es obligatorio.";

    // Si no hay errores, actualizar el cliente
    if (empty($errores)) {
        $query = "UPDATE clientes SET
                  nombre = '$nombre',
                  apellido = '$apellido',
                  email = '$email',
                  telefono = '$telefono',
                  direccion = '$direccion'
                  WHERE id_cliente = $id_cliente";

        if (mysqli_query($conexion, $query)) {
            redireccionar("listar.php?exito=editar");
        } else {
            $errores[] = "Error al actualizar el cliente: " . mysqli_error($conexion);
        }
    }
}

include('../includes/header.php');
?>

<div class="form-container">
    <h2>Editar Cliente</h2>

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

    <!-- Formulario para editar cliente -->
    <form method="post" action="">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control"
                   value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>
        </div>

        <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" class="form-control"
                   value="<?php echo htmlspecialchars($cliente['apellido']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control"
                   value="<?php echo htmlspecialchars($cliente['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" class="form-control"
                   value="<?php echo htmlspecialchars($cliente['telefono']); ?>">
        </div>

        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <textarea id="direccion" name="direccion" class="form-control"><?php echo htmlspecialchars($cliente['direccion']); ?></textarea>
        </div>

        <div class="form-group">
            <input type="submit" value="Actualizar Cliente" class="btn btn-submit">
            <a href="listar.php" class="btn btn-volver">Volver al Listado</a>
        </div>
    </form>
</div>

<?php
include('../includes/footer.php');
mysqli_free_result($resultado);
cerrar_conexion($conexion);
?>