<?php 
require_once __DIR__ . '/../../includes/header.php';             // Incluye cabecera común del sitio (HTML, CSS, navegación)
?>
<!-- Botón que rdiecciona al Inicio -->
<ul>
    <li><a href="/index.php" id="btn-inicio">Inicio</a></li>
</ul>


<div class="container-clientes">                                 <!-- Contenedor principal de la vista -->

    <h1>Clientes</h1>                                            <!-- Título visible de la sección -->

    <div class="botonera-clientes">                              <!-- Botonera con acciones rápidas -->
        <a href="crear.php" class="boton">Crear Cliente</a>      <!-- Botón que lleva al formulario para crear un cliente -->
        <a href="index.php" class="boton">Listar Clientes</a>    <!-- Botón que recarga la lista de clientes -->
      
    </div>

    <table>                                                      <!-- Tabla HTML que estructura los datos -->
        <thead>                                                  <!-- Encabezado de la tabla -->
            <tr>
                <th>ID</th>                                      <!-- ID del cliente -->
                <th>Nombre</th>                                  <!-- Nombre del cliente -->
                <th>Apellido</th>                                <!-- Apellido del cliente -->
                <th>Teléfono</th>                                <!-- Teléfono de contacto -->
                <th>Email</th>                                   <!-- Correo electrónico -->
                <th>Dirección</th>                               <!-- Domicilio -->
                <th>Acciones</th>                                <!-- Botones de editar/borrar -->
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clientes)): ?>                                     <!-- Si hay clientes cargados -->
                <?php foreach(array_slice($clientes, 0, 10) as $c): ?>           <!-- Recorre los 10 primeros clientes -->
                    <tr>
                        <td><?= $c['id'] ?></td>                                        <!-- Muestra el ID (número entero) -->
                        <td><?= htmlspecialchars($c['nombre']) ?></td>                  <!-- Muestra el nombre con seguridad XSS -->
                        <td><?= htmlspecialchars($c['apellido']) ?></td>                <!-- Apellido con seguridad XSS -->
                        <td><?= htmlspecialchars($c['telefono']) ?></td>                <!-- Teléfono con seguridad XSS -->
                        <td><?= htmlspecialchars($c['email']) ?></td>                   <!-- Email con seguridad XSS -->
                        <td><?= htmlspecialchars($c['direccion']) ?></td>               <!-- Dirección con seguridad XSS -->
                        <td>                                                            <!-- Celda para acciones -->
                            <a href="editar.php?id=<?= $c['id'] ?>">Editar</a>          <!-- Link de edición, pasa ID por GET -->
                            |                                                           <!-- Separador visual -->
                            <a href="borrar.php?id=<?= $c['id'] ?>" onclick="return confirm('¿Seguro que querés borrar este cliente?')">Borrar</a>   <!-- Confirm previene borrado accidental -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>                                        <!-- Si no hay clientes -->
                <tr>
                    <td colspan="7" class="sin-clientes">No hay clientes cargados aún.</td>  <!-- Mensaje cuando no hay registros -->
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php 
require_once __DIR__ . '/../../includes/footer.php';             // Incluye pie de página y cierre de etiquetas HTML
?>
