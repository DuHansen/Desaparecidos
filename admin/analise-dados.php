<?php
session_start();
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Análise de Desaparecidos</title>
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --primary: #206bc4;
            --secondary: #5eba00;
            --danger: #cd201f;
            --warning: #f76707;
            --info: #4299e1;
            --dark: #1a1a1a;
            --light: #f8f9fa;
            --gray: #6c757d;
            --gray-light: #e9ecef;
            --border-radius: 0.375rem;
            --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fb;
            color: #333;
            line-height: 1.6;
        }
        
        .container-xl {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .page-body {
            padding: 20px 0;
        }
        
        .row-deck {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -12px;
        }
        
        .row-cards {
            margin-bottom: 20px;
        }
        
        .col-sm-6, .col-lg-3, .col-xl-6, .col-xl-12 {
            padding: 0 12px;
            margin-bottom: 24px;
        }
        
        .col-sm-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        
        .col-lg-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }
        
        .col-xl-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        
        .col-xl-12 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .card {
            background: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background-color: transparent;
        }
        
        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
            color: var(--dark);
        }
        
        .card-body {
            padding: 1.5rem;
            flex: 1;
        }
        
        .card-table {
            padding: 0;
        }
        
        .subheader {
            font-size: 0.875rem;
            color: var(--gray);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .h1 {
            font-size: 2rem;
            font-weight: 600;
            margin: 0;
            color: var(--dark);
        }
        
        .text-yellow {
            color: #f59f00;
        }
        
        .text-success {
            color: var(--secondary);
        }
        
        .text-danger {
            color: var(--danger);
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th, .table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .table thead th {
            border-bottom: 2px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            color: var(--gray);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.01);
        }
        
        .table img {
            border-radius: 50%;
            object-fit: cover;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25em 0.5em;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }
        
        .badge-success {
            background-color: rgba(94, 186, 0, 0.1);
            color: var(--secondary);
        }
        
        .badge-warning {
            background-color: rgba(247, 103, 7, 0.1);
            color: var(--warning);
        }
        
        .badge-danger {
            background-color: rgba(205, 32, 31, 0.1);
            color: var(--danger);
        }
        
        .card-body-scrollable {
            overflow-y: auto;
            max-height: 400px;
        }
        
        /* Responsividade */
        @media (max-width: 1200px) {
            .col-lg-3 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
        
        @media (max-width: 768px) {
            .col-sm-6, .col-lg-3, .col-xl-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            
            .card-body {
                padding: 1rem;
            }
        }
        
        /* Animações */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div id="conteudo" class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            
            <!-- Card 1: Total de Desaparecidos -->
            <div class="col-sm-6 col-lg-3 fade-in">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader">Total de Desaparecidos</div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2">1,248</div>
                            <div class="me-auto">
                                <span class="text-success d-inline-flex align-items-center lh-1">
                                    <i class="fas fa-arrow-up me-1"></i> 12.5%
                                </span>
                            </div>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-primary" style="width: 75%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card 2: Encontrados -->
            <div class="col-sm-6 col-lg-3 fade-in">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader">Pessoas Encontradas</div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2">876</div>
                            <div class="me-auto">
                                <span class="text-success d-inline-flex align-items-center lh-1">
                                    <i class="fas fa-arrow-up me-1"></i> 8.3%
                                </span>
                            </div>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-success" style="width: 65%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card 3: Desaparecidos Recentes -->
            <div class="col-sm-6 col-lg-3 fade-in">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader">Desaparecidos (7 dias)</div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2">84</div>
                            <div class="me-auto">
                                <span class="text-danger d-inline-flex align-items-center lh-1">
                                    <i class="fas fa-arrow-down me-1"></i> 3.2%
                                </span>
                            </div>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-warning" style="width: 45%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card 4: Taxa de Resolução -->
            <div class="col-sm-6 col-lg-3 fade-in">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader">Taxa de Resolução</div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2">70.2%</div>
                            <div class="me-auto">
                                <span class="text-success d-inline-flex align-items-center lh-1">
                                    <i class="fas fa-arrow-up me-1"></i> 5.1%
                                </span>
                            </div>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-info" style="width: 70%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gráfico 1: Desaparecidos por Dia -->
            <div class="col-xl-6 fade-in">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Desaparecidos por Dia (Últimos 30 dias)</h3>
                    </div>
                    <div class="card-body">
                        <div id="chart-desaparecidos-dia" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Gráfico 2: Status dos Desaparecidos -->
            <div class="col-xl-6 fade-in">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Status dos Desaparecidos</h3>
                    </div>
                    <div class="card-body">
                        <div id="chart-status-desaparecidos" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Gráfico 3: Desaparecidos por Estado -->
            <div class="col-xl-12 fade-in">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Desaparecidos por Estado (Top 10)</h3>
                    </div>
                    <div class="card-body">
                        <div id="chart-desaparecidos-estado" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Gráfico 4: Evolução de Cadastros -->
            <div class="col-xl-12 fade-in">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Evolução de Cadastros (Acumulado)</h3>
                    </div>
                    <div class="card-body">
                        <div id="chart-evolucao-cadastros" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Tabela de Desaparecidos Recentes -->
            <div class="col-xl-12 fade-in">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="card-title">Desaparecidos Recentes</div>
                    </div>
                    <div class="card-table card-body-scrollable table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Idade</th>
                                    <th>Data Desaparecimento</th>
                                    <th>Local</th>
                                    <th>Status</th>
                                    <th>Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>João Silva</td>
                                    <td>15 anos</td>
                                    <td>15/05/2023</td>
                                    <td>São Paulo, SP</td>
                                    <td><span class="badge badge-warning">Desaparecido</span></td>
                                    <td><img src="https://via.placeholder.com/40" width="40" height="40" alt="Foto"></td>
                                </tr>
                                <tr>
                                    <td>Maria Oliveira</td>
                                    <td>32 anos</td>
                                    <td>12/05/2023</td>
                                    <td>Rio de Janeiro, RJ</td>
                                    <td><span class="badge badge-success">Encontrado</span></td>
                                    <td><img src="https://via.placeholder.com/40" width="40" height="40" alt="Foto"></td>
                                </tr>
                                <tr>
                                    <td>Carlos Souza</td>
                                    <td>8 anos</td>
                                    <td>10/05/2023</td>
                                    <td>Belo Horizonte, MG</td>
                                    <td><span class="badge badge-warning">Desaparecido</span></td>
                                    <td><img src="https://via.placeholder.com/40" width="40" height="40" alt="Foto"></td>
                                </tr>
                                <tr>
                                    <td>Ana Pereira</td>
                                    <td>45 anos</td>
                                    <td>08/05/2023</td>
                                    <td>Porto Alegre, RS</td>
                                    <td><span class="badge badge-danger">Crime</span></td>
                                    <td><img src="https://via.placeholder.com/40" width="40" height="40" alt="Foto"></td>
                                </tr>
                                <tr>
                                    <td>Pedro Costa</td>
                                    <td>17 anos</td>
                                    <td>05/05/2023</td>
                                    <td>Curitiba, PR</td>
                                    <td><span class="badge badge-success">Encontrado</span></td>
                                    <td><img src="https://via.placeholder.com/40" width="40" height="40" alt="Foto"></td>
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
    const chart1 = new ApexCharts(document.querySelector("#chart-desaparecidos-dia"), {
        chart: {
            type: 'line',
            height: '100%',
            toolbar: { show: false },
            animations: { enabled: true }
        },
        series: [{
            name: 'Desaparecidos',
            data: [12, 15, 8, 10, 7, 14, 18, 20, 15, 12, 9, 13, 16, 11, 8, 10, 14, 17, 19, 15, 12, 10, 13, 16, 18, 14, 11, 9, 12, 15]
        }],
        xaxis: {
            categories: Array.from({length: 30}, (_, i) => `${i+1}/05/2023`),
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
    chart1.render();
    
    // 2. Gráfico de Pizza - Status dos Desaparecidos
    const chart2 = new ApexCharts(document.querySelector("#chart-status-desaparecidos"), {
        chart: {
            type: 'donut',
            height: '100%'
        },
        series: [876, 372],
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
                            formatter: () => '1,248'
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
    chart2.render();
    
    // 3. Gráfico de Barras - Desaparecidos por Estado
    const chart3 = new ApexCharts(document.querySelector("#chart-desaparecidos-estado"), {
        chart: {
            type: 'bar',
            height: '100%',
            toolbar: { show: false }
        },
        series: [{
            name: 'Desaparecidos',
            data: [320, 245, 187, 156, 132, 98, 87, 76, 65, 54]
        }],
        xaxis: {
            categories: ['SP', 'RJ', 'MG', 'RS', 'PR', 'SC', 'BA', 'PE', 'CE', 'DF'],
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
    chart3.render();
    
    // 4. Gráfico de Área - Evolução de Cadastros
    const chart4 = new ApexCharts(document.querySelector("#chart-evolucao-cadastros"), {
        chart: {
            type: 'area',
            height: '100%',
            toolbar: { show: false },
            stacked: false
        },
        series: [{
            name: 'Total Acumulado',
            data: [120, 235, 320, 430, 520, 635, 750, 870, 980, 1050, 1170, 1248]
        }],
        xaxis: {
            categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
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
    chart4.render();
});
</script>
</body>
</html>