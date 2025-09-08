<?php
// Controllers/InventarioController.php
require_once __DIR__ . '/../Models/Inventario.php';

$inventario = new Inventario();

// Determinar acción
$accion = $_POST['accion'] ?? $_GET['accion'] ?? 'listar';

switch($accion) {

    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_insumo  = $_POST['nombre_insumo'] ?? '';
            $cantidad       = $_POST['cantidad'] ?? 0;
            $unidad         = $_POST['unidad'] ?? '';
            $observaciones  = $_POST['observaciones'] ?? '';

            // Guardar insumo usando el modelo
            $resultado = $inventario->guardar(
                $nombre_insumo,
                $cantidad,
                $unidad,
                $observaciones
            );

            if ($resultado) {
                header('Location: ../Views/inventario/index.php');
                exit;
            } else {
                die("Error al guardar el insumo.");
            }
        }
        break;

    case 'eliminar':
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $inventario->eliminar($id);
        }
        header('Location: ../Views/inventario/listar.php');
        exit;
        break;

    case 'listar':
    default:
        $insumos = $inventario->obtenerTodos();
        require_once __DIR__ . '/../Views/inventario/listar.php';
        break;
}
