<?php session_start();
include 'includes/headerUser.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Desaparecido</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    
    <!-- Custom CSS -->
    <style>
 
        
        body {
            background-color:rgb(201, 214, 241);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
        }
        
        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
        }
        
        .form-section-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
        }
        
        .form-section-title i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .required-field::after {
            content: " *";
            color: var(--danger-color);
        }
        
        .photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px dashed #ddd;
            display: none;
        }
        
        .upload-area {
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .upload-area:hover {
            border-color: var(--primary-color);
            background-color: rgba(32, 107, 196, 0.05);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.5rem;
        }
        
        .btn-primary:hover {
            background-color: #1a5aad;
            border-color: #1a5aad;
        }
        
        .invalid-feedback {
            display: none;
            color: var(--danger-color);
            font-size: 0.875rem;
        }
        
        .was-validated .form-control:invalid, 
        .form-control.is-invalid {
            border-color: var(--danger-color);
        }
        
        .was-validated .form-control:invalid ~ .invalid-feedback, 
        .form-control.is-invalid ~ .invalid-feedback {
            display: block;
        }
        
        @media (max-width: 768px) {
            .photo-col {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Cadastrar Pessoa Desaparecida</h4>
                    </div>
                    
                    <div class="card-body">
                        <form id="desaparecidoForm" novalidate>
                            <!-- Seção 1: Dados Pessoais -->
                            <div class="form-section">
                                <h5 class="form-section-title">
                                    <i class="fas fa-id-card"></i>
                                    <span>Dados Pessoais</span>
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nome" class="form-label required-field">Nome Completo</label>
                                        <input type="text" class="form-control" id="nome" name="nome" required>
                                        <div class="invalid-feedback">Por favor, informe o nome completo.</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="apelido" class="form-label">Apelido (se tiver)</label>
                                        <input type="text" class="form-control" id="apelido" name="apelido">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="dataNascimento" class="form-label required-field">Data de Nascimento</label>
                                        <input type="date" class="form-control" id="dataNascimento" name="dataNascimento" required>
                                        <div class="invalid-feedback">Por favor, informe a data de nascimento.</div>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label for="idade" class="form-label required-field">Idade</label>
                                        <input type="number" class="form-control" id="idade" name="idade" min="0" max="120" required>
                                        <div class="invalid-feedback">Por favor, informe a idade correta.</div>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label for="sexo" class="form-label required-field">Sexo</label>
                                        <select class="form-select" id="sexo" name="sexo" required>
                                            <option value="" selected disabled>Selecione</option>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Feminino">Feminino</option>
                                            <option value="Outro">Outro</option>
                                        </select>
                                        <div class="invalid-feedback">Por favor, selecione o sexo.</div>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label for="corPele" class="form-label required-field">Cor/Raça</label>
                                        <select class="form-select" id="corPele" name="corPele" required>
                                            <option value="" selected disabled>Selecione</option>
                                            <option value="Branca">Branca</option>
                                            <option value="Preta">Preta</option>
                                            <option value="Parda">Parda</option>
                                            <option value="Amarela">Amarela</option>
                                            <option value="Indígena">Indígena</option>
                                        </select>
                                        <div class="invalid-feedback">Por favor, selecione a cor/raça.</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Seção 2: Foto -->
                            <div class="form-section">
                                <h5 class="form-section-title">
                                    <i class="fas fa-camera"></i>
                                    <span>Foto</span>
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-4 photo-col">
                                        <div class="upload-area" id="uploadArea">
                                            <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-muted"></i>
                                            <p class="mb-1">Clique para adicionar foto</p>
                                            <p class="small text-muted">Formatos: JPG, PNG (Máx. 5MB)</p>
                                            <input type="file" id="foto" name="foto" accept="image/*" class="d-none" required>
                                        </div>
                                        <div class="invalid-feedback">Por favor, adicione uma foto.</div>
                                    </div>
                                    
                                    <div class="col-md-8 d-flex align-items-center justify-content-center">
                                        <img id="photoPreview" class="photo-preview" alt="Pré-visualização da foto">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Seção 3: Características Físicas -->
                            <div class="form-section">
                                <h5 class="form-section-title">
                                    <i class="fas fa-user-tag"></i>
                                    <span>Características Físicas</span>
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="altura" class="form-label">Altura (cm)</label>
                                        <input type="number" class="form-control" id="altura" name="altura" min="30" max="250">
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label for="peso" class="form-label">Peso (kg)</label>
                                        <input type="number" class="form-control" id="peso" name="peso" min="2" max="300" step="0.1">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="corCabelo" class="form-label">Cor do Cabelo</label>
                                        <select class="form-select" id="corCabelo" name="corCabelo">
                                            <option value="" selected disabled>Selecione</option>
                                            <option value="Preto">Preto</option>
                                            <option value="Castanho">Castanho</option>
                                            <option value="Loiro">Loiro</option>
                                            <option value="Ruivo">Ruivo</option>
                                            <option value="Grisalho">Grisalho</option>
                                            <option value="Branco">Branco</option>
                                            <option value="Colorido">Colorido</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tipoCabelo" class="form-label">Tipo de Cabelo</label>
                                        <select class="form-select" id="tipoCabelo" name="tipoCabelo">
                                            <option value="" selected disabled>Selecione</option>
                                            <option value="Liso">Liso</option>
                                            <option value="Ondulado">Ondulado</option>
                                            <option value="Cacheado">Cacheado</option>
                                            <option value="Crespo">Crespo</option>
                                            <option value="Careca">Careca</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="corOlhos" class="form-label">Cor dos Olhos</label>
                                        <select class="form-select" id="corOlhos" name="corOlhos">
                                            <option value="" selected disabled>Selecione</option>
                                            <option value="Castanhos">Castanhos</option>
                                            <option value="Pretos">Pretos</option>
                                            <option value="Azuis">Azuis</option>
                                            <option value="Verdes">Verdes</option>
                                            <option value="Cinza">Cinza</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="marcas" class="form-label">Marcas ou Tatuagens</label>
                                    <textarea class="form-control" id="marcas" name="marcas" rows="2" placeholder="Descreva marcas, cicatrizes ou tatuagens distintivas"></textarea>
                                </div>
                            </div>
                            
                            <!-- Seção 4: Informações do Desaparecimento -->
                            <div class="form-section">
                                <h5 class="form-section-title">
                                    <i class="fas fa-search-location"></i>
                                    <span>Informações do Desaparecimento</span>
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dataDesaparecimento" class="form-label required-field">Data do Desaparecimento</label>
                                        <input type="date" class="form-control" id="dataDesaparecimento" name="dataDesaparecimento" required>
                                        <div class="invalid-feedback">Por favor, informe a data do desaparecimento.</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="localDesaparecimento" class="form-label required-field">Local do Desaparecimento</label>
                                        <input type="text" class="form-control" id="localDesaparecimento" name="localDesaparecimento" placeholder="Ex: Rua, número, bairro, cidade" required>
                                        <div class="invalid-feedback">Por favor, informe o local do desaparecimento.</div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="cidade" class="form-label required-field">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" required>
                                        <div class="invalid-feedback">Por favor, informe a cidade.</div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="estado" class="form-label required-field">Estado</label>
                                        <select class="form-select" id="estado" name="estado" required>
                                            <option value="" selected disabled>Selecione</option>
                                            <option value="AC">Acre</option>
                                            <option value="AL">Alagoas</option>
                                            <option value="AP">Amapá</option>
                                            <option value="AM">Amazonas</option>
                                            <option value="BA">Bahia</option>
                                            <option value="CE">Ceará</option>
                                            <option value="DF">Distrito Federal</option>
                                            <option value="ES">Espírito Santo</option>
                                            <option value="GO">Goiás</option>
                                            <option value="MA">Maranhão</option>
                                            <option value="MT">Mato Grosso</option>
                                            <option value="MS">Mato Grosso do Sul</option>
                                            <option value="MG">Minas Gerais</option>
                                            <option value="PA">Pará</option>
                                            <option value="PB">Paraíba</option>
                                            <option value="PR">Paraná</option>
                                            <option value="PE">Pernambuco</option>
                                            <option value="PI">Piauí</option>
                                            <option value="RJ">Rio de Janeiro</option>
                                            <option value="RN">Rio Grande do Norte</option>
                                            <option value="RS">Rio Grande do Sul</option>
                                            <option value="RO">Rondônia</option>
                                            <option value="RR">Roraima</option>
                                            <option value="SC">Santa Catarina</option>
                                            <option value="SP">São Paulo</option>
                                            <option value="SE">Sergipe</option>
                                            <option value="TO">Tocantins</option>
                                        </select>
                                        <div class="invalid-feedback">Por favor, selecione o estado.</div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="cep" class="form-label">CEP</label>
                                        <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="circunstancia" class="form-label">Circunstâncias do Desaparecimento</label>
                                    <textarea class="form-control" id="circunstancia" name="circunstancia" rows="3" placeholder="Descreva como ocorreu o desaparecimento"></textarea>
                                </div>
                            </div>
                            
                            <!-- Seção 5: Informações de Contato -->
                            <div class="form-section">
                                <h5 class="form-section-title">
                                    <i class="fas fa-address-book"></i>
                                    <span>Informações de Contato</span>
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="contatoNome" class="form-label required-field">Nome do Contato</label>
                                        <input type="text" class="form-control" id="contatoNome" name="contatoNome" required>
                                        <div class="invalid-feedback">Por favor, informe o nome do contato.</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="contatoParentesco" class="form-label required-field">Parentesco</label>
                                        <input type="text" class="form-control" id="contatoParentesco" name="contatoParentesco" placeholder="Ex: Mãe, Pai, Irmão, etc." required>
                                        <div class="invalid-feedback">Por favor, informe o parentesco.</div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="contatoTelefone" class="form-label required-field">Telefone</label>
                                        <input type="tel" class="form-control" id="contatoTelefone" name="contatoTelefone" required>
                                        <div class="invalid-feedback">Por favor, informe um telefone válido.</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="contatoEmail" class="form-label">E-mail</label>
                                        <input type="email" class="form-control" id="contatoEmail" name="contatoEmail">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </button>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Salvar Cadastro
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    
    <!-- Custom Script -->
    <script>
    $(document).ready(function() {
        // Máscaras para os campos
        $('#contatoTelefone').mask('(00) 00000-0000');
        $('#cep').mask('00000-000');
        
        // Pré-visualização da foto
        $('#uploadArea').click(function() {
            $('#foto').click();
        });
        
        $('#foto').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#photoPreview').attr('src', e.target.result).show();
                    $('#uploadArea').hide();
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Validação do formulário
        $('#desaparecidoForm').submit(function(e) {
            e.preventDefault();
            
            const form = this;
            if (form.checkValidity() === false) {
                e.stopPropagation();
            } else {
                // Simulação de envio - substituir por AJAX real
                alert('Formulário válido! Enviar para o servidor...');
                // Aqui você faria o $.ajax para enviar os dados
            }
            
            $(form).addClass('was-validated');
        });
        
        // Calcular idade automaticamente a partir da data de nascimento
        $('#dataNascimento').change(function() {
            const birthDate = new Date($(this).val());
            if (!isNaN(birthDate.getTime())) {
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                $('#idade').val(age);
            }
        });
    });
    </script>
</body>
</html>