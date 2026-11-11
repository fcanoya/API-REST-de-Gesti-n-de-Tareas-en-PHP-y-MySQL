<?php
include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET': // Consultar
        $stmt = $db->prepare("SELECT * FROM tareas");
        $stmt->execute();
        $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tareas);
        break;

    case 'POST': // Crear
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $db->prepare("INSERT INTO tareas (usuario_id, titulo) VALUES (:usuario_id, :titulo)");
        $stmt->bindParam(":usuario_id", $data->usuario_id);
        $stmt->bindParam(":titulo", $data->titulo);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(["message" => "Tarea creada."]);
        break;

    case 'PUT': // Actualizar
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $db->prepare("UPDATE tareas SET titulo = :titulo, completado = :completado WHERE id = :id");
        $stmt->bindParam(":titulo", $data->titulo);
        $stmt->bindParam(":completado", $data->completado);
        $stmt->bindParam(":id", $data->id);
        $stmt->execute();
        echo json_encode(["message" => "Tarea actualizada."]);
        break;

    case 'DELETE': // Eliminar
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $db->prepare("DELETE FROM tareas WHERE id = :id");
        $stmt->bindParam(":id", $data->id);
        $stmt->execute();
        echo json_encode(["message" => "Tarea eliminada."]);
        break;
}