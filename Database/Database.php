<?php
class Database                                                  // Clase encargada de manejar la conexión a la base de datos mediante PDO
{
    private $host = 'localhost';                                // Dirección del servidor de base de datos (localhost en entorno local)
    private $dbname = 'nahuel_pan';                             // Nombre de la base de datos a la que nos vamos a conectar
    private $username = 'root';                                 // Usuario de acceso a MySQL (por defecto en XAMPP es 'root')
    private $password = 'root';                                 // Contraseña del usuario (en tu caso, también 'root')
    private $pdo;                                               // Variable privada que va a contener el objeto PDO una vez conectado

    public function __construct()                               // Método constructor que se ejecuta automáticamente al instanciar la clase
    {
        try {
            $this->pdo = new PDO(                               // Se crea una nueva instancia de PDO para iniciar la conexión con MySQL
                "mysql:host={$this->host};                      
                 dbname={$this->dbname};                        
                 charset=utf8",                                 // Codificación de caracteres para evitar problemas con acentos y símbolos
                $this->username,                                // Usuario para autenticar
                $this->password                                 // Contraseña para autenticar
            );

            $this->pdo->setAttribute(                           // Configura el comportamiento del objeto PDO
                PDO::ATTR_ERRMODE,                              // Atributo: tipo de manejo de errores
                PDO::ERRMODE_EXCEPTION                         // Valor: lanzar excepciones cuando haya errores (más fácil de capturar y depurar)
            );
        } catch (PDOException $e) {                             // Captura cualquier error al intentar conectarse a la base
            die("Error en la conexión: " . $e->getMessage());   // Termina el script y muestra el mensaje del error de conexión
        }
    }

    public function getConnection()                             // Método público que devuelve la instancia PDO
    {
        return $this->pdo;                                      // Retorna la conexión PDO para que otras clases puedan usarla
    }
}
