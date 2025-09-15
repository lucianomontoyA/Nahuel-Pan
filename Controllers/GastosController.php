<?php
require_once __DIR__ . '/../Models/Gastos.php';

class GastosController
{
    private $gasto;

    public function __construct()
    {
        $this->gasto = new Gasto();
    }

    // Método para listar todos los gastos
    public function listar()
    {
        $gastos = $this->gasto->obtenerTodos();
        require __DIR__ . '/../Views/Gastos/index.php';
    }

    // Crear un gasto
    public function crear()
    {
        if (isset($_POST['categoria'], $_POST['monto'])) {
            $categoria = trim($_POST['categoria']);
            $monto = (float)$_POST['monto'];
            $descripcion = $_POST['descripcion'] ?? null;
            $fecha_pago = !empty($_POST['fecha_pago']) ? $_POST['fecha_pago'] : date('Y-m-d H:i:s');

            if ($this->gasto->guardar($categoria, $monto, $descripcion, $fecha_pago)) {
                $this->listar(); // recarga la lista directamente
                exit;
            } else {
                die("Error: No se pudo guardar el gasto.");
            }
        }
    }

    // Editar un gasto existente
    public function editar($id)
    {
        $gastoActual = $this->gasto->obtenerPorId($id);
        if (!$gastoActual) die("Error: Gasto no encontrado.");

        if (isset($_POST['categoria'], $_POST['monto'])) {
            $categoria = trim($_POST['categoria']);
            $monto = (float)$_POST['monto'];
            $descripcion = $_POST['descripcion'] ?? null;
            $fecha_pago = !empty($_POST['fecha_pago']) ? $_POST['fecha_pago'] : date('Y-m-d H:i:s');

            if ($this->gasto->actualizar($id, $categoria, $monto, $descripcion, $fecha_pago)) {
                $this->listar();
                exit;
            } else {
                die("Error: No se pudo actualizar el gasto.");
            }
        }

        // Si no se envió formulario, mostramos la vista de edición
        $gastos = $this->gasto->obtenerTodos();
        require __DIR__ . '/../Views/Gastos/index.php';
    }

    // Eliminar gasto
    public function eliminar($id)
    {
        if ($this->gasto->eliminar($id)) {
            $this->listar();
            exit;
        } else {
            die("Error: No se pudo eliminar el gasto.");
        }
    }
}

// Router
$controller = new GastosController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'crear':
            $controller->crear();
            break;
        case 'editar':
            if (isset($_POST['id'])) $controller->editar($_POST['id']);
            break;
        case 'eliminar':
            if (isset($_POST['id'])) $controller->eliminar($_POST['id']);
            break;
        default:
            $controller->listar();
            break;
    }
} else {
    // GET o cualquier otro método
    $controller->listar();
}
