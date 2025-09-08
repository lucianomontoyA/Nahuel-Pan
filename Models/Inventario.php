<?php

class Inventario
{
    // Obtiene todos los insumos (limit optional)
    public function obtenerTodos($limit = 20)
    {
        require_once __DIR__ . '/../Database/Database.php';
        $db = new Database();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM inventario ORDER BY id DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Guarda o actualiza un insumo
    public function guardar($nombre_insumo, $cantidad, $unidad, $observaciones)
    {
        require_once __DIR__ . '/../Database/Database.php';
        $db = new Database();
        $pdo = $db->getConnection();

        // Primero revisamos si el insumo ya existe
        $stmt = $pdo->prepare("SELECT id, cantidad FROM inventario WHERE nombre_insumo = ?");
        $stmt->execute([$nombre_insumo]);
        $insumo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($insumo) {
            // Si existe, sumamos la cantidad
            $nuevaCantidad = $insumo['cantidad'] + $cantidad;
            $updateStmt = $pdo->prepare(
                "UPDATE inventario SET cantidad = ?, unidad = ?, observaciones = ? WHERE id = ?"
            );
            return $updateStmt->execute([$nuevaCantidad, $unidad, $observaciones, $insumo['id']]);
        } else {
            // Si no existe, insertamos nuevo
            $insertStmt = $pdo->prepare(
                "INSERT INTO inventario (nombre_insumo, cantidad, unidad, observaciones)
                 VALUES (?, ?, ?, ?)"
            );
            return $insertStmt->execute([$nombre_insumo, $cantidad, $unidad, $observaciones]);
        }
    }

    // Elimina un insumo
    public function eliminar($id)
    {
        require_once __DIR__ . '/../Database/Database.php';
        $db = new Database();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("DELETE FROM inventario WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Obtiene un insumo por ID (para editar si hace falta)
    public function obtenerPorId($id)
    {
        require_once __DIR__ . '/../Database/Database.php';
        $db = new Database();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM inventario WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
 public function usarInsumos($id_pedido, $id_producto, $cantidad_vendida) {
    require_once __DIR__ . '/../Database/Database.php';
    $db = new Database();
    $pdo = $db->getConnection();

    // Traer insumos de la receta del producto
    $stmt = $pdo->prepare("SELECT id_insumo, cantidad_por_unidad FROM productos_insumos WHERE id_producto = ?");
    $stmt->execute([$id_producto]);
    $insumos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($insumos as $insumo) {
        $id_insumo = $insumo['id_insumo'];
        $cantidad_a_usar = $insumo['cantidad_por_unidad'] * $cantidad_vendida;

        // Validar stock
        $stmtCheck = $pdo->prepare("SELECT cantidad FROM inventario WHERE id = ?");
        $stmtCheck->execute([$id_insumo]);
        $current = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        if ($current['cantidad'] < $cantidad_a_usar) {
            throw new Exception("No hay suficiente insumo para el producto ID $id_producto (insumo ID $id_insumo)");
        }

        // Restar del inventario
        $stmtInv = $pdo->prepare("UPDATE inventario SET cantidad = cantidad - ? WHERE id = ?");
        $stmtInv->execute([$cantidad_a_usar, $id_insumo]);

        // Registrar en recetas_pedidos
        $stmtRec = $pdo->prepare("
            INSERT INTO recetas_pedidos (id_pedido, id_producto, id_insumo, cantidad_usada)
            VALUES (?, ?, ?, ?)
        ");
        $stmtRec->execute([$id_pedido, $id_producto, $id_insumo, $cantidad_a_usar]);

        // Registrar en movimientos_inventario
        $stmtHist = $pdo->prepare("
            INSERT INTO movimientos_inventario (id_pedido, id_producto, id_insumo, cantidad_usada)
            VALUES (?, ?, ?, ?)
        ");
        $stmtHist->execute([$id_pedido, $id_producto, $id_insumo, $cantidad_a_usar]);
    }
}


}
