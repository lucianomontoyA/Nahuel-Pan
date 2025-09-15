<?php
// Views/CierresDiarios/index.php
// Lista los cierres diarios y permite crear/editar con modal

require_once __DIR__ . '/../../includes/header.php';
?>

<ul>
    <li><a href="/index.php" id="btn-inicio">Inicio</a></li>
</ul>

<div class="container-clientes">
    <h1>Cierres Diarios</h1>

    <!-- Botón para abrir modal -->
    <div class="botonera-clientes" style="margin-bottom:1rem;">
        <button id="btn-agregar" class="boton">Crear Cierre Diario</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Total Vendido</th>
                <th>Total Cobrado</th>
                <th>Total Resto</th>
                <th>Total Gastos</th>
                <th>Saldo Final</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($cierres)): ?>
                <?php foreach ($cierres as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['id']) ?></td>
                        <td><?= htmlspecialchars($c['fecha']) ?></td>
                        <td>$<?= number_format($c['total_vendido'], 2) ?></td>
                        <td>$<?= number_format($c['total_cobrado'], 2) ?></td>
                        <td>$<?= number_format($c['total_resto'], 2) ?></td>
                        <td>$<?= number_format($c['total_gastos'], 2) ?></td>
                        <td>$<?= number_format($c['saldo_final'], 2) ?></td>
                        <td>
                            <button
                                class="btn-editar"
                                data-id="<?= $c['id'] ?>"
                                data-fecha="<?= htmlspecialchars($c['fecha'], ENT_QUOTES) ?>"
                                data-total_vendido="<?= htmlspecialchars($c['total_vendido'], ENT_QUOTES) ?>"
                                data-total_cobrado="<?= htmlspecialchars($c['total_cobrado'], ENT_QUOTES) ?>"
                                data-total_resto="<?= htmlspecialchars($c['total_resto'], ENT_QUOTES) ?>"
                                data-total_gastos="<?= htmlspecialchars($c['total_gastos'], ENT_QUOTES) ?>"
                                data-saldo_final="<?= htmlspecialchars($c['saldo_final'], ENT_QUOTES) ?>"
                            >Editar</button>

                            <form action="../../Controllers/CierresDiariosController.php" method="POST" style="display:inline" onsubmit="return confirm('¿Seguro que querés eliminar este cierre?')">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" class="sin-clientes">No hay cierres diarios registrados</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal para crear/editar -->
<div id="modal-overlay" class="modal-overlay" style="display:none;">
    <div class="modal">
        <h2 id="modal-title">Nuevo Cierre Diario</h2>

        <form id="cierre-form" action="../../Controllers/CierresDiariosController.php" method="POST">
            <input type="hidden" name="accion" id="form-accion" value="crear">
            <input type="hidden" name="id" id="form-id" value="">

            <div class="campo-form">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha" required>
            </div>

            <div class="campo-form">
                <label for="total_vendido">Total Vendido</label>
                <input type="number" id="total_vendido" name="total_vendido" step="0.01">
            </div>

            <div class="campo-form">
                <label for="total_cobrado">Total Cobrado</label>
                <input type="number" id="total_cobrado" name="total_cobrado" step="0.01">
            </div>

            <div class="campo-form">
                <label for="total_resto">Total Resto</label>
                <input type="number" id="total_resto" name="total_resto" step="0.01">
            </div>

            <div class="campo-form">
                <label for="total_gastos">Total Gastos</label>
                <input type="number" id="total_gastos" name="total_gastos" step="0.01">
            </div>

            <div class="campo-form">
                <label for="saldo_final">Saldo Final</label>
                <input type="number" id="saldo_final" name="saldo_final" step="0.01">
            </div>

            <div class="botonera-clientes">
                <button type="submit" class="boton">Guardar</button>
                <button type="button" id="btn-cancelar" class="boton">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
(function(){
    const overlay = document.getElementById('modal-overlay');
    const btnAgregar = document.getElementById('btn-agregar');
    const btnCancelar = document.getElementById('btn-cancelar');
    const form = document.getElementById('cierre-form');
    const accionInput = document.getElementById('form-accion');
    const idInput = document.getElementById('form-id');
    const tituloModal = document.getElementById('modal-title');

    const fechaInput = document.getElementById('fecha');
    const totalVendidoInput = document.getElementById('total_vendido');
    const totalCobradoInput = document.getElementById('total_cobrado');
    const totalRestoInput = document.getElementById('total_resto');
    const totalGastosInput = document.getElementById('total_gastos');
    const saldoFinalInput = document.getElementById('saldo_final');

    btnAgregar.addEventListener('click', () => {
        accionInput.value = 'crear';
        idInput.value = '';
        tituloModal.textContent = 'Nuevo Cierre Diario';
        fechaInput.value = '';
        totalVendidoInput.value = '';
        totalCobradoInput.value = '';
        totalRestoInput.value = '';
        totalGastosInput.value = '';
        saldoFinalInput.value = '';
        overlay.style.display = 'flex';
    });

    btnCancelar.addEventListener('click', () => overlay.style.display = 'none');

    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            accionInput.value = 'editar';
            idInput.value = id;
            tituloModal.textContent = 'Editar Cierre Diario #' + id;

            fechaInput.value = btn.getAttribute('data-fecha');
            totalVendidoInput.value = btn.getAttribute('data-total_vendido');
            totalCobradoInput.value = btn.getAttribute('data-total_cobrado');
            totalRestoInput.value = btn.getAttribute('data-total_resto');
            totalGastosInput.value = btn.getAttribute('data-total_gastos');
            saldoFinalInput.value = btn.getAttribute('data-saldo_final');

            overlay.style.display = 'flex';
        });
    });

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.style.display = 'none';
    });
})();
</script>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>
