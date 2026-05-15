-- Script de criação das tabelas para o banco existente
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    tipo_perfil ENUM('cidadao', 'empresa') DEFAULT 'cidadao',
    aceite_lgpd BOOLEAN DEFAULT TRUE,
    eco_pontos INT DEFAULT 0,
    bio_curriculo TEXT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pontos_impacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    tipo ENUM('coleta', 'horta', 'loja', 'troca') NOT NULL,
    status ENUM('degradado', 'em_restauracao', 'produtivo', 'ativo') DEFAULT 'ativo',
    criador_id INT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (criador_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS oportunidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    tipo ENUM('vaga', 'curso') NOT NULL,
    link_externo VARCHAR(255),
    criador_id INT,
    data_publicacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (criador_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS marketplace (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    tipo_anuncio ENUM('upcycling', 'troca_semente', 'doacao') NOT NULL,
    imagem VARCHAR(255),
    preco DECIMAL(10,2) DEFAULT 0.00,
    criador_id INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (criador_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    data_evento DATETIME NOT NULL,
    localizacao VARCHAR(255) NOT NULL,
    criador_id INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (criador_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS guia_educacional (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    conteudo TEXT NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS mapa_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ponto_id INT,
    usuario_id INT NOT NULL,
    motivo VARCHAR(100) NOT NULL,
    mensagem TEXT,
    status ENUM('pendente', 'resolvido') DEFAULT 'pendente',
    data_report TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Inserindo Dados Reais e Educativos
INSERT IGNORE INTO guia_educacional (titulo, conteudo, categoria) VALUES 
('Como iniciar uma composteira em casa', 'Passo a passo para transformar restos orgânicos em adubo rico para suas plantas...', 'Compostagem'),
('O que pode e o que não pode reciclar?', 'Nem todo plástico é reciclável. Entenda as categorias...', 'Reciclagem'),
('Economia Circular: O que é?', 'A economia circular propõe um novo olhar sobre o ciclo de vida dos produtos, priorizando a redução, o reúso e a reciclagem.', 'Sustentabilidade'),
('Horta em Apartamento', 'Dicas práticas para cultivar temperos e hortaliças em pequenos espaços usando vasos e luz solar.', 'Agricultura Urbana'),
('Consumo Consciente: Guia Prático', 'Pequenas mudanças no dia a dia podem reduzir drasticamente sua pegada ecológica. Aprenda como.', 'Educação Ambiental');

-- Pontos de Impacto Reais em São Paulo
INSERT IGNORE INTO pontos_impacto (titulo, descricao, latitude, longitude, tipo, status) VALUES 
('Ecoponto Olavo Alvim', 'Recebe entulho, móveis velhos e recicláveis. Rua Olavo Alvim, s/n - Mooca.', -23.55160000, -46.59850000, 'coleta', 'ativo'),
('Ecoponto Barra Funda', 'Ponto de descarte de resíduos volumosos e recicláveis. Rua Sólon, 843.', -23.52620000, -46.64330000, 'coleta', 'ativo'),
('Horta das Corujas', 'Horta comunitária pioneira na Vila Madalena. Praça Dolores Ibarruri.', -23.54840000, -46.69750000, 'horta', 'produtivo'),
('Horta do CCSP', 'Horta urbana no telhado do Centro Cultural São Paulo. Rua Vergueiro, 1000.', -23.57140000, -46.64020000, 'horta', 'produtivo'),
('Instituto Chão', 'Espaço de economia solidária e produtos orgânicos. Rua Harmonia, 123.', -23.55340000, -46.68740000, 'loja', 'ativo'),
('Bemglô', 'Loja com foco em produtos sustentáveis e slow fashion. Rua Oscar Freire.', -23.56390000, -46.66690000, 'loja', 'ativo'),
('Feira de Troca Vila Madalena', 'Ponto tradicional de trocas de livros e objetos. Praça Benedito Calixto.', -23.55580000, -46.68000000, 'troca', 'ativo');

-- Eventos para Maio 2026
INSERT IGNORE INTO eventos (titulo, descricao, data_evento, localizacao, criador_id) VALUES 
('Mutirão de Limpeza Movimento SP', 'Ação coletiva de limpeza e educação ambiental na região central.', '2026-05-10 09:00:00', 'Praça da Sé, SP', 1),
('Workshop de Compostagem Urbana', 'Aprenda a transformar seus resíduos em adubo de forma prática.', '2026-05-24 14:00:00', 'Centro Cultural São Paulo', 1);

-- Oportunidades de Voluntariado
INSERT IGNORE INTO oportunidades (titulo, descricao, tipo, link_externo, criador_id) VALUES 
('Voluntário Fundação Florestal', 'Apoio em Unidades de Conservação e trilhas ecológicas.', 'vaga', 'https://www.fflorestal.sp.gov.br', 1),
('Educador Ambiental - Instituto Limpa Brasil', 'Auxílio em campanhas de conscientização sobre resíduos.', 'vaga', 'https://limpabrasil.org', 1),
('Curso de Agricultura Urbana', 'Capacitação gratuita para moradores da periferia de SP.', 'curso', 'https://sampamaisrural.prefeitura.sp.gov.br', 1);

-- Marketplace de Exemplo
INSERT IGNORE INTO marketplace (titulo, descricao, tipo_anuncio, preco, criador_id) VALUES 
('Kit Compostagem Doméstica', 'Composteira completa em ótimo estado, ideal para apartamentos.', 'upcycling', 150.00, 1),
('Sementes de Manjericão Orgânico', 'Troco sementes de manjericão por sementes de tomate.', 'troca_semente', 0.00, 1),
('Muda de Jabuticabeira', 'Doação de muda pequena para quem tem espaço no quintal.', 'doacao', 0.00, 1);
