/* Script de criação das tabelas para o banco existente */
CREATE DATABASE IF NOT EXISTS ecoconecta;
USE ecoconecta;

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

/* ========================================== */
/* NOVAS TABELAS: ECOLOJA (RECOMPENSAS & RESGATES) */
/* ========================================== */
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

/* ========================================== */
/* NOVAS TABELAS: QUIZZES EDUCACIONAIS */
/* ========================================== */
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

/* ========================================== */
/* INSERÇÃO DE DADOS INICIAIS (SEED DATA) */
/* ========================================== */

/* Usuários Padrão */
INSERT IGNORE INTO usuarios (id, nome, email, senha_hash, tipo_perfil, eco_pontos, bio_curriculo) VALUES
(1, 'Bárbara Silva', 'barbara@ecoconecta.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cidadao', 350, 'Estudante de engenharia ambiental apaixonada por hortas comunitárias e compostagem doméstica.'),
(2, 'EcoLógica Soluções', 'contato@ecologica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empresa', 0, 'Empresa focada em assessoria ambiental, gestão de resíduos sólidos e projetos de sustentabilidade urbana.'),
(3, 'Instituto Ipê de Agroecologia', 'contato@ipeagro.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empresa', 0, 'ONG voltada para o reflorestamento urbano, capacitação em permacultura e conservação de espécies nativas.'),
(4, 'Coopere Centro Reciclagem', 'financeiro@cooperecentro.org.br', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empresa', 0, 'Cooperativa autônoma de catadores de materiais recicláveis da região central de São Paulo.'),
(5, 'Verde Urbano Paisagismo', 'contato@verdeurbano.com.br', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empresa', 0, 'Estúdio de arquitetura ecológica e paisagismo regenerativo focado em telhados verdes e jardins produtivos.'),
(6, 'Carlos Eduardo', 'cadu@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cidadao', 450, 'Morador de Pinheiros, entusiasta de hortas urbanas e ciclista. Voluntário ativo em mutirões de limpeza.'),
(7, 'Mariana Souza', 'mariana.sustentavel@uol.com.br', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cidadao', 280, 'Designer focada em upcycling e moda consciente. Acredito na economia circular e no consumo local.'),
(8, 'Cidadão de Teste', 'cidadao@teste.com', '$2y$10$NqXm0MQPkLDppU5CNAIUnufw8YRzEN/IPYQOk59WzmVyXwcrcNLES', 'cidadao', 500, 'Perfil cidadão para testes do sistema.'),
(9, 'Empresa de Teste', 'empresa@teste.com', '$2y$10$NqXm0MQPkLDppU5CNAIUnufw8YRzEN/IPYQOk59WzmVyXwcrcNLES', 'empresa', 0, 'Perfil empresa para testes do sistema.');

/* Guias Educacionais */
INSERT IGNORE INTO guia_educacional (id, titulo, conteudo, categoria) VALUES 
(1, 'Como iniciar uma composteira em casa', 'A compostagem é um processo biológico de valorização da matéria orgânica. Para começar, você precisa de um recipiente furado, uma camada de folhas secas (carbono) e uma camada de restos de alimento (nitrogênio). Mantenha a umidade equilibrada e revire uma vez por semana...', 'Compostagem'),
(2, 'O que pode e o que não pode reciclar?', 'Nem todo material que parece plástico é reciclável. Copos descartáveis sujos de café, fitas adesivas, papel carbono e cerâmicas não devem ir para o lixo reciclável. Já garrafas PET, latinhas de alumínio e caixas de papelão limpas são altamente valorizadas...', 'Reciclagem'),
(3, 'Economia Circular: O que é?', 'A economia circular propõe um novo ciclo de vida para os produtos. Em vez do modelo linear (extrair, produzir, descartar), ela foca em projetar produtos que possam ser desmontados, upcyclados e reinseridos na cadeia de valor, eliminando o desperdício...', 'Sustentabilidade'),
(4, 'Horta em Apartamento', 'Cultivar temperos em apartamento é simples. Escolha vasos com furos para drenagem, use substrato rico em matéria orgânica e garanta pelo menos 4 horas de sol direto diário. Alecrim, hortelã, manjericão e cebolinha adaptam-se perfeitamente...', 'Agricultura Urbana'),
(5, 'Consumo Consciente: Guia Prático', 'Consumir de forma consciente significa avaliar o impacto de cada compra. Priorize produtores locais, compre a granel para evitar embalagens plásticas descartáveis, e pratique o "desapego" através de trocas ou doações antes de comprar algo novo...', 'Educação Ambiental');

/* Quizzes Educacionais (1 Quiz por Guia) */
INSERT IGNORE INTO guia_quizzes (guia_id, pergunta, opcao_a, opcao_b, opcao_c, opcao_correta) VALUES
(1, 'Qual a proporção ideal aproximada de materiais secos (marrom) para umidos (verde) na compostagem?', '1 parte seca para 1 parte úmida', '2 partes secas para 1 parte úmida', '1 parte seca para 3 partes úmidas', 'B'),
(2, 'Qual dos seguintes materiais NÃO pode ser destinado à lixeira de recicláveis?', 'Garrafa PET limpa', 'Caixa de papelão ondulado', 'Papel carbono e guardanapo sujo', 'C'),
(3, 'Qual o princípio fundamental da Economia Circular?', 'Produzir mais rápido para baratear custos', 'Eliminar resíduos e poluição desde o design do produto', 'Incinerar todo o lixo produzido', 'B'),
(4, 'Quantas horas de sol direto a maioria dos temperos precisa por dia em um apartamento?', 'Pelo menos 4 horas', 'Nenhuma, crescem bem no escuro completo', 'Exatamente 12 horas obrigatórias', 'A'),
(5, 'O que significa priorizar o comércio local no consumo consciente?', 'Comprar de multinacionais que entregam de moto na sua rua', 'Comprar de pequenos produtores e artesãos do seu próprio bairro', 'Evitar fazer compras no seu bairro para economizar', 'B');

/* Pontos de Impacto (Hortas, Coleta, Lojas, Trocas em SP) */
INSERT IGNORE INTO pontos_impacto (id, titulo, descricao, latitude, longitude, tipo, status, criador_id, empresa_padrinho_id) VALUES 
(1, 'Ecoponto Olavo Alvim', 'Recebe entulho, móveis velhos e recicláveis. Rua Olavo Alvim, s/n - Mooca.', -23.55160000, -46.59850000, 'coleta', 'ativo', 1, NULL),
(2, 'Ecoponto Barra Funda', 'Ponto de descarte de resíduos volumosos e recicláveis. Rua Sólon, 843.', -23.52620000, -46.64330000, 'coleta', 'ativo', 1, NULL),
(3, 'Horta das Corujas', 'Horta comunitária pioneira na Vila Madalena. Praça Dolores Ibarruri.', -23.54840000, -46.69750000, 'horta', 'produtivo', 1, 2),
(4, 'Horta do CCSP', 'Horta urbana no telhado do Centro Cultural São Paulo. Rua Vergueiro, 1000.', -23.57140000, -46.64020000, 'horta', 'produtivo', 1, NULL),
(5, 'Instituto Chão', 'Espaço de economia solidária e produtos orgânicos. Rua Harmonia, 123.', -23.55340000, -46.68740000, 'loja', 'ativo', 1, NULL),
(6, 'Bemglô', 'Loja com foco em produtos sustentáveis e slow fashion. Rua Oscar Freire.', -23.56390000, -46.66690000, 'loja', 'ativo', 1, NULL),
(7, 'Feira de Troca Vila Madalena', 'Ponto tradicional de trocas de livros e objetos. Praça Benedito Calixto.', -23.55580000, -46.68000000, 'troca', 'ativo', 1, NULL),
(8, 'Horta Comunitária da Saúde', 'Cultivo colaborativo de PANCs, temperos e plantas medicinais na Zona Sul. Rua Paracatu, 800 - Saúde.', -23.61820000, -46.63700000, 'horta', 'produtivo', 1, 3),
(9, 'Horta do Ciclista', 'Canteiro verde coletivo no coração da Avenida Paulista, gerido por cicloativistas. Avenida Paulista, 2400.', -23.55960000, -46.65820000, 'horta', 'em_restauracao', 6, NULL),
(10, 'Ecoponto Pinheiros', 'Ponto de entrega voluntária de recicláveis comuns, pilhas, baterias e pequenos eletroeletrônicos. Praça Victor Civita.', -23.56580000, -46.70210000, 'coleta', 'ativo', 1, NULL),
(11, 'Cooperativa Coopere Centro', 'Triagem profissional e destinação adequada de resíduos. Recebe papelão, vidros, pets e metais. Rua do Glicério, 400.', -23.54910000, -46.62640000, 'coleta', 'ativo', 1, NULL),
(12, 'Horta Comunitária de São Mateus', 'Horta agroecológica de grande escala que abastece cozinhas solidárias da Zona Leste. Avenida Mateo Bei, 3000.', -23.60120000, -46.47890000, 'horta', 'produtivo', 1, 4),
(13, 'Armazém do Campo', 'Loja especializada em hortifrutis orgânicos certificados, sucos artesanais e produtos sustentáveis. Alameda Eduardo Prado, 496.', -23.53590000, -46.65120000, 'loja', 'ativo', 1, NULL),
(14, 'Feira de Troca de Sementes do Butantã', 'Espaço aberto de intercâmbio de sementes crioulas, bulbos e mudas frutíferas. Praça Elis Regina.', -23.57180000, -46.70820000, 'troca', 'ativo', 6, NULL),
(15, 'Ecoponto Santo Amaro', 'Ponto de descarte regularizado de entulho de construção (até 1m³), madeiras e móveis velhos. Al. Santo Amaro, s/n.', -23.64920000, -46.69980000, 'coleta', 'ativo', 1, NULL),
(16, 'Horta da FSP-USP', 'Horta urbana mantida na Faculdade de Saúde Pública da USP, aberta a visitas pedagógicas e oficinas. Av. Dr. Arnaldo, 715.', -23.55590000, -46.67180000, 'horta', 'produtivo', 1, 5),
(17, 'Casa Sem Lixo', 'Espaço de curadoria ecológica com escovas de dente de bambu, cosméticos sólidos e utensílios reutilizáveis a granel. Rua Simão Álvares, 120.', -23.56040000, -46.68920000, 'loja', 'ativo', 7, NULL),
(18, 'Horta Comunitária da Vila Pompeia', 'Espaço em revitalização com canteiros suspensos que necessita de voluntariado e adubo. Praça das Nascentes - Pompeia.', -23.52980000, -46.68590000, 'horta', 'degradado', 1, NULL),
(19, 'Ecoponto Santana', 'Recebimento de óleo doméstico usado (em garrafa pet), plásticos de engenharia, baterias e volumosos. Av. Cruzeiro do Sul, s/n.', -23.50420000, -46.62480000, 'coleta', 'ativo', 1, NULL);

/* Eventos e Mutirões */
INSERT IGNORE INTO eventos (id, titulo, descricao, data_evento, localizacao, criador_id) VALUES 
(1, 'Mutirão de Limpeza Movimento SP', 'Ação coletiva de limpeza e educação ambiental na região central.', '2026-05-10 09:00:00', 'Praça da Sé, SP', 1),
(2, 'Workshop de Compostagem Urbana', 'Aprenda a transformar seus resíduos em adubo de forma prática.', '2026-05-24 14:00:00', 'Centro Cultural São Paulo', 1),
(3, 'Oficina Prática de Hortas Verticais', 'Aprenda a montar sua horta em pequenos espaços usando materiais recicláveis.', '2026-06-07 10:00:00', 'Horta das Corujas, Vila Madalena', 1),
(4, 'Feira de Troca de Sementes e Mudas', 'Traga suas sementes e mudas e troque com outros entusiastas da agricultura urbana.', '2026-06-20 13:00:00', 'Instituto Chão, Pinheiros', 1),
(5, 'Mutirão de Revitalização da Horta das Corujas', 'Mutirão especial para preparo de novos canteiros de inverno, plantio de mudas e distribuição de composto orgânico aos vizinhos.', '2026-06-14 09:00:00', 'Horta das Corujas - Vila Madalena, SP', 1),
(6, 'Blitz de Limpeza e Educação Ambiental no Rio Pinheiros', 'Ação de coleta de resíduos sólidos e conscientização contra o descarte inadequado de plástico nas margens da ciclovia.', '2026-06-28 08:30:00', 'Ciclovia do Rio Pinheiros (Acesso Estação Vila Olímpia)', 6),
(7, 'Oficina de Bombas de Sementes (Seed Bombs)', 'Atividade pedagógica e divertida para crianças e adultos. Criaremos bombinhas de argila, adubo e sementes nativas para espalhar áreas degradadas.', '2026-07-05 10:00:00', 'Centro Cultural São Paulo (Telhado Verde) - Vergueiro', 1),
(8, 'Grande Feira de Upcycling e Swap Party (Troca de Roupas)', 'Traga roupas limpas e em bom estado que você não usa mais e troque por outras. Teremos oficinas de pequenos reparos e upcycling de tecidos no local.', '2026-07-18 11:00:00', 'Instituto Chão - Pinheiros, SP', 7),
(9, 'Mutirão de Plantio de Árvores Nativas e Reflorestamento', 'Plantio de 150 mudas de árvores nativas da Mata Atlântica em área de preservação na Zona Norte de SP. Recomenda-se protetor solar e calçado fechado.', '2026-06-05 08:00:00', 'Parque Estadual da Cantareira (Entrada Horto Florestal)', 3);

/* Oportunidades de Voluntariado e Vagas Verdes */
INSERT IGNORE INTO oportunidades (id, titulo, descricao, tipo, link_externo, criador_id) VALUES 
(1, 'Voluntário Fundação Florestal', 'Apoio em Unidades de Conservação e trilhas ecológicas.', 'vaga', 'https://www.fflorestal.sp.gov.br', 1),
(2, 'Educador Ambiental - Instituto Limpa Brasil', 'Auxílio em campanhas de conscientização sobre resíduos.', 'vaga', 'https://limpabrasil.org', 1),
(3, 'Curso de Agricultura Urbana', 'Capacitação gratuita para moradores da periferia de SP.', 'curso', 'https://sampamaisrural.prefeitura.sp.gov.br', 1),
(4, 'Técnico em Gestão de Resíduos', 'Vaga CLT para atuação em triagem e reciclagem de plásticos de engenharia na zona leste.', 'vaga', 'https://www.ecologica.com/vagas', 2),
(5, 'Curso Avançado de Compostagem Termofílica', 'Capacitação teórica e prática com certificação para gerenciamento de resíduos orgânicos em larga escala.', 'curso', 'https://www.ecologica.com/cursos', 2),
(6, 'Estágio em Gestão Ambiental e Relatórios ESG', 'Auxiliar no monitoramento de indicadores de emissão de carbono, gestão de resíduos e preparação de auditorias para certificações sustentáveis.', 'vaga', 'https://www.ipeagro.org/carreiras', 3),
(7, 'Analista de Economia Circular Pleno', 'Vaga CLT para atuar no mapeamento e implantação de logística reversa de embalagens plásticas e plásticos de engenharia na indústria.', 'vaga', 'https://www.ecologica.com/vagas', 2),
(8, 'Curso Profissionalizante de Agroecologia Urbana', 'Capacitação prática gratuita com 6 meses de duração, abordando manejo de solos, adubação orgânica, planejamento de hortas e controle biológico de pragas.', 'curso', 'https://www.ipeagro.org/cursos', 3),
(9, 'Oficina de Sistemas Fotovoltaicos e Energia Solar', 'Curso de curta duração focado na capacitação de instaladores de painéis solares residenciais de baixo custo.', 'curso', 'https://www.verdeurbano.com.br/cursos', 5),
(10, 'Consultor de Projetos de Crédito de Carbono Sênior', 'Profissional com formação em Engenharia Florestal ou Agronômica para atuar na estruturação de inventários de carbono florestal.', 'vaga', 'https://www.verdeurbano.com.br/vagas', 5),
(11, 'Curso Básico de Horta Vertical e Mini-Jardins', 'Aprenda a otimizar pequenos espaços urbanos montando minijardins e hortas de parede sustentáveis e estéticas. Ideal para apartamentos.', 'curso', 'https://www.cooperecentro.org.br/cursos', 4),
(12, 'Coordenador de Logística Reversa de Embalagens', 'Planejamento e coordenação de fluxo logístico reverso de materiais recicláveis pós-consumo em parceria com grandes marcas.', 'vaga', 'https://www.cooperecentro.org.br/trabalheconosco', 4);

/* Marketplace de Exemplo */
INSERT IGNORE INTO marketplace (id, titulo, descricao, tipo_anuncio, preco, criador_id) VALUES 
(1, 'Kit Compostagem Doméstica', 'Composteira completa em ótimo estado, ideal para apartamentos.', 'upcycling', 150.00, 1),
(2, 'Sementes de Manjericão Orgânico', 'Troco sementes de manjericão por sementes de tomate.', 'troca_semente', 0.00, 1),
(3, 'Muda de Jabuticabeira', 'Doação de muda pequena para quem tem espaço no quintal.', 'doacao', 0.00, 1),
(4, 'Sabão Ecológico Artesanal (Lote com 4)', 'Sabão feito a partir de óleo de cozinha reciclado e essência natural de eucalipto.', 'upcycling', 25.00, 1),
(5, 'Vasos Auto-irrigáveis de Garrafa Pet', 'Vasos práticos e sustentáveis produzidos a partir de garrafas pet recicladas, ideais para temperos.', 'upcycling', 12.00, 1),
(6, 'Mudas de Pimenta Biquinho', 'Tenho várias mudas prontas para transplante. Aceito troca por adubo orgânico.', 'troca_semente', 0.00, 1),
(7, 'Composteira Híbrida Doméstica', 'Composteira compacta ideal para cozinhas. Acompanha serragem e manual explicativo.', 'upcycling', 110.00, 6),
(8, 'Mudas de Hortelã e Erva Cidreira', 'Mudas enraizadas e prontas para o plantio em vaso. Troco por sementes de coentro ou terra adubada.', 'troca_semente', 0.00, 7),
(9, 'Sobras de Tecido Jeans para Patchwork', 'Sacola com retalhos jeans limpos e cortados para projetos de artesanato e upcycling.', 'doacao', 0.00, 7);

/* Doações de Alimentos Realizadas */
INSERT IGNORE INTO doacoes_alimentos (id, horta_id, quantidade_kg, descricao) VALUES
(1, 3, 12.50, 'Colheita abundante de alfaces crespa e roxa destinadas ao Sopão Comunitário.'),
(2, 4, 8.20, 'Doação de temperos variados (manjericão, salsinha e cebolinha) e tomates cereja.'),
(3, 3, 15.00, 'Mandioca e cenouras colhidas no mutirão de outono e doadas à cozinha solidária local.'),
(4, 8, 18.30, 'Salsa, coentro, alface crespa e mudas de hortelã colhidas para o Sopão Solidário da Paróquia.'),
(5, 12, 35.00, 'Colheita comunitária gigante de cenoura, mandioca e berinjela doada integralmente para cozinhas comunitárias locais.');

/* Recompensas Iniciais da EcoLoja */
INSERT IGNORE INTO recompensas (id, titulo, descricao, custo_pontos, empresa_id, quantidade_disponivel) VALUES
(1, '10% de Desconto em Orgânicos', 'Válido para compras de hortaliças e temperos orgânicos na feira física parceira.', 100, 2, 15),
(2, 'Pacote de Adubo de Minhoca (2kg)', 'Adubo orgânico de altíssima qualidade produzido a partir de compostagem termofílica.', 180, 2, 8),
(3, 'Kit Vasos Biodegradáveis (5 un.)', 'Vasos ecológicos feitos de fibra de coco para plantio direto na terra, evitando recipientes plásticos.', 120, 2, 10),
(4, 'Oficina de Horta em Pequenos Espaços', 'Ingresso gratuito para a oficina presencial promovida pela Verde Urbano com direito a kit de plantio.', 200, 5, 5),
(5, 'Sacola Ecológica de Algodão Cru', 'Sacola resistente para feira, produzida de forma ética pela Coopere Centro.', 80, 4, 20),
(6, 'Desconto de R$ 20,00 no Armazém do Campo', 'Cupom de desconto válido para compras acima de R$ 50,00 em produtos orgânicos no Armazém físico.', 150, 2, 12);

/* Resgate Inicial de Teste */
INSERT IGNORE INTO resgates (usuario_id, recompensa_id, codigo_cupom, status) VALUES
(1, 1, 'ECO-839210-VOLT', 'ativo');
