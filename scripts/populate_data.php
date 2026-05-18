<?php
/**
 * Script de população automática de dados de teste (Seed Data)
 * Projeto: EcoConecta
 */

// Define caminhos e requer a conexão
require_once dirname(__DIR__) . '/config/conexao.php';

echo "=== INICIANDO POPULAÇÃO DE DADOS NO ECOCONECTA ===\n";

// Ativa relatórios de erro
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // 1. INSERINDO USUÁRIOS COMPLEMENTARES
    echo "1. Populando Usuários (Cidadãos e Empresas)...\n";
    $usuarios = [
        [
            'id' => 3,
            'nome' => 'Instituto Ipê de Agroecologia',
            'email' => 'contato@ipeagro.org',
            'senha_hash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // senha: password
            'tipo_perfil' => 'empresa',
            'eco_pontos' => 0,
            'bio_curriculo' => 'ONG voltada para o reflorestamento urbano, capacitação em permacultura e conservação de espécies nativas.'
        ],
        [
            'id' => 4,
            'nome' => 'Coopere Centro Reciclagem',
            'email' => 'financeiro@cooperecentro.org.br',
            'senha_hash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'tipo_perfil' => 'empresa',
            'eco_pontos' => 0,
            'bio_curriculo' => 'Cooperativa autônoma de catadores de materiais recicláveis da região central de São Paulo.'
        ],
        [
            'id' => 5,
            'nome' => 'Verde Urbano Paisagismo',
            'email' => 'contato@verdeurbano.com.br',
            'senha_hash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'tipo_perfil' => 'empresa',
            'eco_pontos' => 0,
            'bio_curriculo' => 'Estúdio de arquitetura ecológica e paisagismo regenerativo focado em telhados verdes e jardins produtivos.'
        ],
        [
            'id' => 6,
            'nome' => 'Carlos Eduardo',
            'email' => 'cadu@gmail.com',
            'senha_hash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'tipo_perfil' => 'cidadao',
            'eco_pontos' => 450,
            'bio_curriculo' => 'Morador de Pinheiros, entusiasta de hortas urbanas e ciclista. Voluntário ativo em mutirões de limpeza.'
        ],
        [
            'id' => 7,
            'nome' => 'Mariana Souza',
            'email' => 'mariana.sustentavel@uol.com.br',
            'senha_hash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'tipo_perfil' => 'cidadao',
            'eco_pontos' => 280,
            'bio_curriculo' => 'Designer focada em upcycling e moda consciente. Acredito na economia circular e no consumo local.'
        ]
    ];

    foreach ($usuarios as $u) {
        $stmt = $conn->prepare("INSERT IGNORE INTO usuarios (id, nome, email, senha_hash, tipo_perfil, eco_pontos, bio_curriculo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssis", $u['id'], $u['nome'], $u['email'], $u['senha_hash'], $u['tipo_perfil'], $u['eco_pontos'], $u['bio_curriculo']);
        $stmt->execute();
    }

    // 2. INSERINDO PONTOS DE IMPACTO COMPLEMENTARES
    echo "2. Populando Pontos de Impacto no Mapa...\n";
    $pontos = [
        [
            'id' => 8,
            'titulo' => 'Horta Comunitária da Saúde',
            'descricao' => 'Cultivo colaborativo de PANCs, temperos e plantas medicinais na Zona Sul. Rua Paracatu, 800 - Saúde.',
            'latitude' => -23.61820000,
            'longitude' => -46.63700000,
            'tipo' => 'horta',
            'status' => 'produtivo',
            'criador_id' => 1,
            'empresa_padrinho_id' => 3
        ],
        [
            'id' => 9,
            'titulo' => 'Horta do Ciclista',
            'descricao' => 'Canteiro verde coletivo no coração da Avenida Paulista, gerido por cicloativistas. Avenida Paulista, 2400.',
            'latitude' => -23.55960000,
            'longitude' => -46.65820000,
            'tipo' => 'horta',
            'status' => 'em_restauracao',
            'criador_id' => 6,
            'empresa_padrinho_id' => null
        ],
        [
            'id' => 10,
            'titulo' => 'Ecoponto Pinheiros',
            'descricao' => 'Ponto de entrega voluntária de recicláveis comuns, pilhas, baterias e pequenos eletroeletrônicos. Praça Victor Civita.',
            'latitude' => -23.56580000,
            'longitude' => -46.70210000,
            'tipo' => 'coleta',
            'status' => 'ativo',
            'criador_id' => 1,
            'empresa_padrinho_id' => null
        ],
        [
            'id' => 11,
            'titulo' => 'Cooperativa Coopere Centro',
            'descricao' => 'Triagem profissional e destinação adequada de resíduos. Recebe papelão, vidros, pets e metais. Rua do Glicério, 400.',
            'latitude' => -23.54910000,
            'longitude' => -46.62640000,
            'tipo' => 'coleta',
            'status' => 'ativo',
            'criador_id' => 1,
            'empresa_padrinho_id' => null
        ],
        [
            'id' => 12,
            'titulo' => 'Horta Comunitária de São Mateus',
            'descricao' => 'Horta agroecológica de grande escala que abastece cozinhas solidárias da Zona Leste. Avenida Mateo Bei, 3000.',
            'latitude' => -23.60120000,
            'longitude' => -46.47890000,
            'tipo' => 'horta',
            'status' => 'produtivo',
            'criador_id' => 1,
            'empresa_padrinho_id' => 4
        ],
        [
            'id' => 13,
            'titulo' => 'Armazém do Campo',
            'descricao' => 'Loja especializada em hortifrutis orgânicos certificados, sucos artesanais e produtos sustentáveis. Alameda Eduardo Prado, 496.',
            'latitude' => -23.53590000,
            'longitude' => -46.65120000,
            'tipo' => 'loja',
            'status' => 'ativo',
            'criador_id' => 1,
            'empresa_padrinho_id' => null
        ],
        [
            'id' => 14,
            'titulo' => 'Feira de Troca de Sementes do Butantã',
            'descricao' => 'Espaço aberto de intercâmbio de sementes crioulas, bulbos e mudas frutíferas. Praça Elis Regina.',
            'latitude' => -23.57180000,
            'longitude' => -46.70820000,
            'tipo' => 'troca',
            'status' => 'ativo',
            'criador_id' => 6,
            'empresa_padrinho_id' => null
        ],
        [
            'id' => 15,
            'titulo' => 'Ecoponto Santo Amaro',
            'descricao' => 'Ponto de descarte regularizado de entulho de construção (até 1m³), madeiras e móveis velhos. Al. Santo Amaro, s/n.',
            'latitude' => -23.64920000,
            'longitude' => -46.69980000,
            'tipo' => 'coleta',
            'status' => 'ativo',
            'criador_id' => 1,
            'empresa_padrinho_id' => null
        ],
        [
            'id' => 16,
            'titulo' => 'Horta da FSP-USP',
            'descricao' => 'Horta urbana mantida na Faculdade de Saúde Pública da USP, aberta a visitas pedagógicas e oficinas. Av. Dr. Arnaldo, 715.',
            'latitude' => -23.55590000,
            'longitude' => -46.67180000,
            'tipo' => 'horta',
            'status' => 'produtivo',
            'criador_id' => 1,
            'empresa_padrinho_id' => 5
        ],
        [
            'id' => 17,
            'titulo' => 'Casa Sem Lixo',
            'descricao' => 'Espaço de curadoria ecológica com escovas de dente de bambu, cosméticos sólidos e utensílios reutilizáveis a granel. Rua Simão Álvares, 120.',
            'latitude' => -23.56040000,
            'longitude' => -46.68920000,
            'tipo' => 'loja',
            'status' => 'ativo',
            'criador_id' => 7,
            'empresa_padrinho_id' => null
        ],
        [
            'id' => 18,
            'titulo' => 'Horta Comunitária da Vila Pompeia',
            'descricao' => 'Espaço em revitalização com canteiros suspensos que necessita de voluntariado e adubo. Praça das Nascentes - Pompeia.',
            'latitude' => -23.52980000,
            'longitude' => -46.68590000,
            'tipo' => 'horta',
            'status' => 'degradado',
            'criador_id' => 1,
            'empresa_padrinho_id' => null
        ],
        [
            'id' => 19,
            'titulo' => 'Ecoponto Santana',
            'descricao' => 'Recebimento de óleo doméstico usado (em garrafa pet), plásticos de engenharia, baterias e volumosos. Av. Cruzeiro do Sul, s/n.',
            'latitude' => -23.50420000,
            'longitude' => -46.62480000,
            'tipo' => 'coleta',
            'status' => 'ativo',
            'criador_id' => 1,
            'empresa_padrinho_id' => null
        ]
    ];

    foreach ($pontos as $p) {
        $stmt = $conn->prepare("INSERT IGNORE INTO pontos_impacto (id, titulo, descricao, latitude, longitude, tipo, status, criador_id, empresa_padrinho_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issddssii", $p['id'], $p['titulo'], $p['descricao'], $p['latitude'], $p['longitude'], $p['tipo'], $p['status'], $p['criador_id'], $p['empresa_padrinho_id']);
        $stmt->execute();
    }

    // 3. INSERINDO OPORTUNIDADES
    echo "3. Populando Oportunidades (Vagas e Cursos)...\n";
    $oportunidades = [
        [
            'id' => 6,
            'titulo' => 'Estágio em Gestão Ambiental e Relatórios ESG',
            'descricao' => 'Auxiliar no monitoramento de indicadores de emissão de carbono, gestão de resíduos e preparação de auditorias para certificações sustentáveis.',
            'tipo' => 'vaga',
            'link_externo' => 'https://www.ipeagro.org/carreiras',
            'criador_id' => 3
        ],
        [
            'id' => 7,
            'titulo' => 'Analista de Economia Circular Pleno',
            'descricao' => 'Vaga CLT para atuar no mapeamento e implantação de logística reversa de embalagens plásticas e plásticos de engenharia na indústria.',
            'tipo' => 'vaga',
            'link_externo' => 'https://www.ecologica.com/vagas',
            'criador_id' => 2
        ],
        [
            'id' => 8,
            'titulo' => 'Curso Profissionalizante de Agroecologia Urbana',
            'descricao' => 'Capacitação prática gratuita com 6 meses de duração, abordando manejo de solos, adubação orgânica, planejamento de hortas e controle biológico de pragas.',
            'tipo' => 'curso',
            'link_externo' => 'https://www.ipeagro.org/cursos',
            'criador_id' => 3
        ],
        [
            'id' => 9,
            'titulo' => 'Oficina de Sistemas Fotovoltaicos e Energia Solar',
            'descricao' => 'Curso de curta duração focado na capacitação de instaladores de painéis solares residenciais de baixo custo.',
            'tipo' => 'curso',
            'link_externo' => 'https://www.verdeurbano.com.br/cursos',
            'criador_id' => 5
        ],
        [
            'id' => 10,
            'titulo' => 'Consultor de Projetos de Crédito de Carbono Sênior',
            'descricao' => 'Profissional com formação em Engenharia Florestal ou Agronômica para atuar na estruturação de inventários de carbono florestal.',
            'tipo' => 'vaga',
            'link_externo' => 'https://www.verdeurbano.com.br/vagas',
            'criador_id' => 5
        ],
        [
            'id' => 11,
            'titulo' => 'Curso Básico de Horta Vertical e Mini-Jardins',
            'descricao' => 'Aprenda a otimizar pequenos espaços urbanos montando minijardins e hortas de parede sustentáveis e estéticas. Ideal para apartamentos.',
            'tipo' => 'curso',
            'link_externo' => 'https://www.cooperecentro.org.br/cursos',
            'criador_id' => 4
        ],
        [
            'id' => 12,
            'titulo' => 'Coordenador de Logística Reversa de Embalagens',
            'descricao' => 'Planejamento e coordenação de fluxo logístico reverso de materiais recicláveis pós-consumo em parceria com grandes marcas.',
            'tipo' => 'vaga',
            'link_externo' => 'https://www.cooperecentro.org.br/trabalheconosco',
            'criador_id' => 4
        ]
    ];

    foreach ($oportunidades as $o) {
        $stmt = $conn->prepare("INSERT IGNORE INTO oportunidades (id, titulo, descricao, tipo, link_externo, criador_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssi", $o['id'], $o['titulo'], $o['descricao'], $o['tipo'], $o['link_externo'], $o['criador_id']);
        $stmt->execute();
    }

    // 4. INSERINDO EVENTOS E MUTIRÕES
    echo "4. Populando Eventos e Mutirões...\n";
    $eventos = [
        [
            'id' => 5,
            'titulo' => 'Mutirão de Revitalização da Horta das Corujas',
            'descricao' => 'Mutirão especial para preparo de novos canteiros de inverno, plantio de mudas e distribuição de composto orgânico aos vizinhos.',
            'data_evento' => '2026-06-14 09:00:00',
            'localizacao' => 'Horta das Corujas - Vila Madalena, SP',
            'criador_id' => 1
        ],
        [
            'id' => 6,
            'titulo' => 'Blitz de Limpeza e Educação Ambiental no Rio Pinheiros',
            'descricao' => 'Ação de coleta de resíduos sólidos e conscientização contra o descarte inadequado de plástico nas margens da ciclovia.',
            'data_evento' => '2026-06-28 08:30:00',
            'localizacao' => 'Ciclovia do Rio Pinheiros (Acesso Estação Vila Olímpia)',
            'criador_id' => 6
        ],
        [
            'id' => 7,
            'titulo' => 'Oficina de Bombas de Sementes (Seed Bombs)',
            'descricao' => 'Atividade pedagógica e divertida para crianças e adultos. Criaremos bombinhas de argila, adubo e sementes nativas para espalhar áreas degradadas.',
            'data_evento' => '2026-07-05 10:00:00',
            'localizacao' => 'Centro Cultural São Paulo (Telhado Verde) - Vergueiro',
            'criador_id' => 1
        ],
        [
            'id' => 8,
            'titulo' => 'Grande Feira de Upcycling e Swap Party (Troca de Roupas)',
            'descricao' => 'Traga roupas limpas e em bom estado que você não usa mais e troque por outras. Teremos oficinas de pequenos reparos e upcycling de tecidos no local.',
            'data_evento' => '2026-07-18 11:00:00',
            'localizacao' => 'Instituto Chão - Pinheiros, SP',
            'criador_id' => 7
        ],
        [
            'id' => 9,
            'titulo' => 'Mutirão de Plantio de Árvores Nativas e Reflorestamento',
            'descricao' => 'Plantio de 150 mudas de árvores nativas da Mata Atlântica em área de preservação na Zona Norte de SP. Recomenda-se protetor solar e calçado fechado.',
            'data_evento' => '2026-06-05 08:00:00',
            'localizacao' => 'Parque Estadual da Cantareira (Entrada Horto Florestal)',
            'criador_id' => 3
        ]
    ];

    foreach ($eventos as $e) {
        $stmt = $conn->prepare("INSERT IGNORE INTO eventos (id, titulo, descricao, data_evento, localizacao, criador_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssi", $e['id'], $e['titulo'], $e['descricao'], $e['data_evento'], $e['localizacao'], $e['criador_id']);
        $stmt->execute();
    }

    // 5. INSERINDO PRODUTOS DO MARKETPLACE
    echo "5. Populando Produtos do Marketplace...\n";
    $marketplace = [
        [
            'id' => 7,
            'titulo' => 'Composteira Híbrida Doméstica',
            'descricao' => 'Composteira compacta ideal para cozinhas. Acompanha serragem e manual explicativo.',
            'tipo_anuncio' => 'upcycling',
            'preco' => 110.00,
            'criador_id' => 6
        ],
        [
            'id' => 8,
            'titulo' => 'Mudas de Hortelã e Erva Cidreira',
            'descricao' => 'Mudas enraizadas e prontas para o plantio em vaso. Troco por sementes de coentro ou terra adubada.',
            'tipo_anuncio' => 'troca_semente',
            'preco' => 0.00,
            'criador_id' => 7
        ],
        [
            'id' => 9,
            'titulo' => 'Sobras de Tecido Jeans para Patchwork',
            'descricao' => 'Sacola com retalhos jeans limpos e cortados para projetos de artesanato e upcycling.',
            'tipo_anuncio' => 'doacao',
            'preco' => 0.00,
            'criador_id' => 7
        ]
    ];

    foreach ($marketplace as $m) {
        $stmt = $conn->prepare("INSERT IGNORE INTO marketplace (id, titulo, descricao, tipo_anuncio, preco, criador_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssdi", $m['id'], $m['titulo'], $m['descricao'], $m['tipo_anuncio'], $m['preco'], $m['criador_id']);
        $stmt->execute();
    }

    // 6. INSERINDO RECOMPENSAS DA ECOLOJA
    echo "6. Populando Recompensas da EcoLoja...\n";
    $recompensas = [
        [
            'id' => 4,
            'titulo' => 'Oficina de Horta em Pequenos Espaços',
            'descricao' => 'Ingresso gratuito para a oficina presencial promovida pela Verde Urbano com direito a kit de plantio.',
            'custo_pontos' => 200,
            'empresa_id' => 5,
            'quantidade_disponivel' => 5
        ],
        [
            'id' => 5,
            'titulo' => 'Sacola Ecológica de Algodão Cru',
            'descricao' => 'Sacola resistente para feira, produzida de forma ética pela Coopere Centro.',
            'custo_pontos' => 80,
            'empresa_id' => 4,
            'quantidade_disponivel' => 20
        ],
        [
            'id' => 6,
            'titulo' => 'Desconto de R$ 20,00 no Armazém do Campo',
            'descricao' => 'Cupom de desconto válido para compras acima de R$ 50,00 em produtos orgânicos no Armazém físico.',
            'custo_pontos' => 150,
            'empresa_id' => 2,
            'quantidade_disponivel' => 12
        ]
    ];

    foreach ($recompensas as $r) {
        $stmt = $conn->prepare("INSERT IGNORE INTO recompensas (id, titulo, descricao, custo_pontos, empresa_id, quantidade_disponivel) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issiii", $r['id'], $r['titulo'], $r['descricao'], $r['custo_pontos'], $r['empresa_id'], $r['quantidade_disponivel']);
        $stmt->execute();
    }

    // 7. INSERINDO DOAÇÕES DE ALIMENTOS
    echo "7. Populando Doações de Alimentos...\n";
    $doacoes = [
        [
            'id' => 4,
            'horta_id' => 8,
            'quantidade_kg' => 18.30,
            'descricao' => 'Salsa, coentro, alface crespa e mudas de hortelã colhidas para o Sopão Solidário da Paróquia.'
        ],
        [
            'id' => 5,
            'horta_id' => 12,
            'quantidade_kg' => 35.00,
            'descricao' => 'Colheita comunitária gigante de cenoura, mandioca e berinjela doada integralmente para cozinhas comunitárias locais.'
        ]
    ];

    foreach ($doacoes as $d) {
        $stmt = $conn->prepare("INSERT IGNORE INTO doacoes_alimentos (id, horta_id, quantidade_kg, descricao) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iids", $d['id'], $d['horta_id'], $d['quantidade_kg'], $d['descricao']);
        $stmt->execute();
    }

    echo "=== BANCO DE DADOS POPULADO COM SUCESSO! ===\n";

} catch (Exception $e) {
    echo "\n[ERRO NA POPULAÇÃO DO BANCO DE DADOS]: " . $e->getMessage() . "\n";
}
?>
