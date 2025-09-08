<?php
require_once __DIR__ . '/../../includes/header.php';        // Carga el encabezado HTML común a todas las páginas (menu, estilos, etc.)
?>

<!-- Botón que rdiecciona al Inicio -->
<ul>
    <li><a href="/index.php" id="btn-inicio">Inicio</a></li>
</ul>


<div class="container-clientes">                            <!-- Contenedor principal del formulario, usado para dar estilo -->
    <h1>Crear Cliente</h1>                                  <!-- Título principal de la página -->

    <form action="../../Controllers/ClientesController.php" method="POST" class="formulario-crear"> 
                                                             <!-- Formulario que envía los datos al controlador de clientes -->
                                                             <!-- Usa método POST para enviar datos de forma segura -->
                                                             <!-- El atributo 'action' indica a qué archivo se enviará el formulario -->

        <div class="campo-form">                                                 <!-- Campo de texto para ingresar el nombre del cliente -->
            <label for="nombre">Nombre:</label>                              <!-- Etiqueta asociada al input -->
            <input type="text" id="nombre" name="nombre" required>      <!-- Campo de entrada obligatorio -->
        </div>

        <div class="campo-form">                                                <!-- Campo de texto para el apellido -->
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>
        </div>

        <div class="campo-form">                                    <!-- Campo para ingresar el teléfono -->
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" required>      <!-- Este campo no es obligatorio -->
       
        </div>

        <div class="campo-form">                                     <!-- Campo para el email del cliente -->
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>       <!-- Validación HTML de tipo email -->
        </div>

        <div class="campo-form">                                     <!-- Campo para la dirección del cliente -->
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>
        </div>

        <div class="botonera-clientes">                      <!-- Sección de botones de acción -->
            <button type="submit" name="accion" value="crear" class="boton">Guardar</button> 
                                                             <!-- Botón para enviar el formulario al controlador -->
                                                             <!-- Se envía el campo 'accion' con valor 'crear' para que el controlador sepa qué hacer -->

            <a href="index.php" class="boton">Volver</a>     <!-- Enlace para volver a la lista de clientes -->
        </div>

    </form>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';        // Cierra el documento HTML incluyendo el pie de página común
?>
