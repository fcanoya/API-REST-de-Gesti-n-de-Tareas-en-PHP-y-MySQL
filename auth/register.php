<?php
// Encabezados obligatorios para recibir JSON y permitir peticiones API
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->nombre) && !empty($data->email) && !empty($data->password)) {
    $query = "INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)";
    $stmt = $db->prepare($query);

    // Encriptación de contraseña
    $password_hash = password_hash($data->password, PASSWORD_BCRYPT);

    $stmt->bindParam(":nombre", $data->nombre);
    $stmt->bindParam(":email", $data->email);
    $stmt->bindParam(":password", $password_hash);

    try {
        if($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Usuario registrado exitosamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "No se pudo registrar el usuario."]);
        }
    } catch (PDOException $e) {
        // Captura si el correo ya existe en la base de datos (clave UNIQUE)
        http_response_code(400);
        echo json_encode(["message" => "El correo ya está registrado.", "error" => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Datos incompletos."]);
}