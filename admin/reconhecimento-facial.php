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
        if (!imgPreview.src || imgPreview.classList.contains('d-none')) {
            alert('Por favor, selecione uma imagem primeiro.');
            return;
        }

        // Mostrar loading
        document.getElementById('results').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p class="mt-2">Processando imagem...</p>
            </div>
        `;

        try {
            // Converter para FormData
            const formData = new FormData();
            
            // Se for URL
            if (imgPreview.src.startsWith('http')) {
                const response = await fetch(imgPreview.src);
                const blob = await response.blob();
                formData.append('image', blob, 'image.jpg');
            } 
            // Se for base64
            else if (imgPreview.src.startsWith('data:')) {
                const base64Response = await fetch(imgPreview.src);
                const blob = await base64Response.blob();
                formData.append('image', blob, 'image.jpg');
            }

            // Enviar para o servidor
            const response = await fetch('processar-imagem.php', {
                method: 'POST',
                body: formData
            });

            // Verificar se a resposta é JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error(`Resposta inválida do servidor: ${text.substring(0, 100)}`);
            }

            const data = await response.json();

            // Verificar erros na resposta
            if (data.error) {
                throw new Error(data.error);
            }

            // Mostrar resultados
            displayResults(data);

        } catch (error) {
            console.error('Erro:', error);
            document.getElementById('results').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Erro ao processar: ${error.message}
                </div>
            `;
        }
    }

    function displayResults(matches) {
        let resultsHTML = '';
        
        if (matches.length === 0) {
            resultsHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Nenhuma correspondência significativa encontrada.
                </div>
            `;
        } else {
            // Fallback SVG em base64 (será usado se a imagem não carregar)
            const fallbackSvg = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCI+PHJlY3Qgd2lkdGg9IjE1MCIgaGVpZ2h0PSIxNTAiIGZpbGw9IiNlZWVlZWUiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE0IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBhbGlnbm1lbnQtYmFzZWxpbmU9Im1pZGRsZSIgZmlsbD0iIzk5OSI+SW1hZ2VtIG7Do28gZGlzcG9uw612ZWw8L3RleHQ+PC9zdmc+';

            matches.forEach((match, index) => {
                // Extrai os dados do desaparecido (com valores padrão para evitar undefined)
                const nome = match.nome || 'Nome não disponível';
                const foto = match.foto || ''; // Usa a URL completa da foto
                const idade = match.idade || 'Idade não informada';
                const cidade = match.cidade || 'Cidade desconhecida';
                const desaparecidoEm = match.desaparecidoEm || 'Data não informada';
                const accuracy = match.accuracy || 0; // Adicionado para compatibilidade

                // Determina se é a melhor correspondência (se tiver accuracy)
                const isBestMatch = index === 0 && accuracy > 70;
                const cardClass = isBestMatch ? 'border-primary border-2' : '';

                // Cor da barra de progresso (se tiver accuracy)
                const progressBarClass = accuracy > 70 ? 'success' : 
                                      accuracy > 50 ? 'warning' : 'danger';

                resultsHTML += `
                    <div class="card match-card mb-3 ${cardClass}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="image-container" style="position: relative; padding-top: 100%;"> 
                                        <img src="${foto}" class="img-thumbnail w-100" style="position: absolute; top: 0; left: 0; height: 100%; object-fit: cover;" alt="${nome}" onerror="this.onerror=null;this.src='${fallbackSvg}'">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    ${isBestMatch ? '<span class="badge bg-primary mb-2">Melhor correspondência</span>' : ''}
                                    <h5>${nome}</h5>
                                    
                                    <div class="mb-2">
                                        <span class="text-muted small"><i class="fas fa-birthday-cake me-1"></i> ${idade} anos</span><br>
                                        <span class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i> ${cidade}</span><br>
                                        <span class="text-muted small"><i class="fas fa-calendar-alt me-1"></i> Desaparecido em: ${desaparecidoEm}</span>
                                    </div>
                                    
                                    ${accuracy ? `
                                    <div class="progress mt-2" style="height: 10px;">
                                        <div class="progress-bar bg-${progressBarClass}" role="progressbar" style="width: ${accuracy}%" aria-valuenow="${accuracy}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted">Probabilidade de correspondência</small>
                                        <small class="fw-bold">${accuracy}%</small>
                                    </div>
                                    ` : ''}
                                    
                                    <button class="btn btn-outline-primary btn-sm mt-3" onclick="viewDetails('${escapeHtml(nome)}')">
                                        <i class="fas fa-info-circle me-1"></i> Ver detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        
        document.getElementById('results').innerHTML = resultsHTML;
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
