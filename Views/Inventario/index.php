<?php
// Views/inventario/index.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../Models/Inventario.php';

// Crear instancia del modelo
$inventarioModel = new Inventario();

// Traer todos los insumos desde la base de datos
$insumos = $inventarioModel->obtenerTodos();
?>

<!-- Botón que direcciona al Inicio -->
<ul>
    <li><a href="/index.php" id="btn-inicio">Inicio</a></li>
</ul>

<div class="container-clientes">
    <h1>Inventario - Insumos</h1>

    <div class="botonera-clientes">
        <a href="crear.php" class="boton">Agregar Insumo</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Insumo</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Observaciones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($insumos)) : ?>
                <?php foreach ($insumos as $insumo) : ?>
                    <tr>
                        <td><?= htmlspecialchars($insumo['id']) ?></td>
                        <td><?= htmlspecialchars($insumo['nombre_insumo']) ?></td>
                        <td><?= htmlspecialchars($insumo['cantidad']) ?></td>
                        <td><?= htmlspecialchars($insumo['unidad']) ?></td>
                        <td><?= htmlspecialchars($insumo['observaciones']) ?></td>
                        <td>
                            <a href="editar.php?id=<?= $insumo['id'] ?>">Editar</a> |
                            <a href="../../Controllers/InventarioController.php?accion=eliminar&id=<?= $insumo['id'] ?>"
                               onclick="return confirm('¿Seguro que querés eliminar este insumo?')">
                               Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr><td colspan="6" class="sin-clientes">No hay insumos registrados</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>
