<?php
// Models/CierreDiario.php
require_once __DIR__ . '/Database.php'; // Tu conexión PDO

class CierreDiario
{
    private $db;

    public function __construct()
    {
        $db = new Database();
        $this->db = $db->getConnection(); // Ahora sí tenemos PDO
    }

    // Crear un nuevo cierre diario
    public function guardar($fecha, $total_vendido, $total_cobrado, $total_resto, $total_gastos, $saldo_final)
    {
        $sql = "INSERT INTO cierres_diarios 
                (fecha, total_vendido, total_cobrado, total_resto, total_gastos, saldo_final)
                VALUES (:fecha, :total_vendido, :total_cobrado, :total_resto, :total_gastos, :saldo_final)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'fecha' => $fecha,
            'total_vendido' => $total_vendido,
            'total_cobrado' => $total_cobrado,
            'total_resto' => $total_resto,
            'total_gastos' => $total_gastos,
            'saldo_final' => $saldo_final
        ]);
    }

    // Obtener todos los cierres diarios
    public function obtenerTodos()
    {
        $sql = "SELECT * FROM cierres_diarios ORDER BY fecha DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un cierre por ID
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM cierres_diarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar un cierre diario
    public function actualizar($id, $fecha, $total_vendido, $total_cobrado, $total_resto, $total_gastos, $saldo_final)
    {
        $sql = "UPDATE cierres_diarios 
                SET fecha = :fecha,
                    total_vendido = :total_vendido,
                    total_cobrado = :total_cobrado,
                    total_resto = :total_resto,
                    total_gastos = :total_gastos,
                    saldo_final = :saldo_final
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'fecha' => $fecha,
            'total_vendido' => $total_vendido,
            'total_cobrado' => $total_cobrado,
            'total_resto' => $total_resto,
            'total_gastos' => $total_gastos,
            'saldo_final' => $saldo_final,
            'id' => $id
        ]);
    }

    // Eliminar un cierre diario
    public function eliminar($id)
    {
        $sql = "DELETE FROM cierres_diarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Obtener cierres por fecha
    public function obtenerPorFecha($fecha)
    {
        $sql = "SELECT * FROM cierres_diarios WHERE fecha = :fecha";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['fecha' => $fecha]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
