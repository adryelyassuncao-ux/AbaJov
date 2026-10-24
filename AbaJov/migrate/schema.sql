CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    email VARCHAR(200) UNIQUE NOT NULL,
    tipo ENUM('jovem', 'empresa') NOT NULL,
    receber_notificacao_email BOOLEAN DEFAULT TRUE,
    data_criacao DATE NOT NULL
);

CREATE TABLE jovem (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome VARCHAR(200) NOT NULL,
    CPF VARCHAR(11) UNIQUE NOT NULL,
    data_nascimento DATE NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    nacionalidade VARCHAR(100),
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    genero ENUM('feminino', 'masculino', 'prefiro_nao_informar', 'outro') DEFAULT 'prefiro_nao_informar',
    PCD BOOLEAN DEFAULT FALSE,
    perfil_visivel BOOLEAN DEFAULT FALSE,
    ultimo_acesso DATE,
    consentimento_responsavel BOOLEAN DEFAULT FALSE,
    nome_responsavel VARCHAR(200),
    contato_responsavel VARCHAR(15),
    FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE
);

CREATE TABLE empresa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome_juridico VARCHAR(200) NOT NULL,
    nome_social VARCHAR(200),
    CNPJ VARCHAR(14) UNIQUE NOT NULL,
    ramo_atuacao VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE
);

CREATE TABLE curriculo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jovem_id INT NOT NULL,
    objetivo TEXT NOT NULL,
    escolaridade VARCHAR(100) NOT NULL,
    nome_instituicao VARCHAR(200) NOT NULL,
    status_escolaridade ENUM('em_andamento', 'concluido', 'trancado'),
    previsao_termino DATE,
    turno ENUM('manha', 'tarde', 'noite', 'integral'),
    habilidades TEXT,
    curso TEXT,
    experiencia TEXT,
    FOREIGN KEY (jovem_id) REFERENCES jovem (id) ON DELETE CASCADE
);

CREATE TABLE vagas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    requisitos TEXT,
    turno ENUM('manha', 'tarde', 'noite', 'integral'),
    beneficios TEXT,
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    faixa_etaria ENUM('14_15', '16_17', '18_24') NOT NULL,
    nivel_ensino ENUM('fundamental', 'medio', 'superior') NOT NULL,
    modalidade ENUM('presencial', 'hibrido', 'home_office') NOT NULL,
    tipo_contrato ENUM('CLT', 'estagio', 'freelancer') NOT NULL,
    carga_horaria ENUM('4h', '6h', '8h') NOT NULL,
    status ENUM('aberta', 'fechada', 'pausada') DEFAULT 'aberta',
    data_criacao DATE NOT NULL,
    FOREIGN KEY (empresa_id) REFERENCES empresa (id) ON DELETE CASCADE
);

CREATE TABLE candidatura (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_vaga INT NOT NULL,
    id_jovem INT NOT NULL,
    data_candidatura DATE NOT NULL,
    status ENUM('nao_visto', 'em_andamento', 'proxima_etapa', 'encerrado') DEFAULT 'nao_visto',
    resposta_jovem ENUM('pendente', 'aceito', 'recusado') DEFAULT 'pendente',
    prazo_resposta DATE,
    FOREIGN KEY (id_vaga) REFERENCES vagas (id) ON DELETE CASCADE,
    FOREIGN KEY (id_jovem) REFERENCES jovem (id) ON DELETE CASCADE
);

CREATE TABLE vaga_salva (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jovem_id INT NOT NULL,
    vaga_id INT NOT NULL,
    data_salvo DATE NOT NULL,
    FOREIGN KEY (jovem_id) REFERENCES jovem (id) ON DELETE CASCADE,
    FOREIGN KEY (vaga_id) REFERENCES vagas (id) ON DELETE CASCADE
);
