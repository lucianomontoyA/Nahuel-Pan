<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../Models/Cliente.php';
require_once __DIR__ . '/../../Models/Producto.php';

$clienteModel = new Cliente();
$clientes = $clienteModel->obtenerTodos();

$productoModel = new Producto();
$productos = $productoModel->obtenerTodos();
?>


<!-- Botón que rdiecciona al Inicio -->
<ul>
    <li><a href="/index.php" id="btn-inicio">Inicio</a></li>
</ul>




<div class="container-clientes">
    <h1>Crear Pedido</h1>





  <form action="../../Controllers/PedidosController.php" method="POST" class="formulario-crear">

        <!-- Selección de Cliente -->
        <div class="campo-form">
            <label for="id_cliente">Cliente:</label>
            <select name="id_cliente" id="id_cliente" required>
                <option value="">-- Seleccione un cliente --</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
            <!-- Selección de Fecha de entrega -->
                <div class="campo-form">
            <label for="fecha_entrega">Fecha de Entrega:</label>
            <input type="date" name="fecha_entrega" id="fecha_entrega" value="<?= date('Y-m-d') ?>" required>
            </div>



        <!-- Selección de Productos -->
        <div class="campo-form">
            <label>Productos:</label>
            <?php foreach ($productos as $prod): ?>
                <div>
                    <input type="checkbox" name="productos_seleccionados[]" value="<?= $prod['id'] ?>" id="prod-<?= $prod['id'] ?>" class="chk-prod">
                    <label for="prod-<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nombre']) ?></label>

                    <?php if ($prod['tipo'] == 'kilo'): ?>
                        <input type="number" step="0.01" name="kilos[<?= $prod['id'] ?>]" placeholder="Kilos" class="input-prod" disabled>
                    <?php else: ?>
                        <input type="number" step="1" name="unidades[<?= $prod['id'] ?>]" placeholder="Unidades" class="input-prod" disabled>
                    <?php endif; ?>

                    <input type="number" step="0.01" name="precio[<?= $prod['id'] ?>]" placeholder="Precio <?= htmlspecialchars($prod['nombre']) ?>" class="input-prod" disabled>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Descripción del Pedido -->
        <label for="descripcion">Descripción:</label>
        <input type="text" name="descripcion" id="descripcion" required>


        <!-- Método de Pago -->
        <div class="campo-form">
            <label for="metodo_pago">Método de Pago:</label>
            <select name="metodo_pago" id="metodo_pago" required>
                <option value="">-- Seleccione método --</option>
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="transferencia">Transferencia</option>
            </select>
        </div>

        <!-- Monto Pagado -->
        <div class="campo-form">
            <label for="monto_pagado">Monto Pagado:</label>
            <input type="number" step="0.01" name="monto_pagado" id="monto_pagado" disabled>
        </div>

        <!-- Resta por Pagar -->
        <div class="campo-form">
            <label for="resta_pagar">Resta por Pagar:</label>
            <input type="number" step="0.01" id="resta_pagar" value="0" readonly>
        </div>

        <!-- Pagado -->
        <div class="campo-form">
            <label for="pagado">Pagado:</label>
            <input type="checkbox" name="pagado" id="pagado" value="1">
        </div>

        <!-- Botones -->
        <div class="botonera-clientes">
            <button type="submit" name="accion" value="crear" class="boton">Guardar</button>
            <a href="index.php" class="boton">Volver</a>
        </div>
    </form>
</div>

<!-- Script Reactivo -->
<script>
const checkboxes = document.querySelectorAll('.chk-prod');
const montoPagadoInput = document.getElementById('monto_pagado');
const restaPagarInput = document.getElementById('resta_pagar');
const metodoPagoSelect = document.getElementById('metodo_pago');

function calcularTotal() {
    let total = 0;

    checkboxes.forEach(chk => {
        if (chk.checked) {
            const parent = chk.parentElement;
            const precioInput = parent.querySelector('input[name^="precio"]');
            let cantidadInput = parent.querySelector('input[name^="kilos"]') || parent.querySelector('input[name^="unidades"]');

            let cantidad = parseFloat(cantidadInput.value) || 0;
            let precio = parseFloat(precioInput.value) || 0;

            total += cantidad * precio;
        }
    });

    const montoPagado = parseFloat(montoPagadoInput.value) || 0;
    const resta = total - montoPagado;
    restaPagarInput.value = resta.toFixed(2);
}

// Habilitar inputs y recalcular cuando se marque/desmarque
checkboxes.forEach(chk => {
    chk.addEventListener('change', function() {
        const parentDiv = this.parentElement;
        parentDiv.querySelectorAll('.input-prod').forEach(input => {
            input.disabled = !this.checked;
        });
        calcularTotal();
    });

    const parentDiv = chk.parentElement;
    parentDiv.querySelectorAll('.input-prod').forEach(input => {
        input.addEventListener('input', calcularTotal);
    });
});

// Habilitar/deshabilitar monto_pagado según método de pago
metodoPagoSelect.addEventListener('change', function() {
    montoPagadoInput.disabled = this.value === "";
    if(this.value === "") {
        montoPagadoInput.value = "";
        calcularTotal();
    }
});

// Recalcular si cambia el monto pagado
montoPagadoInput.addEventListener('input', calcularTotal);
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
