<?php

class Gasto
{
    // Obtener todos los gastos
    public function obtenerTodos($limit = 20)
    {
        require_once __DIR__ . '/../Database/Database.php';
        $db = new Database();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM gastos ORDER BY fecha_pago DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Guardar un gasto nuevo
    public function guardar($categoria, $monto, $descripcion = null, $fecha_pago = null)
    {
        require_once __DIR__ . '/../Database/Database.php';
        $db = new Database();
        $pdo = $db->getConnection();

        // Si no te pasan fecha_pago, se usará la default del campo
        $sql = "INSERT INTO gastos (categoria, monto, descripcion, fecha_pago)
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$categoria, $monto, $descripcion, $fecha_pago]);
    }

    // Actualizar gasto existente
    public function actualizar($id, $categoria, $monto, $descripcion = null, $fecha_pago = null)
    {
        require_once __DIR__ . '/../Database/Database.php';
        $db = new Database();
        $pdo = $db->getConnection();

        $sql = "UPDATE gastos
                   SET categoria = ?, monto = ?, descripcion = ?, fecha_pago = ?
                 WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$categoria, $monto, $descripcion, $fecha_pago, $id]);
    }

    // Eliminar un gasto
    public function eliminar($id)
    {
        require_once __DIR__ . '/../Database/Database.php';
        $db = new Database();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("DELETE FROM gastos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Obtener un gasto por ID
    public function obtenerPorId($id)
    {
        require_once __DIR__ . '/../Database/Database.php';
        $db = new Database();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM gastos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
