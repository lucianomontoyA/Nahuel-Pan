<?php
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Cierres Diarios</h1>

<?php if (!empty($cierres)): ?>
    <table class="tabla-cierres">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Total Vendido</th>
                <th>Total Cobrado</th>
                <th>Total Resto</th>
                <th>Total Gastos</th>
                <th>Saldo Final</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cierres as $cierre): ?>
                <tr>
                    <td><?php echo date('d-m-Y', strtotime($cierre['fecha'])); ?></td>
                    <td><?php echo number_format($cierre['total_vendido'], 2); ?></td>
                    <td><?php echo number_format($cierre['total_cobrado'], 2); ?></td>
                    <td><?php echo number_format($cierre['total_resto'], 2); ?></td>
                    <td><?php echo number_format($cierre['total_gastos'], 2); ?></td>
                    <td><?php echo number_format($cierre['saldo_final'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay registros de cierres diarios para mostrar.</p>
<?php endif; ?>
