# 🌿 EcoConecta — Sustentabilidade, Comunidade e Oportunidades em São Paulo

> **Conectando cidadãos e empresas para regenerar a cidade de São Paulo através da economia circular, agricultura urbana e educação ambiental.**

O **EcoConecta** é uma plataforma web completa e integrada que visa transformar a relação dos paulistanos com o meio ambiente urbano. O sistema atua como um hub central de sustentabilidade comunitária, promovendo a restauração de espaços públicos degradados, incentivando a reciclagem com foco em geração de renda, combatendo a fome por meio da doação de alimentos cultivados localmente e capacitando cidadãos através de educação ecológica gamificada.

---

## 🚀 Principais Módulos do Ecossistema

O sistema é dividido em módulos operacionais interdependentes, projetados para entregar valor ecológico e social real:

### 1. 🗺️ Mapa de Impacto Urbano (SP)
*   **Mapeamento de Pontos de Impacto:** Localização geoespacial (latitude e longitude) de ecopontos oficiais, hortas comunitárias ativas, cooperativas de triagem, pontos de troca e lojas sustentáveis.
*   **Relação Colaborativa:** Usuários podem registrar novos pontos ecológicos e enviar **Relatórios de Status (Reports)** sobre a situação de locais existentes (ex: avisar se uma horta precisa de voluntários ou se um ecoponto está lotado).
*   **Gestão de Apadrinhamento:** Empresas parceiras podem "apadrinhar" hortas ou pontos de coleta degradados, financiando sua revitalização e insumos.

### 2. 🎁 EcoLoja & Gamificação por EcoPontos
*   **Acúmulo de EcoPontos:** Cidadãos acumulam pontos realizando ações ecológicas, como concluir quizzes educativos e participar de mutirões cadastrados na plataforma.
*   **Resgate de Recompensas (Vouchers):** Usuários trocam seus pontos por cupons e benefícios reais fornecidos por empresas parceiras (ex: sacolas de algodão cru, kits de vasos biodegradáveis, sacos de adubo orgânico de minhoca ou 10% de desconto em feiras parceiras).
*   **Validação Segura:** O sistema gera um código de cupom único (`ECO-XXXXXX-VOLT`) com status ativo/utilizado para controle empresarial.

### 3. 📚 Educação Verde & Quizzes Interativos
*   **Guias Educacionais Práticos:** Artigos estruturados sobre Compostagem Doméstica, Reciclagem Correta, Economia Circular, Agricultura em Apartamento e Consumo Consciente.
*   **Quizzes com Feedback Instantâneo:** Cada guia possui um quiz associado para fixação do conhecimento. Responder corretamente bonifica o usuário com EcoPontos, estimulando o aprendizado contínuo.

### 4. 🍅 Hortas Comunitárias & Combate à Fome
*   **Registro de Colheita e Doações:** As hortas cadastradas possuem um painel para registrar colheitas e doações em quilogramas (Kg).
*   **Destinação Social:** Toda a produção agrícola (alfaces, mandioca, cenouras, temperos) é destinada a cozinhas solidárias e paróquias que fornecem refeições para pessoas em situação de vulnerabilidade e fome.

### 5. 💼 Green CV (Currículo Ecológico) & Vagas Verdes
*   **Currículo Verde Digital:** Cidadãos possuem um perfil personalizado que compila automaticamente suas estatísticas de impacto: EcoPontos acumulados, mutirões dos quais participou, cursos concluídos e sua biografia ecológica. Esse perfil pode ser exportado como currículo.
*   **Portal de Oportunidades:** Empresas cadastradas anunciam vagas de "emprego verde", estágios voltados para sustentabilidade e ESG, além de capacitações e cursos gratuitos de agroecologia e energia solar.

### 6. ♻️ Marketplace de Economia Circular (P2P)
*   **Anúncios Comunitários:** Espaço seguro para os usuários comercializarem produtos baseados em *upcycling* (ex: sabão ecológico feito de óleo reciclado, vasos auto-irrigáveis de garrafa pet), realizarem trocas de mudas/sementes e anunciarem doações de materiais.

### 7. 🔒 Segurança & Conformidade LGPD
*   **Segurança de Credenciais:** As senhas dos usuários são protegidas com criptografia hash `bcrypt`.
*   **Privacidade Garantida:** O projeto conta com termos de uso e política de privacidade alinhados à LGPD (Lei Geral de Proteção de Dados), fornecendo aos cidadãos um painel para **exclusão definitiva de sua conta e histórico de dados**.

---

## 🏗️ Arquitetura de Software e Tecnologias

O **EcoConecta** adota uma arquitetura clássica baseada em MVC (Model-View-Controller) simplificada e robusta para ambiente web:

```
┌────────────────────────────────────────────────────────┐
│                      FRONTEND                          │
│  HTML5 • CSS3 Custom (Glassmorphism, Flexbox, Grids)   │
│  JavaScript Vanila (Hamburger Menu, UI reativa)        │
└───────────────────────────┬────────────────────────────┘
                            │ Requisições HTTP
                            ▼
┌────────────────────────────────────────────────────────┐
│                      BACKEND                           │
│  PHP 8.x (Controladores de Sessão, CRUD e Auth)        │
│  Controllers: processa_login.php, processa_resgate.php │
└───────────────────────────┬────────────────────────────┘
                            │ Conectividade PDO / MySQLi
                            ▼
┌────────────────────────────────────────────────────────┐
│                   BANCO DE DADOS                       │
│  MySQL (Modelo Relacional, FK Cascades, Triggers)     │
│  Esquema estruturado em 12 tabelas correlacionadas     │
└────────────────────────────────────────────────────────┘
```

### Stack Tecnológica Detalhada:
*   **Interface Gráfica (UI):** Desenvolvida em HTML5 sem frameworks externos desnecessários. Estilização baseada em **CSS Vanilla** com variáveis customizadas (tema verde e terra ecológico), uso avançado de *CSS Grid* e *Flexbox*, cards com efeito *glassmorphism*, e navegação responsiva adaptável a celulares.
*   **Linguagem de Programação:** PHP para controle de sessões de usuário, autenticação segura, e validação lógica.
*   **Banco de Dados Relacional:** MySQL com suporte completo a constraints de integridade referencial (`ON DELETE CASCADE` e `ON DELETE SET NULL`) para consistência nas ações dos usuários.
*   **Mapeamento de Comportamento:** Integração com **Microsoft Clarity** para rastreamento de comportamento do usuário, análise de usabilidade e otimização de interfaces.

---

## 🗄️ Modelo de Dados (MySQL)

O banco de dados relacional é composto por 12 tabelas altamente normalizadas. O script de criação (`sql/database.sql`) inclui tabelas de auditoria, relacionamento de doações, ecopontos, quizzes e a estrutura de cupons da EcoLoja.

### Principais Entidades e Estrutura:
1.  **`usuarios`**: Cadastro de cidadãos e empresas com hashes de senha seguros, saldo de EcoPontos e biografia do Green CV.
2.  **`pontos_impacto`**: Geolocalização de hortas, ecopontos, lojas e pontos de troca na grande São Paulo.
3.  **`mapa_reports`**: Denúncias ou avisos de status enviados pelos usuários sobre os pontos de impacto.
4.  **`oportunidades`**: Vagas de voluntariado, empregos verdes e cursos sustentáveis.
5.  **`marketplace`**: Produtos artesanais, mudas para troca e insumos ecológicos para doação.
6.  **`eventos`**: Mutirões de limpeza e reflorestamento com data, hora e local.
7.  **`guia_educacional`**: Conteúdo acadêmico separado por categorias de educação ambiental.
8.  **`guia_quizzes`**: Perguntas e respostas de múltipla escolha associadas aos guias educacionais.
9.  **`usuario_quizzes`**: Registro de conclusão de questionários para evitar premiação duplicada de pontos.
10. **`doacoes_alimentos`**: Registro do peso (Kg) de alimentos colhidos nas hortas e entregues a cozinhas solidárias.
11. **`recompensas`**: Brindes e descontos cadastrados por empresas parceiras na EcoLoja.
12. **`resgates`**: Cupons gerados para resgate de prêmios por cidadãos qualificados.

---

## ⚙️ Instalação e Execução Local

Para rodar a plataforma localmente na sua máquina, siga os seguintes passos:

### Pré-requisitos:
*   Ambiente de desenvolvimento PHP + MySQL (ex: **XAMPP**, **WampServer** ou **Laragon**).
*   Git instalado.

### Passo a Passo:

1.  **Clone o repositório ou copie a pasta do projeto** para a pasta pública do seu servidor local (geralmente `htdocs` no XAMPP ou `www` no Laragon):
    ```bash
    git clone https://github.com/BarbaraVSilva/EcoConecta.git
    ```

2.  **Configuração do Banco de Dados:**
    *   Inicie os serviços do **Apache** e do **MySQL** no painel de controle do seu servidor local.
    *   Acesse o **phpMyAdmin** (`http://localhost/phpmyadmin`).
    *   Crie um novo banco de dados chamado **`ecoconecta`** (ou utilize o nome de sua preferência).
    *   Vá na aba "Importar", selecione o arquivo **`sql/database.sql`** localizado dentro do projeto e execute a importação. O script criará todas as tabelas necessárias e preencherá a base com **dados de exemplo altamente robustos** (hortas reais de São Paulo, usuários de teste, guias e quizzes completos).

3.  **Ajuste de Conectividade e Chaves Sensíveis:**
    *   **Importante:** Para segurança das credenciais, os arquivos de configuração reais estão excluídos do Git.
    *   Copie o arquivo **[config/conexao.example.php](file:///C:/Users/Barbara/Desktop/Projetos/01-PROJECTS/Projeto%20Script/config/conexao.example.php)** para **`config/conexao.php`** e configure os dados do seu MySQL local.
    *   Copie o arquivo **[config/google_config.example.php](file:///C:/Users/Barbara/Desktop/Projetos/01-PROJECTS/Projeto%20Script/config/google_config.example.php)** para **`config/google_config.php`** e insira o seu Google Client ID.

4.  **Acesse no Navegador:**
    *   Abra o navegador de sua preferência e digite:
        `http://localhost/EcoConecta/`
    *   Use as credenciais de teste fornecidas na seção abaixo para navegar como cidadão ou empresa!

---

## 🔑 Credenciais de Teste (Dados Iniciais)

Para testar todas as funcionalidades das duas modalidades de perfil disponíveis na plataforma, você pode usar os seguintes acessos rápidos (senha padrão para todos é `teste123`):

*   **Perfil Cidadão (Explorador, Resgates, Quizzes e Green CV):**
    *   **E-mail:** `cidadao@teste.com`
    *   **Senha:** `teste123` (Conta pré-abastecida com 500 EcoPontos)
*   **Perfil Empresa (Criação de Oportunidades, Doações e Recompensas):**
    *   **E-mail:** `empresa@teste.com`
    *   **Senha:** `teste123`

---

## 🔒 Login Social & Google Auth
O projeto conta com integração completa do **Google Sign-In**. Cidadãos e empresas podem se cadastrar e efetuar login usando suas contas do Google diretamente na tela de autenticação, vinculando os seus perfis e aceitando as políticas da LGPD em um único clique sem necessidade de senha manual.

---

## 👥 Equipe de Desenvolvimento

O EcoConecta foi idealizado e construído por um time dedicado à engenharia e inovação sustentável:

*   **Barbara Silva** — Idealizadora & Desenvolvedora
*   **Thamires Martins** — Coordenadora de Projetos
*   **Jefferson Borges** — Desenvolvedor Backend
*   **Richard Greghi** — Especialista em UX/UI
*   **Ricardo Pighin** — Desenvolvedor Frontend
*   **Matheus Araujo** — Analista de Banco de Dados

---

## ♻️ Sincronização do Projeto (Desenvolvimento)
Para desenvolvedores da equipe, o repositório pode ser atualizado rapidamente executando o script utilitário automatizado na raiz do projeto:
```bash
# No terminal Windows, basta executar:
.\atualizaGit.bat
```
Isso fará o estanciamento automático (`git add .`), criará um commit de backup datado e enviará as alterações para a branch `main` no GitHub de forma segura.
