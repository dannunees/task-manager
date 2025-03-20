// Função para pegar as tarefas da API
function fetchTasks() {
    fetch('api.php')  // Faz a requisição para a API
        .then(response => response.json())  // Converte a resposta para JSON
        .then(data => {
            const taskList = document.getElementById('task-list');
            taskList.innerHTML = '';  // Limpa a lista antes de adicionar novamente

            data.forEach(task => {
                const li = document.createElement('li');
                li.setAttribute('data-id', task.id);
                li.innerHTML = `${task.title} - ${task.status} 
                    <button class="delete-btn">Excluir</button>
                    <button class="status-btn">${task.status === 'pendente' ? 'Concluir' : 'Reabrir'}</button>`;
                
                // Adiciona evento para excluir a tarefa
                li.querySelector('.delete-btn').addEventListener('click', () => deleteTask(task.id));
                // Adiciona evento para alterar o status da tarefa
                li.querySelector('.status-btn').addEventListener('click', () => toggleStatus(task.id));

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

    const data = new URLSearchParams();
    data.append('title', title);
    data.append('description', description);

    fetch('api.php', {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(() => {
        fetchTasks();  // Atualiza a lista de tarefas
        document.getElementById('task-form').reset();  // Limpa o formulário
    })
    .catch(error => console.error('Erro ao criar tarefa:', error));
});

// Função para excluir uma tarefa
function deleteTask(taskId) {
    fetch(`api.php?id=${taskId}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(() => fetchTasks())  // Atualiza a lista de tarefas
    .catch(error => console.error('Erro ao excluir tarefa:', error));
}

// Função para alternar o status de uma tarefa
function toggleStatus(taskId) {
    fetch(`api.php?id=${taskId}`, {
        method: 'PUT'
    })
    .then(response => response.json())
    .then(() => fetchTasks())  // Atualiza a lista de tarefas
    .catch(error => console.error('Erro ao atualizar o status:', error));
}

// Carrega as tarefas ao inicializar
document.addEventListener('DOMContentLoaded', fetchTasks);
