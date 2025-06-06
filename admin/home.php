<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

// Usa a data do login salvo na sessão (em horário de Brasília)
$ultimoAcesso = isset($_SESSION['user']['login_time'])
    ? (new DateTime($_SESSION['user']['login_time'], new DateTimeZone('America/Sao_Paulo')))->format('d/m/Y H:i')
    : (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('d/m/Y H:i');

// Garante que $desaparecidos está definido para evitar erro se estiver vazio
$desaparecidos = $desaparecidos ?? [];

include 'includes/headerUser.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Administrativo - Desaparecidos SC</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .card-img-top { height: 250px; object-fit: cover; }
    .card { transition: transform 0.3s; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .user-info { background-color: #f8f9fa; border-radius: 5px; padding: 15px; margin-bottom: 20px; }
    .status-badge { position: absolute; top: 10px; right: 10px; }
  </style>
</head>
<body>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Desaparecidos</h2>
      <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
        <a href="cadastro.php" class="btn btn-primary">
          <i class="fas fa-plus me-1"></i> Novo Cadastro
        </a>
      <?php endif; ?>
    </div>
  </div>

    
    <div class="container">
      <div class="card-body">
        <table id="desaparecidosTable" class="table table-striped" style="width:100%">
          <thead>
            <tr>
              <th>Foto</th>
              <th>Nome</th>
              <th>Idade</th>
              <th>Data Desap.</th>
              <th>Cidade</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <!-- Dados serão carregados via JavaScript -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal de Visualização -->
  <div class="modal fade" id="visualizarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalhes do Desaparecido</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4 text-center">
              <img id="viewFoto" src="" class="img-fluid rounded mb-3" alt="Foto">
            </div>
            <div class="col-md-8">
              <h4 id="viewNome"></h4>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Idade:</strong> <span id="viewIdade"></span></li>
                <li class="list-group-item"><strong>Desaparecido em:</strong> <span id="viewData"></span></li>
                <li class="list-group-item"><strong>Cidade:</strong> <span id="viewCidade"></span></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Edição -->
  <div class="modal fade" id="editarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Desaparecido</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formEditar">
            <input type="hidden" id="editIndex">
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="editNome" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="editNome" name="nome" required>
              </div>
              <div class="col-md-6">
                <label for="editIdade" class="form-label">Idade</label>
                <input type="number" class="form-control" id="editIdade" name="idade" required>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="editData" class="form-label">Data Desaparecimento</label>
                <input type="date" class="form-control" id="editData" name="desaparecidoEm" required>
              </div>
              <div class="col-md-6">
                <label for="editCidade" class="form-label">Cidade</label>
                <input type="text" class="form-control" id="editCidade" name="cidade" required>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-12">
                <label for="editFoto" class="form-label">URL da Foto</label>
                <input type="url" class="form-control" id="editFoto" name="foto" required>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnSalvarEdicao">Salvar Alterações</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Confirmação de Exclusão -->
  <div class="modal fade" id="confirmarExclusaoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmar Exclusão</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Tem certeza que deseja excluir este registro? Esta ação não pode ser desfeita.</p>
          <input type="hidden" id="excluirIndex">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger" id="btnConfirmarExclusao">Excluir</button>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
        <div class="position-sticky pt-3">
          <div class="mt-4 p-3">
            <div class="user-info bg-secondary text-white p-3 rounded">
              <p class="small mb-0">Último acesso: <?= $ultimoAcesso ?></p>
            </div>
          </div>
        </div>
      </nav>

      <!-- Conteúdo principal -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Reportações de Pessoas Desaparecidas</h1>
        </div>

        <!-- Cards -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
          <?php if (empty($desaparecidos)): ?>
            <div class="col-12">
              <div class="alert alert-info">Nenhum desaparecido cadastrado ainda.</div>
            </div>
          <?php else: ?>
            <?php foreach ($desaparecidos as $p): ?>
              <div class="col">
                <div class="card h-100 position-relative">
                  <span class="badge bg-<?= $p['status'] === 'encontrado' ? 'success' : 'danger' ?> status-badge">
                    <?= ucfirst($p['status']) ?>
                  </span>
                  <img src="../uploads/<?= htmlspecialchars($p['foto']) ?>" 
                       class="card-img-top" 
                       alt="Foto de <?= htmlspecialchars($p['nome']) ?>"
                       onerror="this.src='../assets/img/placeholder.jpg'">
                  <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($p['nome']) ?></h5>
                    <p class="card-text">
                      <strong>Idade:</strong> <?= calcularIdade($p['data_nascimento']) ?><br>
                      <strong>Desaparecido em:</strong> <?= date('d/m/Y', strtotime($p['data_desaparecimento'])) ?><br>
                      <strong>Cidade:</strong> <?= htmlspecialchars($p['cidade']) ?>
                    </p>
                  </div>
                  <div class="card-footer bg-transparent">
                    <a href="detalhes.php?id=<?= $p['id'] ?>" class="btn btn-outline-primary btn-sm">
                      <i class="bi bi-eye"></i> Ver Detalhes
                    </a>
                    <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-outline-secondary btn-sm">
                      <i class="bi bi-pencil"></i> Editar
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>

  <!-- Função para calcular idade -->
  <?php
  function calcularIdade($dataNascimento) {
      if (empty($dataNascimento)) return 'Não informada';
      $hoje = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
      $nascimento = new DateTime($dataNascimento);
      $idade = $nascimento->diff($hoje)->y;
      return $idade . ' anos';
  }
  ?>

  <script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('collapsed');
    });
  </script>
    <script>
  $(document).ready(function() {
    let desaparecidosData = [];
    
    // Carregar dados do JSON
    function carregarDados() {
      return $.getJSON('/../../db/desaparecidos.json', function(data) {
        desaparecidosData = data;
      });
    }
    
    $(document).ready(function() {
    // Variável para armazenar todos os dados (usada apenas para os modais)
    let allData = [];
    
    // Inicializar DataTable com server-side processing
    const table = $('#desaparecidosTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'back/listas/todosDesaparecidos.php',
            type: 'GET',
            dataSrc: function(json) {
                // Armazenar todos os dados para uso nos modais
                allData = json.data;
                return json.data;
            }
        },
        columns: [
            { 
                data: 'foto',
                render: function(data, type, row) {
                    return `<img src="${data}" class="table-img" alt="Foto">`;
                }
            },
            { data: 'nome' },
            { data: 'idade' },
            { data: 'desaparecidoEm' },
            { data: 'cidade' },
            {
                data: null,
                orderable: false,
                className: 'action-buttons',
                render: function(data, type, row, meta) {
                    return `
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-info" title="Visualizar" onclick="visualizarDesaparecido(${meta.row})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-primary" title="Editar" onclick="abrirModalEdicao(${meta.row})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" title="Excluir" onclick="abrirModalExclusao(${meta.row})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
        },
        pageLength: 30,
        lengthMenu: [10, 30, 50, 100],
        order: [[3, 'desc']]
    });

    // Função para formatar data
    function formatarData(dataStr) {
        const [dia, mes, ano] = dataStr.split('/');
        return new Date(`${ano}-${mes}-${dia}`).toLocaleDateString('pt-BR');
    }

    // Modal de Visualização
    window.visualizarDesaparecido = function(index) {
        // Usar allData em vez de desaparecidosData
        const pessoa = allData[index];
        
        $('#viewFoto').attr('src', pessoa.foto);
        $('#viewNome').text(pessoa.nome);
        $('#viewIdade').text(pessoa.idade + ' anos');
        $('#viewData').text(formatarData(pessoa.desaparecidoEm));
        $('#viewCidade').text(pessoa.cidade);
        
        const modal = new bootstrap.Modal(document.getElementById('visualizarModal'));
        modal.show();
    };

    // Modal de Edição
    window.abrirModalEdicao = function(index) {
        const pessoa = allData[index];
        
        $('#editIndex').val(index);
        $('#editNome').val(pessoa.nome);
        $('#editIdade').val(pessoa.idade);
        
        // Converter data para formato YYYY-MM-DD
        const [dia, mes, ano] = pessoa.desaparecidoEm.split('/');
        $('#editData').val(`${ano}-${mes}-${dia}`);
        
        $('#editCidade').val(pessoa.cidade);
        $('#editFoto').val(pessoa.foto);
        
        const modal = new bootstrap.Modal(document.getElementById('editarModal'));
        modal.show();
    };

    // Salvar edição
    $('#btnSalvarEdicao').click(function() {
        const index = $('#editIndex').val();
        const [ano, mes, dia] = $('#editData').val().split('-');
        
        // Atualizar os dados locais
        allData[index] = {
            nome: $('#editNome').val(),
            idade: $('#editIdade').val(),
            desaparecidoEm: `${dia}/${mes}/${ano}`,
            cidade: $('#editCidade').val(),
            foto: $('#editFoto').val()
        };
        
        // Aqui você precisaria enviar a atualização para o servidor
        // Por enquanto, apenas recarregamos a tabela
        table.ajax.reload();
        
        bootstrap.Modal.getInstance(document.getElementById('editarModal')).hide();
        alert('Alterações salvas (simulação - em produção salvaria no arquivo JSON)');
    });

    // Modal de Exclusão
    window.abrirModalExclusao = function(index) {
        $('#excluirIndex').val(index);
        const modal = new bootstrap.Modal(document.getElementById('confirmarExclusaoModal'));
        modal.show();
    };

    // Confirmar exclusão
    $('#btnConfirmarExclusao').click(function() {
        const index = $('#excluirIndex').val();
        
        // Remover dos dados locais
        allData.splice(index, 1);
        
        // Aqui você precisaria enviar a exclusão para o servidor
        // Por enquanto, apenas recarregamos a tabela
        table.ajax.reload();
        
        bootstrap.Modal.getInstance(document.getElementById('confirmarExclusaoModal')).hide();
        alert('Registro excluído (simulação - em produção removeria do arquivo JSON)');
    });
});

    // Função para formatar data
    function formatarData(dataStr) {
      const [dia, mes, ano] = dataStr.split('/');
      return new Date(`${ano}-${mes}-${dia}`).toLocaleDateString('pt-BR');
    }

    // Modal de Visualização
    window.visualizarDesaparecido = function(index) {
      const pessoa = desaparecidosData[index];
      
      $('#viewFoto').attr('src', pessoa.foto);
      $('#viewNome').text(pessoa.nome);
      $('#viewIdade').text(pessoa.idade + ' anos');
      $('#viewData').text(formatarData(pessoa.desaparecidoEm));
      $('#viewCidade').text(pessoa.cidade);
      
      const modal = new bootstrap.Modal(document.getElementById('visualizarModal'));
      modal.show();
    };

    // Modal de Edição
    window.abrirModalEdicao = function(index) {
      const pessoa = desaparecidosData[index];
      
      $('#editIndex').val(index);
      $('#editNome').val(pessoa.nome);
      $('#editIdade').val(pessoa.idade);
      
      // Converter data para formato YYYY-MM-DD
      const [dia, mes, ano] = pessoa.desaparecidoEm.split('/');
      $('#editData').val(`${ano}-${mes}-${dia}`);
      
      $('#editCidade').val(pessoa.cidade);
      $('#editFoto').val(pessoa.foto);
      
      const modal = new bootstrap.Modal(document.getElementById('editarModal'));
      modal.show();
    };

    // Salvar edição
    $('#btnSalvarEdicao').click(function() {
      const index = $('#editIndex').val();
      const [ano, mes, dia] = $('#editData').val().split('-');
      
      desaparecidosData[index] = {
        nome: $('#editNome').val(),
        idade: $('#editIdade').val(),
        desaparecidoEm: `${dia}/${mes}/${ano}`,
        cidade: $('#editCidade').val(),
        foto: $('#editFoto').val()
      };
      
      // Aqui você precisaria implementar a lógica para salvar no JSON
      // Por enquanto, apenas recarregamos a tabela
      table.ajax.reload();
      
      bootstrap.Modal.getInstance(document.getElementById('editarModal')).hide();
      alert('Alterações salvas (simulação - em produção salvaria no arquivo JSON)');
    });

    // Modal de Exclusão
    window.abrirModalExclusao = function(index) {
      $('#excluirIndex').val(index);
      const modal = new bootstrap.Modal(document.getElementById('confirmarExclusaoModal'));
      modal.show();
    };

    // Confirmar exclusão
    $('#btnConfirmarExclusao').click(function() {
      const index = $('#excluirIndex').val();
      desaparecidosData.splice(index, 1);
      
      // Aqui você precisaria implementar a lógica para salvar no JSON
      // Por enquanto, apenas recarregamos a tabela
      table.ajax.reload();
      
      bootstrap.Modal.getInstance(document.getElementById('confirmarExclusaoModal')).hide();
      alert('Registro excluído (simulação - em produção removeria do arquivo JSON)');
    });
  });
  </script>
</body>
</html>
