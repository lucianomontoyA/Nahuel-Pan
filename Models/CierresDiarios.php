<?php
// Models/CierreDiario.php
require_once __DIR__ . '/../Database/Database.php';

class CierreDiario
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    // Obtener todos los cierres
    public function obtenerTodos($limit = 50)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cierres_diarios ORDER BY fecha DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener cierres por fecha exacta
    public function obtenerPorFecha($fecha)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cierres_diarios WHERE fecha = ?");
        $stmt->execute([$fecha]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar o insertar el cierre del día
    public function actualizarCierreDelDia()
    {
        $hoy = date('Y-m-d');

        // Totales de pedidos del día
        $stmt = $this->pdo->prepare("
            SELECT 
                IFNULL(SUM(monto_pagado),0) AS total_vendido
            FROM pedidos
            WHERE DATE(fecha_entrega) = ?
        ");
        $stmt->execute([$hoy]);
        $totalesPedido = $stmt->fetch(PDO::FETCH_ASSOC);

        $total_vendido = $totalesPedido['total_vendido'];
        $total_cobrado = $total_vendido; // asumimos que todo pagado se cobró
        $total_resto = 0; // si todo está pagado
       
        // Totales de gastos del día
        $stmt = $this->pdo->prepare("
            SELECT IFNULL(SUM(monto),0) AS total_gastos
            FROM gastos
            WHERE DATE(fecha_pago) = ?
        ");
        $stmt->execute([$hoy]);
        $totalesGastos = $stmt->fetch(PDO::FETCH_ASSOC);

        $total_gastos = $totalesGastos['total_gastos'];
        $saldo_final = $total_cobrado - $total_gastos;

        // Revisar si ya existe cierre
        $stmt = $this->pdo->prepare("SELECT id FROM cierres_diarios WHERE fecha = ?");
        $stmt->execute([$hoy]);
        $existe = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existe) {
            // Actualizar cierre existente
            $stmt = $this->pdo->prepare("
                UPDATE cierres_diarios
                SET total_vendido = ?, total_cobrado = ?, total_resto = ?, total_gastos = ?, saldo_final = ?
                WHERE fecha = ?
            ");
            $stmt->execute([$total_vendido, $total_cobrado, $total_resto, $total_gastos, $saldo_final, $hoy]);
        } else {
            // Insertar nuevo cierre
            $stmt = $this->pdo->prepare("
                INSERT INTO cierres_diarios (fecha, total_vendido, total_cobrado, total_resto, total_gastos, saldo_final)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$hoy, $total_vendido, $total_cobrado, $total_resto, $total_gastos, $saldo_final]);
        }
    }
}
