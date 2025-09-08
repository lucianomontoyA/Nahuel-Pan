<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../Controllers/ClientesController.php';

$controller = new ClientesController(); 

$cliente = null;
if (isset($_GET['id'])) {  // Primero verificamos que exista 'id' en GET
    $cliente = $controller->obtenerClientePorId($_GET['id']);
}

if (!$cliente) {            // Si no hay cliente, mostramos error
    die("Error: Cliente no encontrado.");
}
?>      

<div class="container-clientes">                            
    <h1>Editar Cliente</h1>                                 

    <form action="../../Controllers/ClientesController.php" method="POST" class="formulario-crear"> 
        <input type="hidden" name="id" value="<?= $cliente['id'] ?>"> <!-- ID oculto para identificar el registro -->

        <div class="campo-form">                                                 
            <label for="nombre">Nombre:</label>                              
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>  
        </div>

        <div class="campo-form">                                                
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="<?= htmlspecialchars($cliente['apellido']) ?>" required>
        </div>

        <div class="campo-form">                                    
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>">
        </div>

        <div class="campo-form">                                     
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>
        </div>

        <div class="campo-form">                                     
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>" required>
        </div>

        <div class="botonera-clientes">                      
            <button type="submit" name="accion" value="editar" class="boton">Guardar Cambios</button> 
            <a href="index.php" class="boton">Volver</a>     
        </div>

    </form>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';        // Pie de página
?>