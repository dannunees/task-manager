// Função para carregar as tarefas
function fetchTasks() {
    fetch('api.php')
        .then(response => response.json())
        .then(data => {
            const taskList = document.getElementById('task-list');
            taskList.innerHTML = '';

            data.forEach(task => {
                const li = document.createElement('li');
                li.setAttribute('data-id', task.id);
                li.innerHTML = `
                    ${task.title} - ${task.status} 
                    <button class="edit-btn">Editar</button>
                    <button class="status-btn">${task.status === 'pendente' ? 'Concluir' : 'Reabrir'}</button>
                    <button class="delete-btn">Excluir</button>
                `;

                li.querySelector('.edit-btn').addEventListener('click', () => openEditModal(task));
                li.querySelector('.status-btn').addEventListener('click', () => toggleStatus(task, li));
                li.querySelector('.delete-btn').addEventListener('click', () => deleteTask(task.id));

                taskList.appendChild(li);
            });
        })
        .catch(error => console.error('Erro ao carregar tarefas:', error));
}

// Função para criar uma nova tarefa
document.getElementById('task-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;

    fetch('api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title, description, status: "pendente" })
    })
    .then(response => response.json())
    .then(() => {
        fetchTasks();
        document.getElementById('task-form').reset();
    })
    .catch(error => console.error('Erro ao criar tarefa:', error));
});

// Função para excluir uma tarefa
function deleteTask(taskId) {
    fetch(`api.php?id=${taskId}`, { method: 'DELETE' })
        .then(response => response.json())
        .then(() => fetchTasks())
        .catch(error => console.error('Erro ao excluir tarefa:', error));
}

// Função para alternar o status de uma tarefa
function toggleStatus(task, li) {
    const newStatus = task.status === "pendente" ? "concluída" : "pendente";

    fetch(`api.php?id=${task.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            title: task.title,
            description: task.description,
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(() => {
        task.status = newStatus; // Atualiza o status localmente
        li.querySelector('.status-btn').textContent = newStatus === 'pendente' ? 'Concluir' : 'Reabrir'; // Atualiza o botão
    })
    .catch(error => console.error('Erro ao atualizar o status:', error));
}

// Função para abrir o modal de edição (melhorado)
function openEditModal(task) {
    // Preenche o formulário de edição com os dados da tarefa
    document.getElementById('title').value = task.title;
    document.getElementById('description').value = task.description;

    // Adiciona um atributo no formulário para saber que estamos editando
    document.getElementById('task-form').setAttribute('data-edit-id', task.id);

    // Muda o botão para "Salvar alterações"
    document.querySelector('button[type="submit"]').textContent = 'Salvar Alterações';
}

// Função para atualizar uma tarefa
document.getElementById('task-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const taskId = document.getElementById('task-form').getAttribute('data-edit-id');
    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;

    if (taskId) {
        // Se existe um ID, estamos editando a tarefa
        fetch(`api.php?id=${taskId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                title: title,
                description: description,
                status: "pendente"  // Mantém o status original
            })
        })
        .then(response => response.json())
        .then(() => {
            fetchTasks();
            document.getElementById('task-form').reset();
            document.getElementById('task-form').removeAttribute('data-edit-id');  // Limpa o ID de edição
            document.querySelector('button[type="submit"]').textContent = 'Adicionar Tarefa';  // Restaura o botão
        })
        .catch(error => console.error('Erro ao atualizar tarefa:', error));
    } else {
        // Se não existe um ID, estamos criando uma nova tarefa
        fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ title, description, status: "pendente" })
        })
        .then(response => response.json())
        .then(() => {
            fetchTasks();
            document.getElementById('task-form').reset();
        })
        .catch(error => console.error('Erro ao criar tarefa:', error));
    }
});

// Carregar tarefas ao iniciar
document.addEventListener('DOMContentLoaded', fetchTasks);
