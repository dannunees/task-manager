<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Gerenciador de Tarefas</h1>
        <form id="taskForm">
            <input type="text" id="taskTitle" placeholder="Título da Tarefa" class="form-control mb-4">
            <textarea id="taskDescription" placeholder="Descrição" class="form-control mb-4"></textarea>
            <button type="submit" class="btn btn-primary">Adicionar Tarefa</button>
        </form>

        <h3 class="mt-4">Lista de Tarefas</h3>
        <table class="table mt-4" id="taskList">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        // Função para carregar as tarefas
        function loadTasks() {
            $.get("http://localhost:9000/api.php", function(data) {
                const tasks = JSON.parse(data);
                let html = '';
                tasks.forEach(task => {
                    html += `
                        <tr>
                            <td>${task.id}</td>
                            <td>${task.title}</td>
                            <td>${task.description}</td>
                            <td>${task.status}</td>
                            <td>
                                <button class="btn btn-danger" onclick="deleteTask(${task.id})">Excluir</button>
                            </td>
                        </tr>
                    `;
                });
                $("#taskList tbody").html(html);
            });
        }

        // Função para excluir uma tarefa
        function deleteTask(id) {
            $.ajax({
                url: "http://localhost:9000/api.php?id=" + id,
                type: "DELETE",
                success: function() {
                    loadTasks();
                }
            });
        }

        // Enviar o formulário para criar uma tarefa
        $("#taskForm").on("submit", function(e) {
            e.preventDefault();

            const title = $("#taskTitle").val();
            const description = $("#taskDescription").val();

            $.ajax({
                url: "http://localhost:9000/api.php",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    title: title,
                    description: description,
                    status: "pendente"
                }),
                success: function() {
                    loadTasks();
                    $("#taskTitle").val('');
                    $("#taskDescription").val('');
                }
            });
        });

        // Carregar tarefas ao iniciar
        loadTasks();
    </script>
</body>
</html>
