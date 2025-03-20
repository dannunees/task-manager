<?php
$servername = "db";  // Usamos o nome do serviço 'db' no docker-compose
$username = "root";
$password = "rootpassword";
$dbname = "task_manager";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Função para retornar a lista de tarefas
function getTasks($conn, $id = null) {
    if ($id) {
        // Buscar tarefa específica
        $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $task = $result->fetch_assoc();
        return $task ? $task : null; // Retorna a tarefa ou null se não existir
    } else {
        // Retornar todas as tarefas
        $sql = "SELECT * FROM tasks";
        $result = $conn->query($sql);
        $tasks = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $tasks[] = $row;
            }
        }
        return $tasks;
    }
}

// Função para criar uma tarefa
function createTask($conn, $title, $description, $status) {
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $status);
    $stmt->execute();
    $stmt->close();
}

// Função para atualizar uma tarefa
function updateTask($conn, $id, $title, $description, $status) {
    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("sssi", $title, $description, $status, $id);
    $stmt->execute();
    $stmt->close();
}

// Função para excluir uma tarefa
function deleteTask($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Verifica o tipo de requisição e executa a ação adequada
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        // Se o id estiver presente, retornar a tarefa específica
        echo json_encode(getTasks($conn, $_GET['id']));
    } else {
        // Caso contrário, retornar todas as tarefas
        echo json_encode(getTasks($conn));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    createTask($conn, $data['title'], $data['description'], $data['status']);
    echo json_encode(["status" => "success", "message" => "Tarefa criada"]);
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $id = $_GET['id'];
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['title']) && isset($data['description']) && isset($data['status'])) {
        updateTask($conn, $id, $data['title'], $data['description'], $data['status']);
        echo json_encode(["status" => "success", "message" => "Tarefa atualizada"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Dados incompletos"]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];
    deleteTask($conn, $id);
    echo json_encode(["status" => "success", "message" => "Tarefa excluída"]);
}

$conn->close();
?>
