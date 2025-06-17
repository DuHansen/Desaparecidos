<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: ../index.html');
  exit;
}
$user = $_SESSION['user'];

// Inclui a função que recupera o total de desaparecidos
include 'back/consultas/pegarDesaparecidos.php';
// Recupera os dados dos cards
$totalDesaparecidos = $dashboardData['totalDesaparecidos'];
$totalEncontrados = $dashboardData['totalEncontrados'];
$totalRecent = $dashboardData['totalRecent'];
$taxaResolucao = $dashboardData['taxaResolucao'];
$percentageRecent = $dashboardData['percentageRecent'];
$percentageResolution = $dashboardData['percentageResolution'];
?>

<?php include 'includes/headerUser.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Análise de Desaparecidos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body class="bg-light text-dark">

<div class="container-fluid py-4">
  <div class="container-xl">
    <div class="row g-4">
      
       <!-- CARD: Total de Desaparecidos -->
      <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm border-0 rounded h-100">
          <div class="card-body">
            <h6 class="text-muted">Total de Desaparecidos</h6>
            <div class="d-flex align-items-center">
              <h2 class="mb-0 me-3"><?= $totalDesaparecidos ?></h2>
            </div>
            <div class="progress mt-3" style="height: 5px;">
              <div class="progress-bar bg-primary" style="width: 75%"></div>
            </div>
          </div>
        </div>
      </div>   

      <!-- CARD: Pessoas Encontradas -->
      <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm border-0 rounded h-100">
          <div class="card-body">
            <h6 class="text-muted">Pessoas Encontradas</h6>
            <div class="d-flex align-items-center">
              <h2 class="mb-0 me-3"><?= $totalEncontrados ?></h2>
              <span class="text-success small"><i class="fas fa-arrow-up me-1"></i> <?= $percentageResolution ?>%</span>
            </div>
            <div class="progress mt-3" style="height: 5px;">
              <div class="progress-bar bg-success" style="width: <?= $percentageResolution ?>%"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- CARD: Desaparecidos Recentes (7 dias) -->
      <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm border-0 rounded h-100">
          <div class="card-body">
            <h6 class="text-muted">Desaparecidos (7 dias)</h6>
            <div class="d-flex align-items-center">
              <h2 class="mb-0 me-3"><?= $totalRecent ?></h2>
              <span class="text-danger small"><i class="fas fa-arrow-down me-1"></i> <?= $percentageRecent ?>%</span>
            </div>
            <div class="progress mt-3" style="height: 5px;">
              <div class="progress-bar bg-warning" style="width: <?= $percentageRecent ?>%"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- CARD: Taxa de Resolução -->
      <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm border-0 rounded h-100">
          <div class="card-body">
            <h6 class="text-muted">Taxa de Resolução</h6>
            <div class="d-flex align-items-center">
              <h2 class="mb-0 me-3"><?= $taxaResolucao ?>%</h2>
              <span class="text-success small"><i class="fas fa-arrow-up me-1"></i> <?= $percentageResolution ?>%</span>
            </div>
            <div class="progress mt-3" style="height: 5px;">
              <div class="progress-bar bg-info" style="width: <?= $percentageResolution ?>%"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- GRÁFICOS -->
      <div class="col-xl-6">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-header bg-light">
            <h5 class="mb-0">Desaparecidos por Dia (Últimos 30 dias)</h5>
          </div>
          <div class="card-body">
            <div id="chart-desaparecidos-dia" style="min-height: 300px;"></div>
          </div>
        </div>
      </div>

      <div class="col-xl-6">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-header bg-light">
            <h5 class="mb-0">Status dos Desaparecidos</h5>
          </div>
          <div class="card-body">
            <div id="chart-status-desaparecidos" style="min-height: 300px;"></div>
          </div>
        </div>
      </div>

      <div class="col-xl-12">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-header bg-light">
            <h5 class="mb-0">Desaparecidos por Estado (Top 10)</h5>
          </div>
          <div class="card-body">
            <div id="chart-desaparecidos-estado" style="min-height: 350px;"></div>
          </div>
        </div>
      </div>

      <div class="col-xl-12">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-header bg-light">
            <h5 class="mb-0">Evolução de desaparecidos (Acumulado)</h5>
          </div>
          <div class="card-body">
            <div id="chart-evolucao-cadastros" style="min-height: 350px;"></div>
          </div>
        </div>
      </div>

      <!-- TABELA -->
      <div class="col-xl-12">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-header bg-light">
            <h5 class="mb-0">Desaparecidos Recentes</h5>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle text-center m-0">
              <thead class="table-light">
                <tr>
                  <th>Nome</th>
                  <th>Idade</th>
                  <th>Data</th>
                  <th>Local</th>
                  <th>Status</th>
                  <th>Foto</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>João Silva</td>
                  <td>15</td>
                  <td>15/05/2023</td>
                  <td>São Paulo, SP</td>
                  <td><span class="badge bg-warning text-dark">Desaparecido</span></td>
                  <td><img src="https://via.placeholder.com/40" class="rounded-circle" alt="Foto"></td>
                </tr>
                <tr>
                  <td>Maria Oliveira</td>
                  <td>32</td>
                  <td>12/05/2023</td>
                  <td>Rio de Janeiro, RJ</td>
                  <td><span class="badge bg-success">Encontrado</span></td>
                  <td><img src="https://via.placeholder.com/40" class="rounded-circle" alt="Foto"></td>
                </tr>
                <tr>
                  <td>Carlos Souza</td>
                  <td>8</td>
                  <td>10/05/2023</td>
                  <td>Belo Horizonte, MG</td>
                  <td><span class="badge bg-warning text-dark">Desaparecido</span></td>
                  <td><img src="https://via.placeholder.com/40" class="rounded-circle" alt="Foto"></td>
                </tr>
                <tr>
                  <td>Ana Pereira</td>
                  <td>45</td>
                  <td>08/05/2023</td>
                  <td>Porto Alegre, RS</td>
                  <td><span class="badge bg-danger">Crime</span></td>
                  <td><img src="https://via.placeholder.com/40" class="rounded-circle" alt="Foto"></td>
                </tr>
                <tr>
                  <td>Pedro Costa</td>
                  <td>17</td>
                  <td>05/05/2023</td>
                  <td>Curitiba, PR</td>
                  <td><span class="badge bg-success">Encontrado</span></td>
                  <td><img src="https://via.placeholder.com/40" class="rounded-circle" alt="Foto"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // 1. Gráfico de Linha - Desaparecidos por Dia
    fetch('back/consultas/desaparecidosPorDia.php')  // Fazendo a requisição para a API
        .then(response => response.json())
        .then(data => {
            const chart1 = new ApexCharts(document.querySelector("#chart-desaparecidos-dia"), {
                chart: {
                    type: 'line',
                    height: '100%',
                    toolbar: { show: false },
                    animations: { enabled: true }
                },
                series: [{
                    name: 'Desaparecidos',
                    data: data.data // Dados de desaparecidos por dia
                }],
                xaxis: {
                    categories: data.labels, // As datas dos últimos 30 dias
                    labels: { style: { colors: '#6c757d' } }
                },
                colors: ['#206bc4'],
                stroke: { width: 3, curve: 'smooth' },
                markers: { size: 5 },
                grid: { borderColor: '#e9ecef' },
                tooltip: {
                    y: { formatter: (val) => `${val} pessoas` }
                },
                responsive: [{
                    breakpoint: 768,
                    options: { chart: { height: 300 } }
                }]
            });
            chart1.render(); // Renderiza o gráfico
        })
        .catch(error => console.error('Erro ao carregar dados do gráfico:', error));
});
</script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
    // 2. Gráfico de Pizza - Status dos Desaparecidos
    fetch('back/consultas/statusDesaparecidos.php')  // Fazendo a requisição para a API
        .then(response => response.json())
        .then(data => {
            const chart2 = new ApexCharts(document.querySelector("#chart-status-desaparecidos"), {
                chart: {
                    type: 'donut',
                    height: '100%'
                },
                series: [data.encontrados, data.desaparecidos], // Dados de encontrados e desaparecidos
                labels: ['Encontrados', 'Desaparecidos'],
                colors: ['#5eba00', '#cd201f'],
                legend: { position: 'bottom' },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: () => (data.encontrados + data.desaparecidos).toString()
                                }
                            }
                        }
                    }
                },
                responsive: [{
                    breakpoint: 768,
                    options: { chart: { height: 300 } }
                }]
            });
            chart2.render(); // Renderiza o gráfico
        })
        .catch(error => console.error('Erro ao carregar dados do gráfico:', error));
});
    </script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
    // 3. Gráfico de Barras - Desaparecidos por Estado
    fetch('back/consultas/desaparecidoPorEstado.php')  // Fazendo a requisição para a API
        .then(response => response.json())
        .then(data => {
            const chart3 = new ApexCharts(document.querySelector("#chart-desaparecidos-estado"), {
                chart: {
                    type: 'bar',
                    height: '100%',
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Desaparecidos',
                    data: data.totais // Dados de desaparecidos por estado
                }],
                xaxis: {
                    categories: data.estados, // Estados onde ocorreram desaparecimentos
                    labels: { style: { colors: '#6c757d' } }
                },
                colors: ['#4299e1'],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                        columnWidth: '55%',
                    }
                },
                dataLabels: { enabled: false },
                grid: { borderColor: '#e9ecef' },
                tooltip: {
                    y: { formatter: (val) => `${val} pessoas` }
                },
                responsive: [{
                    breakpoint: 768,
                    options: { 
                        chart: { height: 400 },
                        plotOptions: { bar: { horizontal: true } }
                    }
                }]
            });
            chart3.render(); // Renderiza o gráfico
        })
        .catch(error => console.error('Erro ao carregar dados do gráfico:', error));
});
      </script>
  <script>
   document.addEventListener("DOMContentLoaded", function () {
    // 4. Gráfico de Área - Evolução de Cadastros
    fetch('back/consultas/desaparecidosAcumulado.php')  // Fazendo a requisição para a API
        .then(response => response.json())
        .then(data => {
            const chart4 = new ApexCharts(document.querySelector("#chart-evolucao-cadastros"), {
                chart: {
                    type: 'area',
                    height: '100%',
                    toolbar: { show: false },
                    stacked: false
                },
                series: [{
                    name: 'Total Acumulado',
                    data: data.data // Dados de cadastros acumulados por mês
                }],
                xaxis: {
                    categories: data.labels, // Meses (Jan, Fev, ...)
                    labels: { style: { colors: '#6c757d' } }
                },
                colors: ['#3d8bfd'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                grid: { borderColor: '#e9ecef' },
                tooltip: {
                    y: { formatter: (val) => `${val} pessoas` }
                },
                responsive: [{
                    breakpoint: 768,
                    options: { chart: { height: 300 } }
                }]
            });
            chart4.render(); // Renderiza o gráfico
        })
        .catch(error => console.error('Erro ao carregar dados do gráfico:', error));
});
</script>
</body>
</html>