<?php
// Views/Clientes/index.php

// Incluye el controlador ClientesController, que contiene la lógica para manejar clientes
require_once __DIR__ . '/../../Controllers/ClientesController.php';

// Crea una instancia del controlador ClientesController
$controller = new ClientesController();

// Ejecuta el método listar() para obtener los datos y cargar la vista correspondiente
$controller->listar();

