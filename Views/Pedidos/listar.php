<?php


require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Botón que rdiecciona al Inicio -->
<ul>
    <li><a href="/index.php" id="btn-inicio">Inicio</a></li>
</ul>



<div class="container-clientes">
    <h1>Pedidos</h1>
    <div class="botonera-clientes">
        <a href="crear.php" class="boton">Crear Pedido</a>
        <a href="index.php" class="boton">Listar Pedidos</a>
    </div>

    <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Productos</th>
            <th>Descripción</th>
            <th>Total Pedido</th>
            <th>Monto Pagado</th>
            <th>Saldo Pendiente</th>
            <th>Pagado</th>
            <th>Met. Pago</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($pedidos)): ?>
            <?php foreach($pedidos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['cliente']) ?></td>
                    <td><?= htmlspecialchars($p['productos']) ?></td>
                    <td><?= htmlspecialchars($p['descripcion']) ?></td>
                    <td>$<?= number_format($p['total_pedido'], 2) ?></td>
                    <td>$<?= number_format($p['monto_pagado'], 2) ?></td>
                    <td>$<?= number_format($p['saldo_pendiente'], 2) ?></td>
                    <td><?= $p['pagado'] ? 'Sí' : 'No' ?></td>
                    <td><?= htmlspecialchars($p['metodo_pago']) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $p['id'] ?>">Editar</a> |
                        <a href="borrar.php?id=<?= $p['id'] ?>" onclick="return confirm('¿Seguro que querés borrar este pedido?')">Borrar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="10" class="sin-clientes">No hay pedidos cargados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>
