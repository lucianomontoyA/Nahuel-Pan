<?php
// Views/inventario/crear.php
require_once __DIR__ . '/../../includes/header.php';
?>

<ul>
    <li><a href="/index.php" id="btn-inicio">Inicio</a></li>
</ul>

<div class="container-clientes">
    <h1>Agregar Insumo al Inventario</h1>

    <form action="../../Controllers/InventarioController.php" method="POST" class="formulario-crear">
        <input type="hidden" name="accion" value="crear">

        <div class="campo-form">
            <label for="nombre_insumo">Nombre del Insumo:</label>
            <input type="text" id="nombre_insumo" name="nombre_insumo" required>
        </div>

        <div class="campo-form">
            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" step="0.01" required>
        </div>

        <div class="campo-form">
            <label for="unidad">Unidad:</label>
            <input type="text" id="unidad" name="unidad" value="kg" required>
        </div>

        <div class="campo-form">
            <label for="observaciones">Observaciones:</label>
            <textarea id="observaciones" name="observaciones" rows="3"></textarea>
        </div>

        <div class="botonera-clientes">
            <button type="submit" class="boton">Guardar</button>
            <a href="listar.php" class="boton">Volver</a>
        </div>
    </form>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>
