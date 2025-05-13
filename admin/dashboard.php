<?php include 'includes/headerUser.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .action-buttons .btn { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
    .badge-status { font-size: 0.85em; padding: 0.35em 0.65em; }
    .table-img { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
  </style>
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Desaparecidos</h2>
      <a href="cadastro.php" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Novo Cadastro
      </a>
    </div>
    
    <div class="card">
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

  <!-- JavaScript Libraries -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

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