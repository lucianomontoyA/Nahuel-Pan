<?php
// Views/Gastos/index.php
// Esta vista lista los gastos y permite crear/editar usando un modal único.

// Espera que el controller haya definido la variable $gastos
// $gastos = (new Gasto())->obtenerTodos(...);

// Incluimos el header global (estilos, nav, scripts base)
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Navegación / Volver al inicio -->
<ul>
    <li><a href="/index.php" id="btn-inicio">Inicio</a></li>
</ul>

<div class="container-clientes">
    <h1>Gastos</h1>

    <!-- Botón que abre el modal para "Agregar Gasto" -->
    <div class="botonera-clientes" style="margin-bottom:1rem;">
        <button id="btn-agregar" class="boton">Agregar Gasto</button>
    </div>

    <!-- Tabla que lista los gastos -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Categoría</th>
                <th>Descripción</th>
                <th>Monto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($gastos)): ?>
                <?php foreach ($gastos as $g): ?>
                    <tr>
                        <!-- Mostramos la info del gasto -->
                        <td><?= htmlspecialchars($g['id']) ?></td>
                        <td><?= htmlspecialchars($g['fecha_pago']) ?></td>
                        <td><?= htmlspecialchars($g['categoria']) ?></td>
                        <td><?= htmlspecialchars($g['descripcion']) ?></td>
                        <td>$<?= number_format($g['monto'], 2) ?></td>
                        <td>
                            <!-- Botón Editar: abre el modal y carga los datos -->
                            <button
                                class="btn-editar"
                                data-id="<?= $g['id'] ?>"
                                data-categoria="<?= htmlspecialchars($g['categoria'], ENT_QUOTES) ?>"
                                data-monto="<?= htmlspecialchars($g['monto'], ENT_QUOTES) ?>"
                                data-descripcion="<?= htmlspecialchars($g['descripcion'], ENT_QUOTES) ?>"
                                data-fecha="<?= htmlspecialchars($g['fecha_pago'], ENT_QUOTES) ?>"
                            >Editar</button>

                            <!-- Formulario para eliminar gasto -->
                            <form action="../../Controllers/GastosController.php" method="POST" style="display:inline" onsubmit="return confirm('¿Seguro que querés eliminar este gasto?')">
                                <!-- Indica al controller qué acción tomar -->
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="id" value="<?= $g['id'] ?>">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Mensaje cuando no hay gastos -->
                <tr><td colspan="6" class="sin-clientes">No hay gastos registrados</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal oculto por defecto -->
<div id="modal-overlay" class="modal-overlay" style="display:none;">
    <div class="modal">
        <h2 id="modal-title">Nuevo Gasto</h2>

        <!-- Formulario que sirve para crear y editar -->
        <form id="gasto-form" action="../../Controllers/GastosController.php" method="POST">
            <!-- Hidden input: indica acción al controller ('crear' o 'editar') -->
            <input type="hidden" name="accion" id="form-accion" value="crear">
            <!-- Hidden input: id del gasto para editar -->
            <input type="hidden" name="id" id="form-id" value="">

            <!-- Campos del formulario -->
            <div class="campo-form">
                <label for="categoria">Categoría</label>
                <input type="text" id="categoria" name="categoria" required>
            </div>

            <div class="campo-form">
                <label for="monto">Monto</label>
                <input type="number" id="monto" name="monto" step="0.01" required>
            </div>

            <div class="campo-form">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3"></textarea>
            </div>

            <div class="campo-form">
                <label for="fecha_pago">Fecha y hora (opcional)</label>
                <input type="datetime-local" id="fecha_pago" name="fecha_pago">
                <small>Si lo dejás en blanco se usará la fecha por defecto del servidor.</small>
            </div>

            <!-- Botones de guardar / cancelar -->
            <div class="botonera-clientes">
                <button type="submit" class="boton">Guardar</button>
                <button type="button" id="btn-cancelar" class="boton">Cancelar</button>
            </div>
        </form>
    </div>
</div>


<!-- JS: controlar modal y edición -->
<script>
(function(){
    // Referencias DOM
    const overlay = document.getElementById('modal-overlay');
    const btnAgregar = document.getElementById('btn-agregar');
    const btnCancelar = document.getElementById('btn-cancelar');
    const form = document.getElementById('gasto-form');
    const accionInput = document.getElementById('form-accion');
    const idInput = document.getElementById('form-id');
    const tituloModal = document.getElementById('modal-title');

    // Campos del formulario
    const categoriaInput = document.getElementById('categoria');
    const montoInput = document.getElementById('monto');
    const descripcionInput = document.getElementById('descripcion');
    const fechaPagoInput = document.getElementById('fecha_pago');

    // Abrir modal para crear gasto (limpia campos)
    btnAgregar.addEventListener('click', () => {
        accionInput.value = 'crear';
        idInput.value = '';
        tituloModal.textContent = 'Nuevo Gasto';
        categoriaInput.value = '';
        montoInput.value = '';
        descripcionInput.value = '';
        fechaPagoInput.value = '';
        overlay.style.display = 'flex';
    });

    // Cancelar: cerrar modal
    btnCancelar.addEventListener('click', () => overlay.style.display = 'none');

    // Abrir modal para editar
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            const categoria = btn.getAttribute('data-categoria') || '';
            const monto = btn.getAttribute('data-monto') || '';
            const descripcion = btn.getAttribute('data-descripcion') || '';
            const fecha = btn.getAttribute('data-fecha') || '';

            // Preparar modal para editar
            accionInput.value = 'editar';
            idInput.value = id;
            tituloModal.textContent = 'Editar Gasto #' + id;
            categoriaInput.value = categoria;
            montoInput.value = monto;
            descripcionInput.value = descripcion;

            // Convertir fecha de MySQL a datetime-local
            if (fecha) {
                let dt = fecha.replace(' ', 'T').slice(0,16);
                fechaPagoInput.value = dt;
            } else {
                fechaPagoInput.value = '';
            }

            overlay.style.display = 'flex';
        });
    });

    // Antes de enviar el form, convertir fecha a formato MySQL
    form.addEventListener('submit', (e) => {
        const val = fechaPagoInput.value; // "YYYY-MM-DDTHH:MM"
        if (val) {
            const mysql = val.replace('T', ' ') + ':00';
            let hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'fecha_pago';
            hidden.value = mysql;
            form.appendChild(hidden);
        }
    });

    // Cerrar modal si se hace click fuera del contenido
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.style.display = 'none';
    });
})();
</script>

<?php
// Footer global
require_once __DIR__ . '/../../includes/footer.php';
?>
