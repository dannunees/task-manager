<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Gerenciador de Tarefas</h1>
        <form id="taskForm">
            <input type="text" id="taskTitle" placeholder="Título da Tarefa" class="form-control mb-4">
            <textarea id="taskDescription" placeholder="Descrição" class="form-control mb-4"></textarea>
            <input type="hidden" id="taskId">
            <button type="submit" class="btn btn-primary">Adicionar Tarefa</button>
        </form>

        <h3 class="mt-4">Lista de Tarefas</h3>
        <table class="table mt-4" id="taskList">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Título</th>
                    <th class="text-center">Descrição</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>

        function loadTasks() {
            fetch("/api.php")
                .then(response => response.json())
                .then(tasks => {
                    const tbody = document.querySelector("#taskList tbody");
                    tbody.innerHTML = '';
                    tasks.forEach(task => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td class="text-center">${task.id}</td>
                            <td class="text-center">${task.title}</td>
                            <td class="text-center">${task.description}</td>
                            <td class="text-center">${task.status}</td>
                            <td class="text-center">
                                <button class="btn btn-warning" onclick="editTask(${task.id})">Editar</button>
                                <button class="btn btn-success" onclick="changeStatus(${task.id})">Alterar Status</button>
                                <button class="btn btn-danger" onclick="deleteTask(${task.id})">Excluir</button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => console.error('Erro ao carregar as tarefas:', error));
        }


        function deleteTask(id) {
            fetch(`/api.php?id=${id}`, { method: "DELETE" })
                .then(() => loadTasks())
                .catch(error => console.error('Erro ao excluir tarefa:', error));
        }


        function editTask(id) {
            fetch(`/api.php?id=${id}`)
                .then(response => response.json())
                .then(task => {
                    document.getElementById("taskTitle").value = task.title;
                    document.getElementById("taskDescription").value = task.description;
                    document.getElementById("taskId").value = task.id;
                    document.querySelector("button[type='submit']").textContent = 'Salvar Alterações';
                })
                .catch(error => console.error('Erro ao carregar tarefa para edição:', error));
        }


        function changeStatus(id) {

            fetch(`/api.php?id=${id}`)
                .then(response => response.json())
                .then(task => {
                    let newStatus;

                    if (task.status === "pendente") {
                        newStatus = "em andamento";
                    } else if (task.status === "em andamento") {
                        newStatus = "concluída";
                    } else if (task.status === "concluída") {
                        newStatus = "pendente";
                    }

                    fetch(`/api.php?id=${id}`, {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            title: task.title,    
                            description: task.description,  
                            status: newStatus      
                        })
                    })
                    .then(response => response.json())
                    .then(() => {
                        loadTasks();
                    })
                    .catch(error => console.error('Erro ao atualizar status da tarefa:', error));
                })
                .catch(error => console.error('Erro ao carregar tarefa para alteração de status:', error));
        }

        document.getElementById("taskForm").addEventListener("submit", function (e) {
            e.preventDefault();

            const id = document.getElementById("taskId").value; 
            const title = document.getElementById("taskTitle").value;
            const description = document.getElementById("taskDescription").value;

            if (id) { 
                fetch(`/api.php?id=${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        title: title,
                        description: description,
                        status: "pendente"
                    })
                })
                    .then(() => {
                        loadTasks();
                        document.getElementById("taskForm").reset();
                        document.getElementById("taskId").value = ''; 
                        document.querySelector("button[type='submit']").textContent = 'Adicionar Tarefa';
                    })
                    .catch(error => console.error('Erro ao atualizar tarefa:', error));
            } else { 
                fetch("/api.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        title: title,
                        description: description,
                        status: "pendente"
                    })
                })
                    .then(() => {
                        loadTasks();
                        document.getElementById("taskForm").reset();
                    })
                    .catch(error => console.error('Erro ao criar tarefa:', error));
            }
        });

        loadTasks();
    </script>
</body>
</html>
