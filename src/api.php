<?php
$servername = "db";
$username = "root";
$password = "rootpassword";
$dbname = "task_manager";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getTasks($conn, $id = null) {
    if ($id) {
        $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $task = $result->fetch_assoc();
        return $task ? $task : null;
    } else {
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

function createTask($conn, $title, $description, $status) {
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $status);
    $stmt->execute();
    $stmt->close();
}

function updateTask($conn, $id, $title, $description, $status) {
    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("sssi", $title, $description, $status, $id);
    $stmt->execute();
    $stmt->close();
}

function deleteTask($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        echo json_encode(getTasks($conn, $_GET['id']));
    } else {
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
