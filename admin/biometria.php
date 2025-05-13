<?php include 'includes/headerUser.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Biometria</title>
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row mb-4">
      <div class="col">
        <h1 class="display-6 fw-bold">
          <i class="fas fa-robot text-primary me-2"></i> 
          Biometria Inteligente
        </h1>
        <p class="text-muted">Utilize biometria para identificar desaparecidos</p>
      </div>
    </div>
    
    <div class="row g-4">
      <!-- Opções de Processamento -->
      <div class="col-md-4">
        <div class="card card-feature p-4 text-center">
          <i class="fas fa-camera feature-icon"></i>
          <h4>Capturar</h4>
          <p>Use reconhecimento do celular</p>
          <button class="btn btn-primary w-100" id="startCamera">
            <i class="fas fa-video me-2"></i> Ativar 
          </button>
        </div>
      </div>    
    <!-- Área de Processamento -->
    <div class="row mt-5">
      <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-white">
            <h5 class="mb-0">
              <i class="fas fa-image me-2"></i> 
              Área de Processamento
            </h5>
          </div>
          <div class="card-body">
            <div id="videoContainer" class="mb-3 d-none">
              <video id="video" autoplay playsinline></video>
              <canvas id="canvas"></canvas>
            </div>
            
            <div id="imageContainer" class="text-center">
              <img id="imagePreview" class="img-fluid mb-3 d-none">
              <div id="noImageMessage" class="text-center py-5">
                <i class="fas fa-image fa-4x text-muted mb-3"></i>
                <p class="text-muted">Nenhuma imagem selecionada</p>
              </div>
            </div>
            
            <div class="d-flex justify-content-center gap-3">
              <button id="processImage" class="btn btn-primary" disabled>
                <i class="fas fa-brain me-2"></i> Processar Biometria
              </button>
              <button id="captureImage" class="btn btn-secondary d-none">
                <i class="fas fa-camera me-2"></i> Analisar
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-4">
        <div class="card shadow-sm">
          <div class="card-header bg-white">
            <h5 class="mb-0">
              <i class="fas fa-chart-line me-2"></i> 
              Resultados da Análise
            </h5>
          </div>
          <div class="card-body">
            <div id="loadingIndicator" class="text-center py-4 d-none">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Processando...</span>
              </div>
              <p class="mt-3 mb-0">Analisando biometrias e buscando correspondências...</p>
              <div class="progress mt-3">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
              </div>
            </div>
            
            <div id="results">
              <div class="text-center py-5">
                <i class="fas fa-robot fa-3x text-muted mb-3"></i>
                <p class="text-muted">Os resultados aparecerão aqui após o processamento</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Detalhes -->
  <div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalhes da Correspondência</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <img id="matchPhoto" src="" class="img-fluid rounded mb-3">
              <h6>Informações da Foto:</h6>
              <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item d-flex justify-content-between">
                  <span>Qualidade:</span>
                  <span id="matchQuality" class="badge bg-primary">85%</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span>Pontos Faciais:</span>
                  <span id="matchPoints" class="badge bg-primary">68</span>
                </li>
              </ul>
            </div>
            <div class="col-md-6">
              <h5 id="matchName" class="mb-3"></h5>
              <div class="mb-3">
                <h6>Biometria</h6>
                <div class="progress mb-2">
                  <div id="matchAccuracyBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
                <small class="text-muted" id="matchAccuracyText">Correspondência: 0%</small>
              </div>
              
              <h6>Informações Pessoais:</h6>
              <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item">
                  <i class="fas fa-birthday-cake me-2"></i>
                  <span id="matchAge"></span> anos
                </li>
                <li class="list-group-item">
                  <i class="fas fa-map-marker-alt me-2"></i>
                  Desaparecido em <span id="matchLocation"></span>
                </li>
                <li class="list-group-item">
                  <i class="fas fa-calendar-day me-2"></i>
                  <span id="matchDate"></span>
                </li>
              </ul>
              
              <button class="btn btn-primary w-100 mt-2">
                <i class="fas fa-eye me-2"></i> Ver Perfil Completo
              </button>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-primary">
            <i class="fas fa-flag me-2"></i> Reportar Encontro
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- FaceAPI.js for facial recognition -->
  <script src="https://justadudewhohacks.github.io/face-api.js/face-api.min.js"></script>
  
  <script>
  // Variáveis globais
  let stream = null;
  
  // Inicializar modelos de IA
  async function loadModels() {
    try {
      await faceapi.nets.tinyFaceDetector.loadFromUri('models');
      await faceapi.nets.faceLandmark68Net.loadFromUri('models');
      await faceapi.nets.faceRecognitionNet.loadFromUri('models');
      console.log('Modelos de IA carregados com sucesso');
    } catch (error) {
      console.error('Erro ao carregar modelos:', error);
      alert('Erro ao carregar os modelos de IA. Por favor, recarregue a página.');
    }
  }
  
  // Iniciar câmera
  async function startCamera() {
    try {
      stream = await navigator.mediaDevices.getUserMedia({ 
        video: { 
          width: 640, 
          height: 480,
          facingMode: 'environment' 
        }, 
        audio: false 
      });
      
      const video = document.getElementById('video');
      video.srcObject = stream;
      document.getElementById('videoContainer').classList.remove('d-none');
      document.getElementById('captureImage').classList.remove('d-none');
      
      // Processar vídeo em tempo real
      video.addEventListener('play', () => {
        const canvas = document.getElementById('canvas');
        const displaySize = { width: video.width, height: video.height };
        faceapi.matchDimensions(canvas, displaySize);
        
        setInterval(async () => {
          const detections = await faceapi.detectAllFaces(
            video, 
            new faceapi.TinyFaceDetectorOptions()
          ).withFaceLandmarks();
          
          const resizedDetections = faceapi.resizeResults(detections, displaySize);
          canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
          faceapi.draw.drawDetections(canvas, resizedDetections);
          faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
        }, 100);
      });
    } catch (error) {
      console.error('Erro ao acessar a câmera:', error);
      alert('Não foi possível acessar a câmera. Verifique as permissões.');
    }
  }
  
  // Capturar imagem da câmera
  function captureImage() {
    const video = document.getElementById('video');
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    
    const imageData = canvas.toDataURL('image/jpeg');
    displayImageForProcessing(imageData);
  }
  
  // Exibir imagem para processamento
  function displayImageForProcessing(imageSrc) {
    const imgPreview = document.getElementById('imagePreview');
    imgPreview.src = imageSrc;
    imgPreview.classList.remove('d-none');
    document.getElementById('noImageMessage').classList.add('d-none');
    document.getElementById('processImage').disabled = false;
    
    // Parar câmera se estiver ativa
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
      document.getElementById('videoContainer').classList.add('d-none');
      document.getElementById('captureImage').classList.add('d-none');
      stream = null;
    }
  }
  
  // Processar imagem com IA
  async function processImage() {
    const imgPreview = document.getElementById('imagePreview');
    if (!imgPreview.src || imgPreview.classList.contains('d-none')) {
      alert('Por favor, selecione uma imagem primeiro.');
      return;
    }
    
    // Mostrar loading
    document.getElementById('loadingIndicator').classList.remove('d-none');
    document.getElementById('results').innerHTML = '';
    
    try {
      // Simular processamento (substituir por chamada real à API)
      setTimeout(() => {
        showResults();
      }, 3000);
      
      // Processamento real com FaceAPI (exemplo)
      /*
      const image = await faceapi.fetchImage(imgPreview.src);
      const detections = await faceapi.detectAllFaces(image)
        .withFaceLandmarks()
        .withFaceDescriptors();
      
      // Aqui você enviaria os descritores para seu backend
      // para comparar com o banco de dados de desaparecidos
      */
      
    } catch (error) {
      console.error('Erro ao processar imagem:', error);
      alert('Erro ao processar a imagem. Por favor, tente novamente.');
    }
  }
  
  // Mostrar resultados (simulados)
  function showResults() {
    document.getElementById('loadingIndicator').classList.add('d-none');
    
    // Dados simulados - substituir por dados reais da sua API
    const matches = [
      {
        id: 1,
        name: "ARNOLDO STEFFEN",
        age: 111,
        location: "PORTO VELHO",
        date: "01/01/1978",
        photo: "https://devs.pc.sc.gov.br/servicos/desaparecidos/images/15891708/d3bd6a69-23ec-41c3-8e4f-5c6d2ed4fe5c.jpg",
        accuracy: 87,
        quality: "Boa",
        points: 72
      },
      {
        id: 2,
        name: "ABEL DOMINONI SOBRINHO",
        age: 79,
        location: "SÃO FRANCISCO DO SUL",
        date: "14/12/1976",
        photo: "https://devs.pc.sc.gov.br/servicos/desaparecidos/images/3710221/03deb709-4f57-4b48-9fd2-aa49263c7f31.jpg",
        accuracy: 65,
        quality: "Média",
        points: 58
      }
    ];
    
    if (matches.length === 0) {
      document.getElementById('results').innerHTML = `
        <div class="alert alert-warning">
          <i class="fas fa-exclamation-triangle me-2"></i>
          Nenhuma correspondência encontrada em nossa base de dados.
        </div>
      `;
      return;
    }
    
    let resultsHTML = '<h6 class="mb-3">Possíveis Correspondências:</h6>';
    
    matches.forEach(match => {
      resultsHTML += `
        <div class="card match-card mb-3" onclick="showMatchDetails(${match.id})" style="cursor: pointer;">
          <div class="card-body">
            <div class="row">
              <div class="col-3">
                <img src="${match.photo}" class="img-thumbnail">
              </div>
              <div class="col-9">
                <h6>${match.name}</h6>
                <div class="d-flex justify-content-between small">
                  <span><i class="fas fa-birthday-cake me-1"></i> ${match.age} anos</span>
                  <span><i class="fas fa-map-marker-alt me-1"></i> ${match.location}</span>
                </div>
                <div class="progress mt-2" style="height: 6px;">
                  <div class="progress-bar" role="progressbar" style="width: ${match.accuracy}%"></div>
                </div>
                <small class="text-muted">Correspondência: ${match.accuracy}%</small>
              </div>
            </div>
          </div>
        </div>
      `;
    });
    
    document.getElementById('results').innerHTML = resultsHTML;
  }
  
  // Mostrar detalhes da correspondência
  function showMatchDetails(matchId) {
    // Aqui você buscaria os detalhes completos do desaparecido
    // Estamos usando dados simulados para demonstração
    const matchDetails = {
      id: 1,
      name: "ARNOLDO STEFFEN",
      age: 111,
      location: "PORTO VELHO",
      date: "01/01/1978",
      photo: "https://devs.pc.sc.gov.br/servicos/desaparecidos/images/15891708/d3bd6a69-23ec-41c3-8e4f-5c6d2ed4fe5c.jpg",
      accuracy: 87,
      quality: "Boa",
      points: 72,
      details: "Desaparecido desde 1978. Última vez visto no centro de Porto Velho."
    };
    
    // Preencher modal com os dados
    document.getElementById('matchName').textContent = matchDetails.name;
    document.getElementById('matchPhoto').src = matchDetails.photo;
    document.getElementById('matchAge').textContent = matchDetails.age;
    document.getElementById('matchLocation').textContent = matchDetails.location;
    document.getElementById('matchDate').textContent = matchDetails.date;
    document.getElementById('matchQuality').textContent = matchDetails.quality;
    document.getElementById('matchPoints').textContent = matchDetails.points;
    document.getElementById('matchAccuracyBar').style.width = `${matchDetails.accuracy}%`;
    document.getElementById('matchAccuracyText').textContent = `Correspondência: ${matchDetails.accuracy}%`;
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    modal.show();
  }
  
  // Event Listeners
  document.addEventListener('DOMContentLoaded', () => {
    loadModels();
    
    // Eventos dos botões
    document.getElementById('startCamera').addEventListener('click', startCamera);
    document.getElementById('captureImage').addEventListener('click', captureImage);
    document.getElementById('processImage').addEventListener('click', processImage);
    
    // Upload de imagem
    document.getElementById('imageUpload').addEventListener('change', function(e) {
      if (e.target.files.length > 0) {
        const file = e.target.files[0];
        const reader = new FileReader();
        
        reader.onload = function(event) {
          displayImageForProcessing(event.target.result);
        };
        
        reader.readAsDataURL(file);
      }
    });
    
    // Carregar por URL
    document.getElementById('loadFromUrl').addEventListener('click', function() {
      const url = document.getElementById('imageUrl').value.trim();
      if (url) {
        displayImageForProcessing(url);
      } else {
        alert('Por favor, insira uma URL válida.');
      }
    });
  });
  </script>
</body>
</html>