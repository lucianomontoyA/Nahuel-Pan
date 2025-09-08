<?php

class Producto
{
    public function obtenerTodos()
    {
        require_once __DIR__ . '/../Database/Database.php';
        $db = new Database();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM productos ORDER BY nombre ASC");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id)
    {
        require_once __DIR__ . '/../Database/Database.php';
        $db = new Database();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardar($nombre, $precio)
    {
        require_once __DIR__ . '/../Database/Database.php';
        $db = new Database();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("INSERT INTO productos (nombre, precio) VALUES (?, ?)");
        return $stmt->execute([$nombre, $precio]);
    }

    public function actualizar($id, $nombre, $precio)
    {
        require_once __DIR__ . '/../Database/Database.php';
        $db = new Database();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, precio = ? WHERE id = ?");
        return $stmt->execute([$nombre, $precio, $id]);
    }
}
