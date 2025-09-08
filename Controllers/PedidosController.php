<?php
require_once __DIR__ . '/../Models/Pedido.php';
require_once __DIR__ . '/../Models/Cliente.php';

class PedidosController
{
    private $pedido;
    private $cliente;

    public function __construct()
    {
        $this->pedido = new Pedido();
        $this->cliente = new Cliente(); 
    }

    public function listar() {
        $pedidos = $this->pedido->obtenerTodos();
        require __DIR__ . '/../Views/Pedidos/listar.php';
    }

   public function crear()
{
    // Recoger datos del formulario
    $productos_seleccionados = $_POST['productos_seleccionados'] ?? [];
    $kilos = $_POST['kilos'] ?? [];
    $unidades = $_POST['unidades'] ?? [];
    $precios = $_POST['precio'] ?? [];
    $fecha_entrega = $_POST['fecha_entrega'] ?? date('Y-m-d');

    if (isset($_POST['id_cliente'], $_POST['descripcion'], $_POST['metodo_pago'])) {
        $id_cliente = trim($_POST['id_cliente']);
        $descripcion = trim($_POST['descripcion']);
        $monto_pagado = trim($_POST['monto_pagado'] ?? 0);
        $pagado = isset($_POST['pagado']) ? 1 : 0;
        $metodo_pago = trim($_POST['metodo_pago']);

        // Guardar el pedido y obtener su ID
        $id_pedido = $this->pedido->guardar(
            $id_cliente,
            $descripcion,
            $productos_seleccionados,
            $kilos,
            $unidades,
            $precios,
            $monto_pagado,
            $pagado,
            $metodo_pago,
            $fecha_entrega
        );

        if ($id_pedido) {
            // Restar insumos automáticamente del inventario
            require_once __DIR__ . '/../Models/Inventario.php';
            $inventario = new Inventario();

            foreach ($productos_seleccionados as $index => $id_producto) {
                // Determinar la cantidad vendida (por kilos o unidades)
                $cantidad_vendida = $kilos[$index] ?? $unidades[$index] ?? 1;

                // Usar los insumos correspondientes
                $inventario->usarInsumos($id_pedido, $id_producto, $cantidad_vendida);
            }

            // Redirigir al index de pedidos
            header('Location: ../Views/Pedidos/index.php');
            exit;
        } else {
            die("Error: No se pudo guardar el pedido.");
        }
    }
}


    




    public function obtenerPedidoPorIdPublic($id) {
        return $this->pedido->obtenerPedidoPorId($id);
    }

    public function editar($id)
    {
        $pedidoActual = $this->pedido->obtenerPedidoPorId($id);
        if (!$pedidoActual) die("Error: Pedido no encontrado.");

        if (isset($_POST['id_cliente'], $_POST['descripcion'], $_POST['kilos'], $_POST['precio_por_kilo'], $_POST['monto_pagado'], $_POST['pagado'], $_POST['metodo_pago'])) {
            $id_cliente = trim($_POST['id_cliente']);
            $descripcion = trim($_POST['descripcion']);
            $kilos = trim($_POST['kilos']);
            $precio_por_kilo = trim($_POST['precio_por_kilo']);
            $monto_pagado = trim($_POST['monto_pagado']);
            $pagado = isset($_POST['pagado']) ? 1 : 0;
            $metodo_pago = trim($_POST['metodo_pago']);
            $fecha_entrega = $_POST['fecha_entrega'] ?? date('Y-m-d');


            if ($this->pedido->actualizar($id, $id_cliente, $descripcion, $kilos, $precio_por_kilo, $monto_pagado, $pagado, $metodo_pago,$fecha_entrega)) {
                header('Location: ../Views/Pedidos/index.php');
                exit;
            } else {
                die("Error: No se pudo actualizar el pedido.");
            }
        }

        $clientes = $this->cliente->obtenerTodos();
        require __DIR__ . '/../Views/Pedidos/editar.php';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $controller = new PedidosController();
    switch ($_POST['accion']) {
        case 'crear':
            $controller->crear();
            break;
        case 'editar':
            if (isset($_POST['id'])) $controller->editar($_POST['id']);
            break;
    }
}
