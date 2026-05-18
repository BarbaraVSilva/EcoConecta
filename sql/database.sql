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
    empresa_padrinho_id INT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (criador_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (empresa_padrinho_id) REFERENCES usuarios(id) ON DELETE SET NULL
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

CREATE TABLE IF NOT EXISTS doacoes_alimentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    horta_id INT NOT NULL,
    quantidade_kg DECIMAL(10, 2) NOT NULL,
    descricao TEXT,
    data_doacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (horta_id) REFERENCES pontos_impacto(id) ON DELETE CASCADE
);

-- ==========================================
-- NOVAS TABELAS: ECOLOJA (RECOMPENSAS & RESGATES)
-- ==========================================
CREATE TABLE IF NOT EXISTS recompensas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    custo_pontos INT NOT NULL,
    empresa_id INT NOT NULL,
    quantidade_disponivel INT DEFAULT 10,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS resgates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    recompensa_id INT NOT NULL,
    codigo_cupom VARCHAR(50) NOT NULL UNIQUE,
    status ENUM('ativo', 'utilizado') DEFAULT 'ativo',
    data_resgate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (recompensa_id) REFERENCES recompensas(id) ON DELETE CASCADE
);

-- ==========================================
-- NOVAS TABELAS: QUIZZES EDUCACIONAIS
-- ==========================================
CREATE TABLE IF NOT EXISTS guia_quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guia_id INT NOT NULL,
    pergunta VARCHAR(255) NOT NULL,
    opcao_a VARCHAR(150) NOT NULL,
    opcao_b VARCHAR(150) NOT NULL,
    opcao_c VARCHAR(150) NOT NULL,
    opcao_correta CHAR(1) NOT NULL,
    FOREIGN KEY (guia_id) REFERENCES guia_educacional(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS usuario_quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    guia_id INT NOT NULL,
    data_conclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (guia_id) REFERENCES guia_educacional(id) ON DELETE CASCADE
);

-- ==========================================
-- INSERÇÃO DE DADOS INICIAIS (SEED DATA)
-- ==========================================

-- Usuários Padrão
INSERT IGNORE INTO usuarios (id, nome, email, senha_hash, tipo_perfil, eco_pontos, bio_curriculo) VALUES
(1, 'Bárbara Silva', 'barbara@ecoconecta.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cidadao', 350, 'Estudante de engenharia ambiental apaixonada por hortas comunitárias e compostagem doméstica.'),
(2, 'EcoLógica Soluções', 'contato@ecologica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empresa', 0, 'Empresa focada em assessoria ambiental, gestão de resíduos sólidos e projetos de sustentabilidade urbana.');

-- Guias Educacionais
INSERT IGNORE INTO guia_educacional (id, titulo, conteudo, categoria) VALUES 
(1, 'Como iniciar uma composteira em casa', 'A compostagem é um processo biológico de valorização da matéria orgânica. Para começar, você precisa de um recipiente furado, uma camada de folhas secas (carbono) e uma camada de restos de alimento (nitrogênio). Mantenha a umidade equilibrada e revire uma vez por semana...', 'Compostagem'),
(2, 'O que pode e o que não pode reciclar?', 'Nem todo material que parece plástico é reciclável. Copos descartáveis sujos de café, fitas adesivas, papel carbono e cerâmicas não devem ir para o lixo reciclável. Já garrafas PET, latinhas de alumínio e caixas de papelão limpas são altamente valorizadas...', 'Reciclagem'),
(3, 'Economia Circular: O que é?', 'A economia circular propõe um novo ciclo de vida para os produtos. Em vez do modelo linear (extrair, produzir, descartar), ela foca em projetar produtos que possam ser desmontados, upcyclados e reinseridos na cadeia de valor, eliminando o desperdício...', 'Sustentabilidade'),
(4, 'Horta em Apartamento', 'Cultivar temperos em apartamento é simples. Escolha vasos com furos para drenagem, use substrato rico em matéria orgânica e garanta pelo menos 4 horas de sol direto diário. Alecrim, hortelã, manjericão e cebolinha adaptam-se perfeitamente...', 'Agricultura Urbana'),
(5, 'Consumo Consciente: Guia Prático', 'Consumir de forma consciente significa avaliar o impacto de cada compra. Priorize produtores locais, compre a granel para evitar embalagens plásticas descartáveis, e pratique o "desapego" através de trocas ou doações antes de comprar algo novo...', 'Educação Ambiental');

-- Quizzes Educacionais (1 Quiz por Guia)
INSERT IGNORE INTO guia_quizzes (guia_id, pergunta, opcao_a, opcao_b, opcao_c, opcao_correta) VALUES
(1, 'Qual a proporção ideal aproximada de materiais secos (marrom) para úmidos (verde) na compostagem?', '1 parte seca para 1 parte úmida', '2 partes secas para 1 parte úmida', '1 parte seca para 3 partes úmidas', 'B'),
(2, 'Qual dos seguintes materiais NÃO pode ser destinado à lixeira de recicláveis?', 'Garrafa PET limpa', 'Caixa de papelão ondulado', 'Papel carbono e guardanapo sujo', 'C'),
(3, 'Qual o princípio fundamental da Economia Circular?', 'Produzir mais rápido para baratear custos', 'Eliminar resíduos e poluição desde o design do produto', 'Incinerar todo o lixo produzido', 'B'),
(4, 'Quantas horas de sol direto a maioria dos temperos precisa por dia em um apartamento?', 'Pelo menos 4 horas', 'Nenhuma, crescem bem no escuro completo', 'Exatamente 12 horas obrigatórias', 'A'),
(5, 'O que significa priorizar o comércio local no consumo consciente?', 'Comprar de multinacionais que entregam de moto na sua rua', 'Comprar de pequenos produtores e artesãos do seu próprio bairro', 'Evitar fazer compras no seu bairro para economizar', 'B');

-- Pontos de Impacto (Hortas com Apadrinhamento Corporativo Inicial)
INSERT IGNORE INTO pontos_impacto (id, titulo, descricao, latitude, longitude, tipo, status, criador_id, empresa_padrinho_id) VALUES 
(1, 'Ecoponto Olavo Alvim', 'Recebe entulho, móveis velhos e recicláveis. Rua Olavo Alvim, s/n - Mooca.', -23.55160000, -46.59850000, 'coleta', 'ativo', 1, NULL),
(2, 'Ecoponto Barra Funda', 'Ponto de descarte de resíduos volumosos e recicláveis. Rua Sólon, 843.', -23.52620000, -46.64330000, 'coleta', 'ativo', 1, NULL),
(3, 'Horta das Corujas', 'Horta comunitária pioneira na Vila Madalena. Praça Dolores Ibarruri.', -23.54840000, -46.69750000, 'horta', 'produtivo', 1, 2),
(4, 'Horta do CCSP', 'Horta urbana no telhado do Centro Cultural São Paulo. Rua Vergueiro, 1000.', -23.57140000, -46.64020000, 'horta', 'produtivo', 1, NULL),
(5, 'Instituto Chão', 'Espaço de economia solidária e produtos orgânicos. Rua Harmonia, 123.', -23.55340000, -46.68740000, 'loja', 'ativo', 1, NULL),
(6, 'Bemglô', 'Loja com foco em produtos sustentáveis e slow fashion. Rua Oscar Freire.', -23.56390000, -46.66690000, 'loja', 'ativo', 1, NULL),
(7, 'Feira de Troca Vila Madalena', 'Ponto tradicional de trocas de livros e objetos. Praça Benedito Calixto.', -23.55580000, -46.68000000, 'troca', 'ativo', 1, NULL);

-- Eventos para Maio e Junho 2026
INSERT IGNORE INTO eventos (titulo, descricao, data_evento, localizacao, criador_id) VALUES 
('Mutirão de Limpeza Movimento SP', 'Ação coletiva de limpeza e educação ambiental na região central.', '2026-05-10 09:00:00', 'Praça da Sé, SP', 1),
('Workshop de Compostagem Urbana', 'Aprenda a transformar seus resíduos em adubo de forma prática.', '2026-05-24 14:00:00', 'Centro Cultural São Paulo', 1),
('Oficina Prática de Hortas Verticais', 'Aprenda a montar sua horta em pequenos espaços usando materiais recicláveis.', '2026-06-07 10:00:00', 'Horta das Corujas, Vila Madalena', 1),
('Feira de Troca de Sementes e Mudas', 'Traga suas sementes e mudas e troque com outros entusiastas da agricultura urbana.', '2026-06-20 13:00:00', 'Instituto Chão, Pinheiros', 1);

-- Oportunidades de Voluntariado e Vagas Verdes
INSERT IGNORE INTO oportunidades (titulo, descricao, tipo, link_externo, criador_id) VALUES 
('Voluntário Fundação Florestal', 'Apoio em Unidades de Conservação e trilhas ecológicas.', 'vaga', 'https://www.fflorestal.sp.gov.br', 1),
('Educador Ambiental - Instituto Limpa Brasil', 'Auxílio em campanhas de conscientização sobre resíduos.', 'vaga', 'https://limpabrasil.org', 1),
('Curso de Agricultura Urbana', 'Capacitação gratuita para moradores da periferia de SP.', 'curso', 'https://sampamaisrural.prefeitura.sp.gov.br', 1),
('Técnico em Gestão de Resíduos', 'Vaga CLT para atuação em triagem e reciclagem de plásticos de engenharia na zona leste.', 'vaga', 'https://www.ecologica.com/vagas', 2),
('Curso Avançado de Compostagem Termofílica', 'Capacitação teórica e prática com certificação para gerenciamento de resíduos orgânicos em larga escala.', 'curso', 'https://www.ecologica.com/cursos', 2);

-- Marketplace de Exemplo
INSERT IGNORE INTO marketplace (titulo, descricao, tipo_anuncio, preco, criador_id) VALUES 
('Kit Compostagem Doméstica', 'Composteira completa em ótimo estado, ideal para apartamentos.', 'upcycling', 150.00, 1),
('Sementes de Manjericão Orgânico', 'Troco sementes de manjericão por sementes de tomate.', 'troca_semente', 0.00, 1),
('Muda de Jabuticabeira', 'Doação de muda pequena para quem tem espaço no quintal.', 'doacao', 0.00, 1),
('Sabão Ecológico Artesanal (Lote com 4)', 'Sabão feito a partir de óleo de cozinha reciclado e essência natural de eucalipto.', 'upcycling', 25.00, 1),
('Vasos Auto-irrigáveis de Garrafa Pet', 'Vasos práticos e sustentáveis produzidos a partir de garrafas pet recicladas, ideais para temperos.', 'upcycling', 12.00, 1),
('Mudas de Pimenta Biquinho', 'Tenho várias mudas prontas para transplante. Aceito troca por adubo orgânico.', 'troca_semente', 0.00, 1);

-- Doações de Alimentos Realizadas
INSERT IGNORE INTO doacoes_alimentos (id, horta_id, quantidade_kg, descricao) VALUES
(1, 3, 12.50, 'Colheita abundante de alfaces crespa e roxa destinadas ao Sopão Comunitário.'),
(2, 4, 8.20, 'Doação de temperos variados (manjericão, salsinha e cebolinha) e tomates cereja.'),
(3, 3, 15.00, 'Mandioca e cenouras colhidas no mutirão de outono e doadas à cozinha solidária local.');

-- Recompensas Iniciais da EcoLoja
INSERT IGNORE INTO recompensas (id, titulo, descricao, custo_pontos, empresa_id, quantidade_disponivel) VALUES
(1, '10% de Desconto em Orgânicos', 'Válido para compras de hortaliças e temperos orgânicos na feira física parceira.', 100, 2, 15),
(2, 'Pacote de Adubo de Minhoca (2kg)', 'Adubo orgânico de altíssima qualidade produzido a partir de compostagem termofílica.', 180, 2, 8),
(3, 'Kit Vasos Biodegradáveis (5 un.)', 'Vasos ecológicos feitos de fibra de coco para plantio direto na terra, evitando recipientes plásticos.', 120, 2, 10);

-- Resgate Inicial de Teste
INSERT IGNORE INTO resgates (usuario_id, recompensa_id, codigo_cupom, status) VALUES
(1, 1, 'ECO-839210-VOLT', 'ativo');
