<!DOCTYPE html>                                               <!-- Tipo de documento: HTML5 -->
<html lang="es">                                              <!-- Idioma principal del sitio -->

<head>
    <meta charset="UTF-8">                                    <!-- Codificación de caracteres: UTF-8 (soporta tildes y ñ) -->
    <title>Panel Nahuel Pan</title>                           <!-- Título de la pestaña del navegador -->
    <link rel="stylesheet" href="/css/style.css">             <!-- Enlace al archivo de estilos CSS -->
</head>

<body>




<!-- Botón que rdiecciona al Inicio -->
<ul>
    <li><a href="/index.php" id="btn-inicio">Inicio</a></li>
</ul>



<?php
require_once __DIR__ . '/includes/header.php';                // Incluye el encabezado común del sitio (menú, logo, etc.)
?>

<h1>Bienvenido al sistema de gestión de Nahuel Pan</h1>       <!-- Título principal visible de la página -->

<ul>                                                          <!-- Lista desordenada con enlaces de navegación -->
    <li><a href="Views/Clientes/index.php">Gestión de Clientes</a></li>              <!-- Enlace a la gestión de clientes -->
    <li><a href="Views/Pedidos/index.php">Gestión de Pedidos</a></li>              <!-- Enlace a la gestión de pedidos -->
    <li><a href="Views/pagos/listar.php">Gestión de Pagos (borrar por que es lo mismo  un "pago" un "gasto")</a></li>                  <!-- Enlace a la gestión de pagos -->
    <li><a href="Views/gastos/index.php">Gastos</a></li>                <!-- Enlace al control de gastos -->
    <li><a href="Views/inventario/index.php">Inventario</a></li>                   <!-- Enlace al inventario -->
    <li><a href="Views/CierresDiarios/index.php">Cierres Diarios</a></li>         <!-- Enlace al cierre diario de caja -->
</ul>

<?php
require_once __DIR__ . '/includes/footer.php';                // Incluye el pie de página común del sitio
?>
