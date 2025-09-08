<?php
// Views/Pedidos/index.php

// Incluye el controlador PedidosController, que contiene la lógica para manejar pedidos
require_once __DIR__ . '/../../Controllers/PedidosController.php';

// Crea una instancia del controlador PedidosController
$controller = new PedidosController();

// Ejecuta el método listar() para obtener los datos y cargar la vista correspondiente
$controller->listar();
        