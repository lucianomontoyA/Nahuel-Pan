<?php
require_once __DIR__ . '/../../includes/header.php';
?>

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
                <?php foreach ($insumos as $mov) : ?>
                    <tr>
                        <td><?= htmlspecialchars($mov['id']) ?></td>
                        <td><?= htmlspecialchars($mov['nombre_insumo']) ?></td>
                        <td><?= htmlspecialchars($mov['cantidad']) ?></td>
                        <td><?= htmlspecialchars($mov['unidad']) ?></td>
                        <td><?= htmlspecialchars($mov['observaciones']) ?></td>
                        <td>
                            <a href="editar.php?id=<?= $mov['id'] ?>">Editar</a> |
                            <a href="../../Controllers/InventarioController.php?accion=eliminar&id=<?= $mov['id'] ?>"
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
