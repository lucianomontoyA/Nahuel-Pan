<?php
// Controllers/CierresDiariosController.php

require_once __DIR__ . '/../Models/CierreDiario.php';

class CierresDiariosController
{
    private $cierre;

    public function __construct()
    {
        $this->cierre = new CierreDiario();
    }

    // Método para listar cierres
    public function listar($soloHoy = false)
    {
        if ($soloHoy) {
            $hoy = date('Y-m-d');
            $cierres = $this->cierre->obtenerPorFecha($hoy);
        } else {
            $cierres = $this->cierre->obtenerTodos();
        }

        require __DIR__ . '/../Views/CierresDiarios/index.php';
    }

    // Crear cierre diario
    public function crear()
    {
        if (isset($_POST['fecha'])) {
            $fecha = $_POST['fecha'];
            $total_vendido = $_POST['total_vendido'] ?? 0;
            $total_cobrado = $_POST['total_cobrado'] ?? 0;
            $total_resto = $_POST['total_resto'] ?? 0;
            $total_gastos = $_POST['total_gastos'] ?? 0;
            $saldo_final = $_POST['saldo_final'] ?? 0;

            if ($this->cierre->guardar($fecha, $total_vendido, $total_cobrado, $total_resto, $total_gastos, $saldo_final)) {
                header('Location: CierresDiariosController.php?accion=listar&hoy=1');
                exit;
            } else {
                die("Error: No se pudo guardar el cierre diario.");
            }
        }
    }

    // Editar cierre diario
    public function editar($id)
    {
        $cierreActual = $this->cierre->obtenerPorId($id);
        if (!$cierreActual) die("Error: Cierre diario no encontrado.");

        if (isset($_POST['fecha'])) {
            $fecha = $_POST['fecha'];
            $total_vendido = $_POST['total_vendido'] ?? 0;
            $total_cobrado = $_POST['total_cobrado'] ?? 0;
            $total_resto = $_POST['total_resto'] ?? 0;
            $total_gastos = $_POST['total_gastos'] ?? 0;
            $saldo_final = $_POST['saldo_final'] ?? 0;

            if ($this->cierre->actualizar($id, $fecha, $total_vendido, $total_cobrado, $total_resto, $total_gastos, $saldo_final)) {
                header('Location: CierresDiariosController.php?accion=listar&hoy=1');
                exit;
            } else {
                die("Error: No se pudo actualizar el cierre diario.");
            }
        }

        require __DIR__ . '/../Views/CierresDiarios/index.php';
    }

    // Eliminar cierre diario
    public function eliminar($id)
    {
        if ($this->cierre->eliminar($id)) {
            header('Location: CierresDiariosController.php?accion=listar&hoy=1');
            exit;
        } else {
            die("Error: No se pudo eliminar el cierre diario.");
        }
    }
}

// Router
$controller = new CierresDiariosController();

// POST: crear, editar, eliminar
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
    }
}

// GET: listar cierres
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $accion = $_GET['accion'] ?? 'listar';
    if ($accion === 'listar') {
        $soloHoy = isset($_GET['hoy']) && $_GET['hoy'] == 1;
        $controller->listar($soloHoy);
    }
}
