<?php
// Views/Gastos/crearEditar.php
// Esta vista se usa tanto para crear como para editar gastos dentro de un modal.
// Espera que $gasto exista si es edición; si es nuevo, $gasto puede ser null.

$esEdicion = isset($gasto) && !empty($gasto); // Determina si estamos editando
?>

<!-- Modal oculto por defecto -->
<div id="modal-overlay" class="modal-overlay" style="display:none;">
    <div class="modal">
        <!-- Título dinámico -->
        <h2 id="modal-title"><?= $esEdicion ? "Editar Gasto #".$gasto['id'] : "Nuevo Gasto" ?></h2>

        <!-- Formulario que sirve tanto para crear como editar -->
        <form id="gasto-form" action="../../Controllers/GastosController.php" method="POST">
            <!-- Hidden input: indica acción al controller ('crear' o 'editar') -->
            <input type="hidden" name="accion" id="form-accion" value="<?= $esEdicion ? 'editar' : 'crear' ?>">
            <!-- Hidden input: id del gasto para editar -->
            <input type="hidden" name="id" id="form-id" value="<?= $esEdicion ? $gasto['id'] : '' ?>">

            <!-- Campos del formulario -->
            <div class="campo-form">
                <label for="categoria">Categoría</label>
                <input type="text" id="categoria" name="categoria" value="<?= $esEdicion ? htmlspecialchars($gasto['categoria'], ENT_QUOTES) : '' ?>" required>
            </div>

            <div class="campo-form">
                <label for="monto">Monto</label>
                <input type="number" id="monto" name="monto" step="0.01" value="<?= $esEdicion ? $gasto['monto'] : '' ?>" required>
            </div>

            <div class="campo-form">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3"><?= $esEdicion ? htmlspecialchars($gasto['descripcion'], ENT_QUOTES) : '' ?></textarea>
            </div>

            <div class="campo-form">
                <label for="fecha_pago">Fecha y hora (opcional)</label>
                <input type="datetime-local" id="fecha_pago" name="fecha_pago" 
                    value="<?= $esEdicion && $gasto['fecha_pago'] ? date('Y-m-d\TH:i', strtotime($gasto['fecha_pago'])) : '' ?>">
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

<!-- JS: controlar apertura/cierre del modal y edición -->
<script>
(function(){
    const overlay = document.getElementById('modal-overlay');
    const btnCancelar = document.getElementById('btn-cancelar');
    const form = document.getElementById('gasto-form');

    // Cerrar modal al hacer click en cancelar
    btnCancelar.addEventListener('click', () => overlay.style.display = 'none');

    // Cerrar modal si se hace click fuera del contenido
    overlay.addEventListener('click', (e) => {
        if(e.target === overlay) overlay.style.display = 'none';
    });

    // Antes de enviar el form, convertir fecha a formato MySQL
    form.addEventListener('submit', e => {
        const fechaInput = document.getElementById('fecha_pago');
        const val = fechaInput.value; // "YYYY-MM-DDTHH:MM"
        if(val){
            const mysql = val.replace('T', ' ') + ':00';
            let hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'fecha_pago';
            hidden.value = mysql;
            form.appendChild(hidden);
        }
    });
})();
</script>
