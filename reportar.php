<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reportar Desaparecimento - SC</title>
  <meta name="description" content="Formulário para reportar pessoas desaparecidas em Santa Catarina">
  <link href="assets/css/bootstrap-5.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    .contact-card {
      transition: transform 0.3s;
    }
    .contact-card:hover {
      transform: translateY(-5px);
    }
    .form-section {
      background-color: #f8f9fa;
      border-radius: 10px;
    }
    .hero-banner {
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('assets/img/sc-bg.jpg');
      background-size: cover;
      background-position: center;
      color: white;
      padding: 80px 0;
      margin-bottom: 40px;
    }
  </style>
</head>
<body>

<!-- Banner com informações de SC -->
<section class="hero-banner text-center" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/bg.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
  <div class="container py-5">
    <h1 class="display-4 fw-bold text-white">Reportar Desaparecimento em Santa Catarina</h1>
    <p class="lead text-white">Sua informação pode salvar vidas. Preencha o formulário abaixo com os dados do desaparecido.</p>
  </div>
</section>

<div class="container mb-5">
  <div class="row g-4">
    <!-- Formulário de reporte -->
    <div class="col-lg-8">
      <section class="form-section p-4 shadow-sm">
        <h2 class="h4 mb-4 text-danger"><i class="bi bi-person-plus"></i> Formulário de Desaparecimento</h2>
        
        <form id="reportForm" method="post" enctype="multipart/form-data">
          <div class="row g-3">
            <!-- Dados Pessoais -->
            <div class="col-md-6">
              <label for="nome" class="form-label">Nome Completo*</label>
              <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            
            <div class="col-md-6">
              <label for="apelido" class="form-label">Apelido ou Nome Conhecido</label>
              <input type="text" class="form-control" id="apelido" name="apelido">
            </div>
            
            <div class="col-md-3">
              <label for="idade" class="form-label">Idade*</label>
              <input type="number" class="form-control" id="idade" name="idade" required>
            </div>
            
            <div class="col-md-3">
              <label for="sexo" class="form-label">Sexo*</label>
              <select class="form-select" id="sexo" name="sexo" required>
                <option value="" selected disabled>Selecione</option>
                <option value="Masculino">Masculino</option>
                <option value="Feminino">Feminino</option>
                <option value="Outro">Outro</option>
              </select>
            </div>
            
            <div class="col-md-6">
              <label for="nascimento" class="form-label">Data de Nascimento</label>
              <input type="date" class="form-control" id="nascimento" name="nascimento">
            </div>
            
            <!-- Informações do Desaparecimento -->
            <div class="col-12">
              <h3 class="h5 mt-4 mb-3 text-danger">Informações do Desaparecimento</h3>
            </div>
            
            <div class="col-md-6">
              <label for="desaparecido_em" class="form-label">Data do Desaparecimento*</label>
              <input type="date" class="form-control" id="desaparecido_em" name="desaparecido_em" required>
            </div>
            
            <div class="col-md-6">
              <label for="cidade" class="form-label">Cidade*</label>
              <select class="form-select" id="cidade" name="cidade" required>
                <option value="" selected disabled>Selecione a cidade</option>
                <option value="Florianópolis">Florianópolis</option>
                <option value="Joinville">Joinville</option>
                <option value="Blumenau">Blumenau</option>
                <option value="São José">São José</option>
                <option value="Criciúma">Criciúma</option>
                <option value="Chapecó">Chapecó</option>
                <option value="Itajaí">Itajaí</option>
                <option value="Lages">Lages</option>
                <option value="Jaraguá do Sul">Jaraguá do Sul</option>
                <option value="Palhoça">Palhoça</option>
                <option value="Outra">Outra</option>
              </select>
            </div>
            
            <div class="col-12">
              <label for="ultimo_local" class="form-label">Último Local Visto*</label>
              <textarea class="form-control" id="ultimo_local" name="ultimo_local" rows="2" required></textarea>
            </div>
            
            <div class="col-12">
              <label for="circunstancias" class="form-label">Circunstâncias do Desaparecimento</label>
              <textarea class="form-control" id="circunstancias" name="circunstancias" rows="3"></textarea>
            </div>
            
            <!-- Características Físicas -->
            <div class="col-12">
              <h3 class="h5 mt-4 mb-3 text-danger">Características Físicas</h3>
            </div>
            
            <div class="col-md-4">
              <label for="altura" class="form-label">Altura (cm)</label>
              <input type="number" class="form-control" id="altura" name="altura">
            </div>
            
            <div class="col-md-4">
              <label for="peso" class="form-label">Peso (kg)</label>
              <input type="number" class="form-control" id="peso" name="peso">
            </div>
            
            <div class="col-md-4">
              <label for="cor_pele" class="form-label">Cor da Pele</label>
              <select class="form-select" id="cor_pele" name="cor_pele">
                <option value="" selected disabled>Selecione</option>
                <option value="Branca">Branca</option>
                <option value="Parda">Parda</option>
                <option value="Negra">Negra</option>
                <option value="Amarela">Amarela</option>
                <option value="Indígena">Indígena</option>
              </select>
            </div>
            
            <div class="col-12">
              <label for="vestimentas" class="form-label">Roupas que Usava</label>
              <textarea class="form-control" id="vestimentas" name="vestimentas" rows="2"></textarea>
            </div>
            
            <div class="col-12">
              <label for="caracteristicas" class="form-label">Características Marcantes</label>
              <textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3"></textarea>
            </div>
            
            <!-- Foto e Contato -->
            <div class="col-12">
              <h3 class="h5 mt-4 mb-3 text-danger">Foto e Contato</h3>
            </div>
            
            <div class="col-md-6">
              <label for="foto" class="form-label">Foto*</label>
              <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
              <div class="form-text">Envie uma foto recente e de boa qualidade</div>
            </div>
            
            <div class="col-md-6">
              <label for="contato" class="form-label">Telefone para Contato*</label>
              <input type="tel" class="form-control" id="contato" name="contato" required>
            </div>
            
            <div class="col-12">
              <label for="email" class="form-label">E-mail</label>
              <input type="email" class="form-control" id="email" name="email">
            </div>
            
            <div class="col-12 mt-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="termos" required>
                <label class="form-check-label" for="termos">
                  Declaro que as informações fornecidas são verdadeiras*
                </label>
              </div>
            </div>
            
            <div class="col-12 mt-3">
              <button type="submit" class="btn btn-danger btn-lg">
                <i class="bi bi-send"></i> Reportar Desaparecimento
              </button>
            </div>
          </div>
        </form>
      </section>
    </div>
    
    <!-- Contatos e Informações -->
    <div class="col-lg-4">
      <div class="sticky-top" style="top: 20px;">
        <!-- Cartões de Contato -->
        <div class="card mb-4 border-danger contact-card">
          <div class="card-header bg-danger text-white">
            <h3 class="h5 mb-0"><i class="bi bi-telephone"></i> Contatos de Emergência</h3>
          </div>
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-danger rounded-circle p-2 me-3 text-white">
                <i class="bi bi-telephone fs-5"></i>
              </div>
              <div>
                <h4 class="h6 mb-0">Polícia Militar</h4>
                <a href="tel:190" class="text-decoration-none">190</a>
              </div>
            </div>
            
            <div class="d-flex align-items-center mb-3">
              <div class="bg-danger rounded-circle p-2 me-3 text-white">
                <i class="bi bi-megaphone fs-5"></i>
              </div>
              <div>
                <h4 class="h6 mb-0">Disque Denúncia</h4>
                <a href="tel:181" class="text-decoration-none">181</a>
              </div>
            </div>
            
            <div class="d-flex align-items-center mb-3">
              <div class="bg-danger rounded-circle p-2 me-3 text-white">
                <i class="bi bi-people fs-5"></i>
              </div>
              <div>
                <h4 class="h6 mb-0">Conselho Tutelar</h4>
                <a href="tel:08006449999" class="text-decoration-none">0800 644 9999</a>
              </div>
            </div>
            
            <div class="d-flex align-items-center">
              <div class="bg-danger rounded-circle p-2 me-3 text-white">
                <i class="bi bi-heart fs-5"></i>
              </div>
              <div>
                <h4 class="h6 mb-0">CVV - Centro de Valorização da Vida</h4>
                <a href="tel:188" class="text-decoration-none">188</a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Informações sobre desaparecimentos em SC -->
        <div class="card mb-4 border-primary contact-card">
          <div class="card-header bg-primary text-white">
            <h3 class="h5 mb-0"><i class="bi bi-info-circle"></i> Dados de SC</h3>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Desaparecimentos/ano</span>
                <span class="badge bg-primary rounded-pill">~2.500</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Maioria ocorre em</span>
                <span class="badge bg-primary rounded-pill">Grande Florianópolis</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Faixa etária comum</span>
                <span class="badge bg-primary rounded-pill">15-25 anos</span>
              </li>
              <li class="list-group-item">
                <small class="text-muted">Fonte: Secretaria de Segurança Pública de SC</small>
              </li>
            </ul>
          </div>
        </div>
        
        <!-- Fluxo de Ação -->
        <div class="card border-success contact-card">
          <div class="card-header bg-success text-white">
            <h3 class="h5 mb-0"><i class="bi bi-lightbulb"></i> O Que Fazer?</h3>
          </div>
          <div class="card-body">
   <ol class="list-group list-group-numbered list-group-flush">
  <li class="list-group-item">
    Registre imediatamente um Boletim de Ocorrência (BO)<br>
    <a href="https://delegaciavirtual.sc.gov.br" target="_blank" class="text-decoration-underline text-primary fw-semibold">
      ➤ Delegacia Virtual de Santa Catarina
    </a><br>
    <a href="https://www.sc.gov.br/detalhe/realizar-boletim-de-ocorrencia-para-localizacao-e-busca-de-pessoas-desaparecidas" target="_blank" class="text-decoration-underline text-primary fw-semibold">
      ➤ Instruções para registro de BO de desaparecimento
    </a>
  </li>
  <li class="list-group-item">Acione a rede de contatos (familiares e amigos)</li>
  <li class="list-group-item">Compartilhe fotos e dados nas redes sociais</li>
  <li class="list-group-item">
    Contate órgãos especializados<br>
    <a href="https://www.gov.br/mj/pt-br/acesso-a-informacao/acoes-e-programas/desaparecidos" target="_blank" class="text-decoration-underline text-primary fw-semibold">
      ➤ Política Nacional de Busca de Pessoas Desaparecidas
    </a><br>
    <a href="https://www.policiacientifica.sc.gov.br/pci-conecta/como-participar/familiar-de-pessoa-desaparecida/" target="_blank" class="text-decoration-underline text-primary fw-semibold">
      ➤ Programa Conecta – Polícia Científica de SC
    </a>
  </li>
  <li class="list-group-item">Mantenha a calma e organize buscas com ajuda de voluntários</li>
</ol>


          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Leaflet CSS e JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Seção do Mapa com Bootstrap -->
<section class="bg-light py-5">
  <div class="container">
    <h2 class="text-center mb-4"><i class="bi bi-map"></i> Desaparecimentos por Região de SC</h2>
    <div class="row align-items-center">
      
      <!-- Coluna do Mapa (ocupa 12 col no mobile, 6 no desktop) -->
      <div class="col-12 col-md-6 mb-4 mb-md-0">
        <div id="map-sc" class="w-100 rounded shadow" style="height: 400px;"></div>
      </div>

      <!-- Coluna da Tabela -->
      <div class="col-12 col-md-6">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead class="table-dark">
              <tr>
                <th>Região</th>
                <th>Casos/ano</th>
                <th>%</th>
              </tr>
            </thead>
            <tbody>
              <tr><td>Grande Florianópolis</td><td>850</td><td>34%</td></tr>
              <tr><td>Vale do Itajaí</td><td>600</td><td>24%</td></tr>
              <tr><td>Norte/Nordeste</td><td>450</td><td>18%</td></tr>
              <tr><td>Oeste</td><td>350</td><td>14%</td></tr>
              <tr><td>Sul</td><td>250</td><td>10%</td></tr>
            </tbody>
          </table>
        </div>
        <div class="alert alert-info mt-3">
          <i class="bi bi-info-circle"></i> Dados aproximados baseados em estatísticas dos últimos 3 anos.
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Script para exibir o mapa com marcadores por região -->
<script>
  const map = L.map('map-sc').setView([-27.45, -50.95], 7);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  const regioes = [
    { nome: 'Grande Florianópolis', coords: [-27.6, -48.6], casos: 850 },
    { nome: 'Vale do Itajaí', coords: [-27.0, -49.5], casos: 600 },
    { nome: 'Norte/Nordeste', coords: [-26.3, -48.8], casos: 450 },
    { nome: 'Oeste', coords: [-26.9, -52.5], casos: 350 },
    { nome: 'Sul', coords: [-28.7, -49.4], casos: 250 }
  ];

  regioes.forEach(r => {
    L.marker(r.coords).addTo(map)
      .bindPopup(`<strong>${r.nome}</strong><br>Casos/ano: ${r.casos}`);
  });
</script>

<script>
  // Validação do formulário
  (function() {
    'use strict';
    
    const form = document.getElementById('reportForm');
    if (form) {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        
        form.classList.add('was-validated');
      }, false);
    }
    
    // Máscara para telefone
    const phoneInput = document.getElementById('contato');
    if (phoneInput) {
      phoneInput.addEventListener('input', function(e) {
        const x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
        e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
      });
    }
  })();
</script>

</body>
</html>
<?php include 'includes/footer.php'; ?>