<?php include 'includes/headerUser.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reconhecimento Facial | Sistema Desaparecidos</title>
  
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  
  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row mb-4">
      <div class="col">
        <h1 class="display-6 fw-bold">
          <i class="fas fa-robot text-primary me-2"></i> 
          Reconhecimento Facial Inteligente
        </h1>
        <p class="text-muted">Utilize inteligência artificial para identificar desaparecidos</p>
      </div>
    </div>

    <div class="row g-4">
      <!-- Opções de Processamento -->
      <div class="col-md-4">
        <div class="card card-feature p-4 text-center">
          <i class="fas fa-camera feature-icon"></i>
          <h4>Captura ao Vivo</h4>
          <p>Use sua câmera em tempo real</p>
          <button class="btn btn-primary w-100" id="startCamera">
            <i class="fas fa-video me-2"></i> Ativar Câmera
          </button>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card card-feature p-4 text-center">
          <i class="fas fa-upload feature-icon"></i>
          <h4>Upload de Imagem</h4>
          <p>Envie uma foto para análise</p>
          <input type="file" id="imageUpload" accept="image/*" class="d-none">
          <button class="btn btn-primary w-100" onclick="document.getElementById('imageUpload').click()">
            <i class="fas fa-cloud-upload-alt me-2"></i> Selecionar Arquivo
          </button>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card card-feature p-4 text-center">
          <i class="fas fa-link feature-icon"></i>
          <h4>URL da Imagem</h4>
          <p>Analise uma imagem da web</p>
          <div class="input-group">
            <input type="text" id="imageUrl" class="form-control" placeholder="Cole a URL aqui">
            <button class="btn btn-primary" id="loadFromUrl">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
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
              <button id="processImage" class="btn btn-primary" disabled onclick="processImage()">
                <i class="fas fa-brain me-2"></i> Processar Imagem
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

  <script src="https://justadudewhohacks.github.io/face-api.js/face-api.min.js"></script>

  <script>
    let stream = null;
    let faceDetectionModelLoaded = false;

    // Carregar modelos de detecção facial
    async function loadFaceDetectionModels() {
        try {
            await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
            faceDetectionModelLoaded = true;
        } catch (error) {
            console.error('Erro ao carregar modelos de detecção facial:', error);
            // Continuar mesmo sem os modelos (usando comparação por histograma)
        }
    }

    // Chamar a função para carregar os modelos quando a página carregar
    document.addEventListener('DOMContentLoaded', loadFaceDetectionModels);

    // Iniciar câmera
    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    facingMode: 'environment' 
                }, 
                audio: false 
            });
            
            const video = document.getElementById('video');
            video.srcObject = stream;
            document.getElementById('videoContainer').classList.remove('d-none');
            document.getElementById('processImage').disabled = false;
            
            // Adicionar botão de captura
            const captureBtn = document.createElement('button');
            captureBtn.className = 'btn btn-success mt-3';
            captureBtn.innerHTML = '<i class="fas fa-camera me-2"></i> Capturar Imagem';
            captureBtn.onclick = captureImage;
            
            const videoContainer = document.getElementById('videoContainer');
            if (!videoContainer.querySelector('.btn-success')) {
                videoContainer.appendChild(captureBtn);
            }
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
        
        // Melhorar qualidade da imagem capturada
        const imageData = canvas.toDataURL('image/jpeg', 0.9);
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
            stream = null;
        }
    }

async function processImage() {
    const imgPreview = document.getElementById('imagePreview');
    const resultsContainer = document.getElementById('results');

    // Verificação básica da imagem
    if (!imgPreview || !imgPreview.src || imgPreview.src === "about:blank" || imgPreview.classList.contains('d-none')) {
        alert('Por favor, selecione uma imagem válida.');
        return;
    }

    // Mostrar spinner/loading
    resultsContainer.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="mt-2">Processando imagem...</p>
        </div>
    `;

    try {
        const formData = new FormData();

        // Convertendo o src em blob
        const responseBlob = await fetch(imgPreview.src);
        if (!responseBlob.ok) throw new Error("Erro ao carregar a imagem da visualização.");

        const blob = await responseBlob.blob();
        formData.append('file', blob, 'image.jpg');

        // Requisição à API FastAPI
        const response = await fetch('http://localhost:8000/comparar/', {
            method: 'POST',
            body: formData
        });

        const contentType = response.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            const text = await response.text();
            throw new Error(`Resposta inesperada do servidor: ${text.substring(0, 100)}`);
        }

        const data = await response.json();
        displayResults(data);

    } catch (error) {
        console.error('Erro:', error);
        resultsContainer.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Erro ao processar a imagem: ${error.message}
            </div>
        `;
    }
}
function displayResults(data) {
    let html = '';

    if (data.match) {
        html = `
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Correspondência encontrada!</strong><br><br>
                <b>Nome:</b> ${data.nome}<br>
                <b>Data desaparecimento:</b> ${data.data}<br>
                <b>Cidade:</b> ${data.cidade}<br><br>
                <img src="${data.foto}" alt="Foto da pessoa" class="img-thumbnail mt-2" style="max-width: 200px;">
            </div>
        `;
    } else {
        html = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-circle me-2"></i>
                Nenhuma correspondência encontrada.<br>
                <b>Distância:</b> ${data.distancia.toFixed(4)}
            </div>
        `;
    }

    document.getElementById('results').innerHTML = html;
}



    // Função auxiliar para escapar HTML (segurança)
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Função para visualizar detalhes (exemplo)
    function viewDetails(nome) {
        alert(`Detalhes de ${nome}\nEsta função pode ser expandida para mostrar mais informações.`);
    }

    // Event Listeners
    document.getElementById('startCamera').addEventListener('click', startCamera);
    document.getElementById('loadFromUrl').addEventListener('click', function() {
        const url = document.getElementById('imageUrl').value.trim();
        if (url) {
            // Verificar se é uma URL de imagem válida
            if (/\.(jpeg|jpg|gif|png)$/.test(url.toLowerCase())) {
                displayImageForProcessing(url);
            } else {
                alert('Por favor, insira uma URL de imagem válida (JPEG, JPG, GIF ou PNG).');
            }
        } else {
            alert('Por favor, insira uma URL válida.');
        }
    });

    document.getElementById('imageUpload').addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            
            // Verificar tipo de arquivo
            if (!file.type.match('image.*')) {
                alert('Por favor, selecione um arquivo de imagem.');
                return;
            }
            
            // Verificar tamanho do arquivo (máx 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('O arquivo é muito grande. Tamanho máximo permitido: 5MB.');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(event) {
                displayImageForProcessing(event.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
  </script>
</body>
</html>
