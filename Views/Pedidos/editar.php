<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../Controllers/PedidosController.php';
require_once __DIR__ . '/../../Models/Producto.php';

$controller = new PedidosController();
$productoModel = new Producto();
$productos = $productoModel->obtenerTodos();




$pedido = null;
if (isset($_GET['id'])) {
    $pedido = $controller->obtenerPedidoPorIdPublic($_GET['id']);
}

if (!$pedido) {
    die("Error: Pedido no encontrado.");
}

// Array con IDs de productos seleccionados en el pedido
$productosSeleccionados = $pedido['productos'] ?? []; // Esto depende de cómo traigas los productos del pedido
?>

<div class="container-clientes">
    <h1>Editar Pedido</h1>

    <form action="../../Controllers/PedidosController.php" method="POST" class="formulario-crear">
        <input type="hidden" name="id" value="<?= $pedido['id'] ?>">

        <div class="campo-form">
            <label for="id_cliente">Cliente:</label>
            <input type="number" name="id_cliente" id="id_cliente" value="<?= $pedido['id_cliente'] ?>" required>
        </div>

        <div class="campo-form">
            <label>Productos:</label>
            <?php foreach ($productos as $prod): ?>
                <div>
                    <input type="checkbox" name="productos[]" value="<?= $prod['id'] ?>" id="prod-<?= $prod['id'] ?>"
                        <?= in_array($prod['id'], $productosSeleccionados) ? 'checked' : '' ?>>
                    <label for="prod-<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nombre']) ?> ($<?= $prod['precio'] ?>)</label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="campo-form">
            <label for="monto_pagado">Monto Pagado:</label>
            <input type="number" step="0.01" name="monto_pagado" id="monto_pagado" value="<?= $pedido['monto_pagado'] ?>" required>
        </div>

        <div class="campo-form">
            <label for="metodo_pago">Método de Pago:</label>
            <select name="metodo_pago" id="metodo_pago" required>
                <option value="efectivo" <?= $pedido['metodo_pago'] == 'efectivo' ? 'selected' : '' ?>>Efectivo</option>
                <option value="tarjeta" <?= $pedido['metodo_pago'] == 'tarjeta' ? 'selected' : '' ?>>Tarjeta</option>
                <option value="transferencia" <?= $pedido['metodo_pago'] == 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
            </select>
        </div>

        <div class="campo-form">
            <label for="pagado">Pagado:</label>
            <input type="checkbox" name="pagado" id="pagado" value="1" <?= $pedido['pagado'] ? 'checked' : '' ?>>
        </div>

        <div class="botonera-clientes">
            <button type="submit" name="accion" value="editar" class="boton">Guardar Cambios</button>
            <a href="index.php" class="boton">Volver</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
