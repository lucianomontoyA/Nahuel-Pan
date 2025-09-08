        <?php
        require_once __DIR__ . '/../Models/Cliente.php';                // Carga el modelo Cliente (para interactuar con la base de datos)

        class ClientesController                                        // Controlador que maneja la lógica de clientes
        {
            private $cliente;                                           // Propiedad privada para almacenar la instancia del modelo

            public function __construct()                               // Constructor: se ejecuta al crear un objeto de esta clase
                {
                $this->cliente = new Cliente();                         // Crea un nuevo objeto Cliente (modelo) para usar sus métodos
                }

            public function listar()                                    // Método que obtiene y muestra los últimos 10 clientes
                {
                $clientes = $this->cliente->obtenerTodos();             // Llama al modelo para traer los clientes desde la base de datos
                require __DIR__ . '/../Views/Clientes/listar.php';       // Carga la vista listar.php (recibe la variable $clientes)
                }

            public function crear()                                     // Método que maneja la creación de un nuevo cliente
                {
                // Verifica que el formulario envió todos los campos esperados
                    if (isset($_POST['nombre'], $_POST['apellido'], $_POST['telefono'], $_POST['email'], $_POST['direccion'])) {

                    // 1. Asigna y limpia datos (trim elimina espacios extra)
                    $nombre    = trim($_POST['nombre']);                
                    $apellido  = trim($_POST['apellido']);
                    $telefono  = trim($_POST['telefono']);
                    $telefono = str_replace(' ', '', $telefono); // Quita cualquier espacio interno
                    $email     = trim($_POST['email']);
                    $direccion = trim($_POST['direccion']);

                    // 2. Validación de campos obligatorios
                    if (empty($nombre) || empty($apellido) || empty($email) || empty($direccion)) {
                        die("Error: Todos los campos obligatorios deben completarse.");    // Si falta algo, se detiene
                    }

                    // 3. Validar que nombre y apellido contengan solo letras y espacios
                    if (!preg_match('/^[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]+$/', $nombre)) {
                        die("Error: El nombre solo puede contener letras y espacios.");     // Si hay números u otros símbolos, error
                    }
                    if (!preg_match('/^[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]+$/', $apellido)) {
                        die("Error: El apellido solo puede contener letras y espacios.");
                    }

                    // 4. Validar formato de email usando el filtro interno de PHP
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        die("Error: El email no es válido.");
                    }

                  // 5. Validar teléfono obligatorio (solo números, máximo 15 dígitos)
                    if (empty($telefono) || !preg_match('/^\d{1,15}$/', $telefono)) {
                    die("Error: El teléfono es obligatorio y solo puede contener números (máximo 15).");
                    }

                    // 6. Si todo está bien, llamar al modelo para guardar en base de datos
                    if ($this->cliente->guardar($nombre, $apellido, $telefono, $email, $direccion)) {
                        header('Location: ../Views/Clientes/index.php');  // Redirige a la vista principal
                        exit;                                             // Detiene ejecución para evitar errores al recargar
                    } else {
                        die("Error: No se pudo guardar el cliente en la base de datos.");    // Si falla el INSERT
                    }
                    } else {
                    echo "Faltan datos obligatorios para crear el cliente.";                // Si el formulario no envía todos los datos
                }
            
            }
            public function editar($id)  // Método para editar un cliente existente
                {
                // 1. Traer los datos actuales del cliente por su ID
                $clienteActual = $this->cliente->obtenerPorId($id); // Necesitás crear este método en el modelo

                if (!$clienteActual) {
                die("Error: Cliente no encontrado.");
                }

                // 2. Verificar si el formulario fue enviado con POST
                if (isset($_POST['nombre'], $_POST['apellido'], $_POST['telefono'], $_POST['email'], $_POST['direccion']))
                    {
                    $nombre    = trim($_POST['nombre']);
                    $apellido  = trim($_POST['apellido']);
                    $telefono  = trim($_POST['telefono']);
                    $telefono = str_replace(' ', '', $telefono); // Quita cualquier espacio interno
                    $email     = trim($_POST['email']);
                    $direccion = trim($_POST['direccion']);

                    // 3. Validaciones (igual que en crear)
                    if (empty($nombre) || empty($apellido) || empty($email) || empty($direccion)) {
                    die("Error: Todos los campos obligatorios deben completarse.");
                    }
                    if (!preg_match('/^[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]+$/', $nombre)) {
                    die("Error: El nombre solo puede contener letras y espacios.");
                    }
                    if (!preg_match('/^[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]+$/', $apellido)) {
                    die("Error: El apellido solo puede contener letras y espacios.");
                    }
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    die("Error: El email no es válido.");
                    }
                  if (empty($telefono) || !preg_match('/^\d{1,15}$/', $telefono)) {
    die("Error: El teléfono es obligatorio y solo puede contener números (máximo 15).");
}

                    // 4. Llamar al modelo para actualizar el cliente
                    if ($this->cliente->actualizar($id, $nombre, $apellido, $telefono, $email, $direccion)) {
                    header('Location: ../Views/Clientes/index.php'); // Redirigir al listado
                    exit;
                    } else {
                    die("Error: No se pudo actualizar el cliente.");
                    }
                }

                // 5. Cargar la vista con los datos actuales del cliente
            require __DIR__ . '/../Views/Clientes/editar.php';
            }

            
            public function obtenerClientePorId($id) {
                return $this->cliente->obtenerPorId($id);
            }

        

            
        }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
        $controller = new ClientesController();
        switch ($_POST['accion']) {
            case 'crear':                     // <-- AGREGAR ESTE CASO
                $controller->crear();
                break;
            case 'editar':                  // <-- AGREGAR ESTE CASO
                if (isset($_POST['id'])) {
                    $controller->editar($_POST['id']);
                }
                break;
        }
    }
