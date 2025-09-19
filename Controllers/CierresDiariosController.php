<?php
require_once __DIR__ . '/../Models/CierreDiario.php';

class CierresDiariosController
{
    private $cierre;

    public function __construct()
    {
        $this->cierre = new CierreDiario();
    }

    // Listar cierres
    public function listar($soloHoy = false)
    {
        // Actualizamos o creamos el cierre del día
        $this->cierre->actualizarCierreDelDia();

        // Obtenemos los cierres
        if ($soloHoy) {
            $hoy = date('Y-m-d');
            $cierres = $this->cierre->obtenerPorFecha($hoy);
        } else {
            $cierres = $this->cierre->obtenerTodos();
        }

        require __DIR__ . '/../Views/CierresDiarios/index.php';
    }
}

// Router simple
$controller = new CierresDiariosController();
$accion = $_GET['accion'] ?? 'listar';
$soloHoy = isset($_GET['hoy']) && $_GET['hoy'] == 1;

if ($accion === 'listar') {
    $controller->listar($soloHoy);
}
