    <?php

    class Pedido
    {
        // Trae los últimos 10 pedidos
    public function obtenerTodos()
    {
    require_once __DIR__ . '/../Database/Database.php';
    $db = new Database();
    $pdo = $db->getConnection();

    $stmt = $pdo->prepare("
        SELECT 
            p.id,
            CONCAT(c.nombre, ' ', c.apellido) AS cliente,
            p.descripcion,
            p.monto_pagado,
            p.pagado,
            p.metodo_pago,
            p.fecha_entrega,
            IFNULL(SUM(pp.cantidad * pp.precio), 0) AS total_pedido,
            (IFNULL(SUM(pp.cantidad * pp.precio), 0) - p.monto_pagado) AS saldo_pendiente,
            GROUP_CONCAT(CONCAT(pr.nombre, ' (', pp.cantidad, ' ', pp.tipo, ' x $', pp.precio, ')') SEPARATOR ', ') AS productos
        FROM pedidos p
        JOIN clientes c ON p.id_cliente = c.id
        LEFT JOIN pedido_productos pp ON p.id = pp.id_pedido
        LEFT JOIN productos pr ON pp.id_producto = pr.id
        GROUP BY p.id
        ORDER BY p.id DESC
        LIMIT 10
    ");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        // Crear un pedido nuevo
        public function guardar($id_cliente, $descripcion, $productos, $kilos, $unidades, $precios, $monto_pagado, $pagado, $metodo_pago, $fecha_entrega)
        {
        require_once __DIR__ . '/../Database/Database.php';
        $db = new Database();
        $pdo = $db->getConnection();
        

        // Normalizar valores
        $monto_pagado = trim($monto_pagado);
        if ($monto_pagado === '' || !is_numeric($monto_pagado)) {
        $monto_pagado = 0.00;
        }

        $pagado = $pagado ? 1 : 0;

        if (empty($fecha_entrega)) {
        $fecha_entrega = date('Y-m-d'); // si no se pasa fecha, usa la de hoy
        }



        try {
            $pdo->beginTransaction();

            // Insertar pedido
            $stmt = $pdo->prepare("INSERT INTO pedidos (id_cliente, descripcion, monto_pagado, metodo_pago, pagado, fecha_entrega) VALUES (?, ?, ?, ?, ?,?)");
            $stmt->execute([$id_cliente, $descripcion, $monto_pagado, $metodo_pago, $pagado,$fecha_entrega ]);
            $id_pedido = $pdo->lastInsertId();

            // Insertar productos del pedido
            $stmtProd = $pdo->prepare("INSERT INTO pedido_productos (id_pedido, id_producto, cantidad, precio, tipo) VALUES (?, ?, ?, ?, ?)");
            foreach ($productos as $id_prod) {
            $tipo = isset($kilos[$id_prod]) ? 'kilo' : 'unidad';
            $cantidad = $tipo === 'kilo' ? $kilos[$id_prod] : $unidades[$id_prod];

            // Validar precio   
            $precio = isset($precios[$id_prod]) && is_numeric($precios[$id_prod]) ? $precios[$id_prod] : 0.00;

            $stmtProd->execute([$id_pedido, $id_prod, $cantidad, $precio, $tipo]);
            }


            $pdo->commit();
            return $id_pedido;

            } catch (Exception $e) {
                $pdo->rollBack();
                die("Error al guardar pedido: " . $e->getMessage());
            }
        }

        // Obtener un pedido por ID
        public function obtenerPedidoPorId($id)
        {
            require_once __DIR__ . '/../Database/Database.php';
            $db = new Database();
            $pdo = $db->getConnection();

            $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Actualizar un pedido existente
        public function actualizar($id, $id_cliente, $descripcion, $kilos, $precio_por_kilo, $monto_pagado, $pagado, $metodo_pago, $fecha_entrega)
        {
            require_once __DIR__ . '/../Database/Database.php';
            $db = new Database();
            $pdo = $db->getConnection();

            $stmt = $pdo->prepare(
                "UPDATE pedidos SET 
                    id_cliente = ?, 
                    descripcion = ?, 
                    kilos = ?, 
                    precio_por_kilo = ?, 
                    monto_pagado = ?, 
                    pagado = ?, 
                    metodo_pago = ? ,
                    fecha_entrega= ?
                WHERE id = ?"
            );

            return $stmt->execute([$id_cliente, $descripcion, $kilos, $precio_por_kilo, $monto_pagado, $pagado, $metodo_pago, $fecha_entrega, $id]);
        }

        // Borrar un pedido
        public function borrar($id)
        {
            require_once __DIR__ . '/../Database/Database.php';
            $db = new Database();
            $pdo = $db->getConnection();

            $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id = ?");
            return $stmt->execute([$id]);
        }
    }
