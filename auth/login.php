<?php
// Configuración de cabeceras HTTP para permitir solicitudes externas y definir respuesta JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir conexión a la base de datos
include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Obtener el cuerpo de la solicitud JSON
$data = json_decode(file_get_contents("php://input"));

// Validar que los campos no estén vacíos
if (!empty($data->email) && !empty($data->password)) {
    
    // Consulta para buscar el usuario por email
    $query = "SELECT id, nombre, email, password FROM usuarios WHERE email = :email LIMIT 1";
    $stmt = $db->prepare($query);
    
    // Limpiar entrada de datos
    $email = htmlspecialchars(strip_tags($data->email));
    $stmt->bindParam(":email", $email);
    
    $stmt->execute();

    // Verificar si el usuario existe
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificación del hash de la contraseña
        if (password_verify($data->password, $row['password'])) {
            
            // Respuesta exitosa (200 OK)
            http_response_code(200);
            echo json_encode([
                "message" => "Inicio de sesión exitoso.",
                "user" => [
                    "id" => $row['id'],
                    "nombre" => $row['nombre'],
                    "email" => $row['email']
                ]
            ]);
            
        } else {
            // Contraseña incorrecta (401 Unauthorized)
            http_response_code(401);
            echo json_encode(["message" => "Contraseña incorrecta."]);
        }
        
    } else {
        // Usuario no encontrado (404 Not Found)
        http_response_code(404);
        echo json_encode(["message" => "El usuario no existe."]);
    }
    
} else {
    // Datos incompletos (400 Bad Request)
    http_response_code(400);
    echo json_encode(["message" => "Datos incompletos. Se requiere 'email' y 'password'."]);
}