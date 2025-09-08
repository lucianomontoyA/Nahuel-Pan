<?php

class Cliente   // Modelo que representa a un cliente y se conecta con la base de datos
{
    // Obtiene los últimos 10 clientes registrados
    public function obtenerTodos()
    {
        require_once __DIR__ . '/../Database/Database.php';   // Carga la clase Database para la conexión PDO

        $db = new Database();                                 // Crea instancia de Database (conecta automáticamente)
        $pdo = $db->getConnection();                          // Obtiene la conexión PDO

        // Consulta SQL: selecciona todos los clientes ordenados por ID descendente (últimos primero)
        $stmt = $pdo->prepare("SELECT * FROM clientes ORDER BY id DESC LIMIT 10");
        $stmt->execute();                                     // Ejecuta la consulta

        // Devuelve los resultados como array asociativo (clave = nombre de columna)
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Guarda un nuevo cliente en la base de datos
    public function guardar($nombre, $apellido, $telefono, $email, $direccion)
    {
        require_once __DIR__ . '/../Database/Database.php';   // Carga la clase Database para la conexión PDO

        $db = new Database();                                 // Crea instancia de Database
        $pdo = $db->getConnection();                          // Obtiene la conexión PDO

        // Inserta el cliente usando placeholders para evitar inyección SQL
        $stmt = $pdo->prepare(
            "INSERT INTO clientes (nombre, apellido, telefono, email, direccion)
             VALUES (?, ?, ?, ?, ?)"
        );

        // Ejecuta la consulta pasando los valores en un array
        return $stmt->execute([
            $nombre,    // parámetro 1: nombre
            $apellido,  // parámetro 2: apellido
            $telefono,  // parámetro 3: teléfono (puede ser vacío)
            $email,     // parámetro 4: email
            $direccion  // parámetro 5: dirección
        ]);
    }
    public function obtenerPorId($id)
    {
    require_once __DIR__ . '/../Database/Database.php';
    $db = new Database();
    $pdo = $db->getConnection();
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function actualizar($id, $nombre, $apellido, $telefono, $email, $direccion)
    {
    require_once __DIR__ . '/../Database/Database.php';
    $db = new Database();
    $pdo = $db->getConnection();
    $stmt = $pdo->prepare("UPDATE clientes SET nombre = ?, apellido = ?, telefono = ?, email = ?, direccion = ? WHERE id = ?");
    return $stmt->execute([$nombre, $apellido, $telefono, $email, $direccion, $id]);
    }


}
