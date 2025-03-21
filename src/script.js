function fetchTasks() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'api.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            const taskList = document.getElementById('task-list');
            taskList.innerHTML = '';

            data.forEach(function (task) {
                const li = document.createElement('li');
                li.setAttribute('data-id', task.id);
                li.innerHTML = `
                    ${task.title} - ${task.status} 
                    <button class="edit-btn">Editar</button>
                    <button class="status-btn">${task.status === 'pendente' ? 'Concluir' : 'Reabrir'}</button>
                    <button class="delete-btn">Excluir</button>
                `;

                li.querySelector('.edit-btn').addEventListener('click', function () { openEditModal(task); });
                li.querySelector('.status-btn').addEventListener('click', function () { toggleStatus(task, li); });
                li.querySelector('.delete-btn').addEventListener('click', function () { deleteTask(task.id); });

                taskList.appendChild(li);
            });
        }
    };
    xhr.send();
}

document.getElementById('task-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'api.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            fetchTasks();
            document.getElementById('task-form').reset();
        }
    };
    xhr.send(JSON.stringify({ title: title, description: description, status: "pendente" }));
});

function deleteTask(taskId) {
    var xhr = new XMLHttpRequest();
    xhr.open('DELETE', 'api.php?id=' + taskId, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            fetchTasks();
        }
    };
    xhr.send();
}

function toggleStatus(task, li) {
    let newStatus;

    if (task.status === "pendente") {
        newStatus = "em andamento"; 
    } else if (task.status === "em andamento") {
        newStatus = "concluída"; 
    } else {
        newStatus = "pendente";
    }

    var xhr = new XMLHttpRequest();
    xhr.open('PUT', 'api.php?id=' + task.id, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            task.status = newStatus;
            li.querySelector('.status-btn').textContent = 
                newStatus === 'pendente' ? 'Concluir' : 
                newStatus === 'em andamento' ? 'Concluir' : 'Reabrir';
        }
    };
    xhr.send(JSON.stringify({
        title: task.title,
        description: task.description,
        status: newStatus
    }));
}

function openEditModal(task) {

    document.getElementById('title').value = task.title;
    document.getElementById('description').value = task.description;
    document.getElementById('task-form').setAttribute('data-edit-id', task.id);
    document.querySelector('button[type="submit"]').textContent = 'Salvar Alterações';
}

document.getElementById('task-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const taskId = document.getElementById('task-form').getAttribute('data-edit-id');
    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;

    if (taskId) {
        var xhr = new XMLHttpRequest();
        xhr.open('PUT', 'api.php?id=' + taskId, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                fetchTasks();
                document.getElementById('task-form').reset();
                document.getElementById('task-form').removeAttribute('data-edit-id'); 
                document.querySelector('button[type="submit"]').textContent = 'Adicionar Tarefa';
            }
        };
        xhr.send(JSON.stringify({
            title: title,
            description: description,
            status: "pendente"
        }));
    } else {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'api.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                fetchTasks();
                document.getElementById('task-form').reset();
            }
        };
        xhr.send(JSON.stringify({ title: title, description: description, status: "pendente" }));
    }
});

document.addEventListener('DOMContentLoaded', fetchTasks);
