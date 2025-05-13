<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user'])) {
  header('Location: ../index.html');
  exit;
}

$user = $_SESSION['user'];
?>
<?php include 'includes/headerUser.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cruzamento de Dados - Desaparecidos</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .card-filter {
      margin-bottom: 20px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .table-img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 4px;
    }
    .filter-section {
      background-color: #f8f9fa;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 30px;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2><i class="fas fa-project-diagram me-2"></i>Cruzamento de Dados</h2>
    </div>

    <!-- Filtros de Cruzamento -->
    <div class="filter-section">
      <h4 class="mb-4"><i class="fas fa-filter me-2"></i>Filtros para Cruzamento</h4>
      <form id="formFiltros">
        <div class="row">
          <div class="col-md-6">
            <div class="card card-filter">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Dados Pessoais</h5>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label for="filtroNome" class="form-label">Nome</label>
                  <input type="text" class="form-control" id="filtroNome" placeholder="Digite parte do nome">
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label for="filtroIdadeMin" class="form-label">Idade Mínima</label>
                    <input type="number" class="form-control" id="filtroIdadeMin" min="0">
                  </div>
                  <div class="col-md-6">
                    <label for="filtroIdadeMax" class="form-label">Idade Máxima</label>
                    <input type="number" class="form-control" id="filtroIdadeMax" min="0">
                  </div>
                </div>
                <div class="mb-3 mt-3">
                  <label for="filtroSexo" class="form-label">Sexo</label>
                  <select class="form-select" id="filtroSexo">
                    <option value="">Todos</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                    <option value="Outro">Outro</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="card card-filter">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Desaparecimento</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <label for="filtroDataInicio" class="form-label">Data Inicial</label>
                    <input type="date" class="form-control" id="filtroDataInicio">
                  </div>
                  <div class="col-md-6">
                    <label for="filtroDataFim" class="form-label">Data Final</label>
                    <input type="date" class="form-control" id="filtroDataFim">
                  </div>
                </div>
                <div class="mb-3 mt-3">
                  <label for="filtroEstado" class="form-label">Estado</label>
                  <select class="form-select" id="filtroEstado">
                    <option value="">Todos</option>
                    <option value="AC">Acre</option>
                    <option value="AL">Alagoas</option>
                    <!-- Adicione outros estados -->
                  </select>
                </div>
                <div class="mb-3">
                  <label for="filtroCidade" class="form-label">Cidade</label>
                  <input type="text" class="form-control" id="filtroCidade" placeholder="Digite a cidade">
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row mt-3">
          <div class="col-md-12">
            <div class="card card-filter">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Características Físicas</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <label for="filtroCorOlhos" class="form-label">Cor dos Olhos</label>
                    <select class="form-select" id="filtroCorOlhos">
                      <option value="">Todos</option>
                      <option value="Castanhos">Castanhos</option>
                      <option value="Azuis">Azuis</option>
                      <option value="Verdes">Verdes</option>
                      <option value="Pretos">Pretos</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label for="filtroCorPele" class="form-label">Cor da Pele</label>
                    <select class="form-select" id="filtroCorPele">
                      <option value="">Todos</option>
                      <option value="Branca">Branca</option>
                      <option value="Parda">Parda</option>
                      <option value="Negra">Negra</option>
                      <option value="Amarela">Amarela</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label for="filtroTipoCabelo" class="form-label">Tipo de Cabelo</label>
                    <select class="form-select" id="filtroTipoCabelo">
                      <option value="">Todos</option>
                      <option value="Liso">Liso</option>
                      <option value="Ondulado">Ondulado</option>
                      <option value="Cacheado">Cacheado</option>
                      <option value="Crespo">Crespo</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="text-center mt-4">
          <button type="button" id="btnFiltrar" class="btn btn-primary btn-lg">
            <i class="fas fa-search me-2"></i>Realizar Cruzamento
          </button>
          <button type="reset" id="btnLimpar" class="btn btn-outline-secondary btn-lg ms-2">
            <i class="fas fa-eraser me-2"></i>Limpar Filtros
          </button>
        </div>
      </form>
    </div>

    <!-- Resultados do Cruzamento -->
    <div class="card">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Resultados do Cruzamento</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="resultadosTable" class="table table-striped" style="width:100%">
            <thead>
              <tr>
                <th>Foto</th>
                <th>Nome</th>
                <th>Idade</th>
                <th>Sexo</th>
                <th>Data Desap.</th>
                <th>Cidade/Estado</th>
                <th>Características</th>
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
  </div>

  <!-- Modal de Detalhes -->
  <div class="modal fade" id="detalhesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalhes do Registro</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="detalhesConteudo">
          <!-- Conteúdo será carregado via JavaScript -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
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
    // Inicializar DataTable
    const table = $('#resultadosTable').DataTable({
      ajax: {
        url: 'back/cruzamento/cruzamento.php',
        type: 'POST',
        data: function(d) {
          // Adicionar nossos filtros aos parâmetros enviados
          d.filtros = {
            nome: $('#filtroNome').val(),
            idadeMin: $('#filtroIdadeMin').val(),
            idadeMax: $('#filtroIdadeMax').val(),
            sexo: $('#filtroSexo').val(),
            dataInicio: $('#filtroDataInicio').val(),
            dataFim: $('#filtroDataFim').val(),
            estado: $('#filtroEstado').val(),
            cidade: $('#filtroCidade').val(),
            corOlhos: $('#filtroCorOlhos').val(),
            corPele: $('#filtroCorPele').val(),
            tipoCabelo: $('#filtroTipoCabelo').val()
          };
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
        { data: 'sexo' },
        { data: 'desaparecidoEm' },
        { 
          data: null,
          render: function(data, type, row) {
            return `${row.cidade}/${row.estado}`;
          }
        },
        { 
          data: null,
          render: function(data, type, row) {
            return `Olhos: ${row.corOlhos || 'N/I'}<br>Pele: ${row.corPele || 'N/I'}<br>Cabelo: ${row.tipoCabelo || 'N/I'}`;
          }
        },
        {
          data: null,
          orderable: false,
          render: function(data, type, row) {
            return `
              <button class="btn btn-sm btn-info" title="Detalhes" onclick="verDetalhes('${row.id}')">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm btn-primary" title="Comparar" onclick="compararRegistro('${row.id}')">
                <i class="fas fa-exchange-alt"></i>
              </button>
            `;
          }
        }
      ],
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
      },
      pageLength: 10
    });

    // Aplicar filtros
    $('#btnFiltrar').click(function() {
      table.ajax.reload();
    });

    // Limpar filtros
    $('#btnLimpar').click(function() {
      $('#formFiltros')[0].reset();
      table.ajax.reload();
    });

    // Função para ver detalhes
    window.verDetalhes = function(id) {
      $.get(`back/cruzamento/detalhesDesaparecido.php?id=${id}`, function(data) {
        if (data.success) {
          const pessoa = data.data;
          let html = `
            <div class="row">
              <div class="col-md-4 text-center">
                <img src="${pessoa.foto}" class="img-fluid rounded mb-3" alt="Foto">
              </div>
              <div class="col-md-8">
                <h4>${pessoa.nome}</h4>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"><strong>Idade:</strong> ${pessoa.idade} anos</li>
                  <li class="list-group-item"><strong>Sexo:</strong> ${pessoa.sexo || 'Não informado'}</li>
                  <li class="list-group-item"><strong>Desaparecido em:</strong> ${pessoa.desaparecidoEm}</li>
                  <li class="list-group-item"><strong>Local:</strong> ${pessoa.cidade}/${pessoa.estado}</li>
                  <li class="list-group-item"><strong>Cor dos olhos:</strong> ${pessoa.corOlhos || 'Não informado'}</li>
                  <li class="list-group-item"><strong>Cor da pele:</strong> ${pessoa.corPele || 'Não informado'}</li>
                  <li class="list-group-item"><strong>Tipo de cabelo:</strong> ${pessoa.tipoCabelo || 'Não informado'}</li>
                </ul>
              </div>
            </div>
          `;
          $('#detalhesConteudo').html(html);
          const modal = new bootstrap.Modal(document.getElementById('detalhesModal'));
          modal.show();
        } else {
          alert('Erro ao carregar detalhes: ' + data.message);
        }
      }, 'json');
    };

    // Função para comparar registros (implementação básica)
    window.compararRegistro = function(id) {
      alert('Funcionalidade de comparação será implementada aqui. ID: ' + id);
      // Você pode implementar uma lógica para selecionar 2 registros e compará-los
    };
  });
  </script>
</body>
</html>