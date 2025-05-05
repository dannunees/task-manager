<h2>Gerenciador de Tarefas</h2>

Este é um projeto de um Gerenciador de Tarefas. O sistema permite criar, editar, excluir e alterar o status das tarefas. Ele usa Docker para configuração do ambiente, PHP para manipulação das tarefas e MySQL como banco de dados para armazenar as informações das tarefas.

<h3>Funcionalidades</h3>

<ul>
    <li>Criar tarefa: Adicionar uma nova tarefa com título e descrição.</li>
    <li>Editar tarefa: Alterar título e descrição de uma tarefa existente.</li>
    <li>Excluir tarefa: Remover uma tarefa do sistema.</li>
    <li>Alterar status: Modificar o status da tarefa entre "pendente", "em andamento" e "concluída".</li>
</ul>

<h3>Requisitos</h3>

<ul>
    <li>Docker: Para rodar o projeto em containers, você precisa ter o Docker e o Docker Compose instalados na sua máquina.</li>
    <li>PHP: O backend utiliza PHP para gerenciar as tarefas.</li>
    <li>MySQL: O projeto usa MySQL como banco de dados para armazenar as tarefas.</li>
</ul>


<h3>Como Rodar o Projeto</h3>

1. Clonar o repositório
Primeiro, clone o repositório do projeto para sua máquina local:

```bash
git clone https://github.com/dannunees/gerenciador-de-tarefas.git
cd gerenciador-de-tarefas
```

2. Construir e rodar os containers Docker
Com o Docker e Docker Compose instalados na sua máquina, navegue até o diretório do projeto e execute o seguinte comando para construir e iniciar os containers:

```bash
docker-compose up --build
```

Este comando irá:

1-Construir as imagens do Docker com base no arquivo Dockerfile e no docker-compose.yml.
2-Subir os containers com o ambiente de desenvolvimento, incluindo o servidor PHP e o banco de dados MySQL.

<h3>3. Acessar o Aplicativo</h3>

Após os containers estarem rodando, você pode acessar o aplicativo em seu navegador no endereço:

```bash
http://localhost:9000
```


<h3>4. Parar os Containers</h3>

Para parar os containers, você pode usar o comando:

```bash
docker-compose down
```

Este comando irá parar e remover os containers criados, mas manterá os volumes de dados (se configurados).

<h3>Estrutura do Projeto</h3>

<ul>
    <li>docker-compose.yml: Arquivo de configuração para o Docker Compose, onde os containers são definidos (incluindo o MySQL).</li>
    <li>Dockerfile: Arquivo de configuração para construir a imagem do servidor PHP.</li>
    <li>api.php: Arquivo PHP que gerencia as tarefas (CRUD - Criar, Ler, Atualizar, Excluir) e interage com o banco de dados MySQL.</li>
    <li>index.html: Frontend simples em HTML, JavaScript e CSS que permite a interação com o sistema de tarefas.</li>
    <li>db.sql: Arquivo com a estrutura inicial do banco de dados MySQL, contendo as tabelas necessárias para o funcionamento do sistema.</li>
</ul>

<h3>Tecnologias</h3>

<ul>
    <li>Frontend: HTML, CSS (Bootstrap) e Vanilla JavaScript.</li>
    <li>Backend: PHP.</li>
    <li>Banco de Dados: MySQL (configurado via Docker).</li>
    <li>Docker: Usado para criar containers e orquestrar o ambiente de desenvolvimento.
    </li>
</ul>








