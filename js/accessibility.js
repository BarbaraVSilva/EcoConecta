/**
 * EcoConecta — Módulo de Acessibilidade
 * WCAG 2.1 AA Compliant
 * Funcionalidades: Alto Contraste, Tamanho de Fonte, Fonte Dislexia,
 * Escala de Cinza, Guia de Leitura, Sem Animações
 */
(function () {
    'use strict';

    /* ================================================================
       ESTADO — Carregado do localStorage na inicialização
    ================================================================ */
    const STORAGE_KEY = 'ecoconecta_a11y';

    const defaultState = {
        altoContraste: false,
        fonteTamanho: 'normal', // normal | media | grande | enorme
        fonteDislexia: false,
        escalaCinza: false,
        guiaLeitura: false,
        semAnimacoes: false
    };

    function loadState() {
        try {
            const saved = localStorage.getItem(STORAGE_KEY);
            return saved ? Object.assign({}, defaultState, JSON.parse(saved)) : Object.assign({}, defaultState);
        } catch (e) {
            return Object.assign({}, defaultState);
        }
    }

    function saveState(state) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
        } catch (e) { /* silently fail */ }
    }

    let state = loadState();

    /* ================================================================
       APLICAR ESTADO NO <BODY>
    ================================================================ */
    function applyState() {
        const body = document.body;

        // Alto Contraste
        body.classList.toggle('alto-contraste', state.altoContraste);

        // Tamanho de fonte
        body.classList.remove('fonte-media', 'fonte-grande', 'fonte-enorme');
        if (state.fonteTamanho !== 'normal') {
            body.classList.add('fonte-' + state.fonteTamanho);
        }

        // Fonte para dislexia
        body.classList.toggle('fonte-dislexia', state.fonteDislexia);

        // Escala de cinza
        body.classList.toggle('escala-cinza', state.escalaCinza);

        // Guia de leitura
        body.classList.toggle('guia-ativa', state.guiaLeitura);

        // Sem animações
        body.classList.toggle('sem-animacoes', state.semAnimacoes);

        saveState(state);
        syncToggles();
    }

    /* ================================================================
       SINCRONIZAR TOGGLES DO PAINEL
    ================================================================ */
    function syncToggles() {
        setToggle('ac-alto-contraste', state.altoContraste);
        setToggle('ac-fonte-dislexia', state.fonteDislexia);
        setToggle('ac-escala-cinza', state.escalaCinza);
        setToggle('ac-guia-leitura', state.guiaLeitura);
        setToggle('ac-sem-animacoes', state.semAnimacoes);

        // Botões de fonte
        ['normal', 'media', 'grande', 'enorme'].forEach(size => {
            const btn = document.getElementById('font-' + size);
            if (btn) btn.classList.toggle('ativo', state.fonteTamanho === size);
        });
    }

    function setToggle(id, value) {
        const el = document.getElementById(id);
        if (el) el.checked = value;
    }

    /* ================================================================
       CRIAR PAINEL E BOTÃO NO DOM
    ================================================================ */
    function buildPanel() {
        // Skip link (primeiro elemento do body)
        const skipLink = document.createElement('a');
        skipLink.href = '#conteudo-principal';
        skipLink.className = 'skip-link';
        skipLink.textContent = 'Pular para o conteúdo principal';
        document.body.insertAdjacentElement('afterbegin', skipLink);

        // Guia de leitura
        const guide = document.createElement('div');
        guide.id = 'guia-leitura';
        guide.setAttribute('aria-hidden', 'true');
        document.body.appendChild(guide);

        // Botão flutuante
        const fab = document.createElement('button');
        fab.id = 'eco-acessibilidade-btn';
        fab.setAttribute('aria-label', 'Abrir painel de acessibilidade');
        fab.setAttribute('aria-expanded', 'false');
        fab.setAttribute('aria-controls', 'eco-painel-acessibilidade');
        fab.innerHTML = '♿';
        fab.title = 'Acessibilidade';
        document.body.appendChild(fab);

        // Painel
        const panel = document.createElement('div');
        panel.id = 'eco-painel-acessibilidade';
        panel.setAttribute('role', 'dialog');
        panel.setAttribute('aria-modal', 'false');
        panel.setAttribute('aria-label', 'Painel de acessibilidade');
        panel.innerHTML = `
          <div class="painel-header">
            <h2>
              <span aria-hidden="true">♿</span>
              Acessibilidade
            </h2>
            <button class="painel-fechar" id="painel-fechar-btn" aria-label="Fechar painel de acessibilidade">✕</button>
          </div>
          <div class="painel-corpo">

            <!-- Alto Contraste -->
            <div class="ac-control">
              <label class="ac-control-label" for="ac-alto-contraste">
                <span class="ac-icon" aria-hidden="true">🔆</span>
                Alto Contraste
              </label>
              <label class="ac-toggle" aria-label="Ativar alto contraste">
                <input type="checkbox" id="ac-alto-contraste" role="switch">
                <span class="ac-slider"></span>
              </label>
            </div>

            <!-- Tamanho de Fonte -->
            <div class="ac-control">
              <label class="ac-control-label" id="font-label">
                <span class="ac-icon" aria-hidden="true">🔡</span>
                Tamanho do texto
              </label>
              <div class="font-size-controls" role="group" aria-labelledby="font-label">
                <button class="font-btn" id="font-normal" aria-label="Texto normal" aria-pressed="true">A</button>
                <button class="font-btn" id="font-media"  aria-label="Texto médio"  aria-pressed="false">A+</button>
                <button class="font-btn" id="font-grande" aria-label="Texto grande" aria-pressed="false">A++</button>
                <button class="font-btn" id="font-enorme" aria-label="Texto muito grande" aria-pressed="false">A+++</button>
              </div>
            </div>

            <!-- Fonte para Dislexia -->
            <div class="ac-control">
              <label class="ac-control-label" for="ac-fonte-dislexia">
                <span class="ac-icon" aria-hidden="true">📖</span>
                Fonte p/ Dislexia
              </label>
              <label class="ac-toggle" aria-label="Ativar fonte para dislexia">
                <input type="checkbox" id="ac-fonte-dislexia" role="switch">
                <span class="ac-slider"></span>
              </label>
            </div>

            <!-- Escala de Cinza -->
            <div class="ac-control">
              <label class="ac-control-label" for="ac-escala-cinza">
                <span class="ac-icon" aria-hidden="true">⚫</span>
                Escala de Cinza
              </label>
              <label class="ac-toggle" aria-label="Ativar escala de cinza">
                <input type="checkbox" id="ac-escala-cinza" role="switch">
                <span class="ac-slider"></span>
              </label>
            </div>

            <!-- Guia de Leitura -->
            <div class="ac-control">
              <label class="ac-control-label" for="ac-guia-leitura">
                <span class="ac-icon" aria-hidden="true">📏</span>
                Guia de Leitura
              </label>
              <label class="ac-toggle" aria-label="Ativar guia de leitura">
                <input type="checkbox" id="ac-guia-leitura" role="switch">
                <span class="ac-slider"></span>
              </label>
            </div>

            <!-- Sem Animações -->
            <div class="ac-control">
              <label class="ac-control-label" for="ac-sem-animacoes">
                <span class="ac-icon" aria-hidden="true">🎞️</span>
                Pausar Animações
              </label>
              <label class="ac-toggle" aria-label="Pausar animações">
                <input type="checkbox" id="ac-sem-animacoes" role="switch">
                <span class="ac-slider"></span>
              </label>
            </div>

            <!-- Botão Resetar -->
            <button class="ac-reset-btn" id="ac-reset" aria-label="Redefinir todas as configurações de acessibilidade">
              <span aria-hidden="true">↩️</span> Redefinir Configurações
            </button>

            <p class="ac-status" aria-live="polite" id="ac-status-msg"></p>
          </div>
        `;
        document.body.appendChild(panel);

        // Anuncio de status (aria-live)
        const liveRegion = document.createElement('div');
        liveRegion.id = 'a11y-live';
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.setAttribute('aria-atomic', 'true');
        liveRegion.className = 'sr-only';
        document.body.appendChild(liveRegion);
    }

    /* ================================================================
       ANUNCIAR PARA LEITOR DE TELA
    ================================================================ */
    function announce(msg) {
        const live = document.getElementById('a11y-live');
        if (live) {
            live.textContent = '';
            setTimeout(() => { live.textContent = msg; }, 50);
        }
    }

    /* ================================================================
       EVENT LISTENERS
    ================================================================ */
    function bindEvents() {
        const fab   = document.getElementById('eco-acessibilidade-btn');
        const panel = document.getElementById('eco-painel-acessibilidade');
        const fechar = document.getElementById('painel-fechar-btn');

        // Abrir/fechar painel
        fab.addEventListener('click', () => {
            const isOpen = panel.classList.toggle('aberto');
            fab.setAttribute('aria-expanded', isOpen.toString());
            fab.setAttribute('aria-label', isOpen ? 'Fechar painel de acessibilidade' : 'Abrir painel de acessibilidade');
            if (isOpen) fechar.focus();
        });

        fechar.addEventListener('click', () => {
            panel.classList.remove('aberto');
            fab.setAttribute('aria-expanded', 'false');
            fab.setAttribute('aria-label', 'Abrir painel de acessibilidade');
            fab.focus();
        });

        // Fechar com ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && panel.classList.contains('aberto')) {
                panel.classList.remove('aberto');
                fab.setAttribute('aria-expanded', 'false');
                fab.focus();
            }
        });

        // Alto Contraste
        document.getElementById('ac-alto-contraste').addEventListener('change', (e) => {
            state.altoContraste = e.target.checked;
            applyState();
            announce(state.altoContraste ? 'Modo alto contraste ativado.' : 'Modo alto contraste desativado.');
        });

        // Tamanho de fonte
        ['normal', 'media', 'grande', 'enorme'].forEach(size => {
            document.getElementById('font-' + size).addEventListener('click', () => {
                state.fonteTamanho = size;
                applyState();
                const labels = { normal: 'normal', media: 'médio', grande: 'grande', enorme: 'muito grande' };
                announce('Tamanho do texto alterado para ' + labels[size] + '.');
            });
        });

        // Fonte dislexia
        document.getElementById('ac-fonte-dislexia').addEventListener('change', (e) => {
            state.fonteDislexia = e.target.checked;
            applyState();
            announce(state.fonteDislexia ? 'Fonte para dislexia ativada.' : 'Fonte para dislexia desativada.');
        });

        // Escala de cinza
        document.getElementById('ac-escala-cinza').addEventListener('change', (e) => {
            state.escalaCinza = e.target.checked;
            applyState();
            announce(state.escalaCinza ? 'Escala de cinza ativada.' : 'Escala de cinza desativada.');
        });

        // Guia de leitura
        document.getElementById('ac-guia-leitura').addEventListener('change', (e) => {
            state.guiaLeitura = e.target.checked;
            applyState();
            announce(state.guiaLeitura ? 'Guia de leitura ativada. Mova o mouse para usar.' : 'Guia de leitura desativada.');
        });

        // Sem animações
        document.getElementById('ac-sem-animacoes').addEventListener('change', (e) => {
            state.semAnimacoes = e.target.checked;
            applyState();
            announce(state.semAnimacoes ? 'Animações pausadas.' : 'Animações reativadas.');
        });

        // Resetar
        document.getElementById('ac-reset').addEventListener('click', () => {
            state = Object.assign({}, defaultState);
            applyState();
            announce('Todas as configurações de acessibilidade foram redefinidas.');
            document.getElementById('ac-status-msg').textContent = 'Configurações redefinidas!';
            setTimeout(() => {
                const msg = document.getElementById('ac-status-msg');
                if (msg) msg.textContent = '';
            }, 3000);
        });

        // Guia de leitura — segue o cursor
        document.addEventListener('mousemove', (e) => {
            if (state.guiaLeitura) {
                const guide = document.getElementById('guia-leitura');
                if (guide) {
                    guide.style.top = (e.clientY - 22) + 'px';
                }
            }
        });

        // Fechar painel ao clicar fora
        document.addEventListener('click', (e) => {
            if (panel.classList.contains('aberto') &&
                !panel.contains(e.target) &&
                e.target !== fab) {
                panel.classList.remove('aberto');
                fab.setAttribute('aria-expanded', 'false');
            }
        });
    }

    /* ================================================================
       ATALHOS DE TECLADO
       Alt+A  → abrir/fechar painel
       Alt+C  → alto contraste
       Alt+Shift+A → redefinir
    ================================================================ */
    function bindShortcuts() {
        document.addEventListener('keydown', (e) => {
            if (e.altKey && !e.shiftKey && e.key.toLowerCase() === 'a') {
                e.preventDefault();
                document.getElementById('eco-acessibilidade-btn').click();
            }
            if (e.altKey && !e.shiftKey && e.key.toLowerCase() === 'c') {
                e.preventDefault();
                state.altoContraste = !state.altoContraste;
                applyState();
                announce(state.altoContraste ? 'Alto contraste ativado.' : 'Alto contraste desativado.');
            }
            if (e.altKey && e.shiftKey && e.key.toLowerCase() === 'a') {
                e.preventDefault();
                state = Object.assign({}, defaultState);
                applyState();
                announce('Configurações de acessibilidade redefinidas.');
            }
        });
    }

    /* ================================================================
       MELHORIAS AUTOMÁTICAS DE ACESSIBILIDADE NA PÁGINA
    ================================================================ */
    function autoEnhance() {
        // Adicionar id ao main se não tiver
        const main = document.querySelector('main');
        if (main && !main.id) main.id = 'conteudo-principal';

        // Adicionar lang ao html se não tiver
        const html = document.documentElement;
        if (!html.lang) html.lang = 'pt-BR';

        // Garantir que todas as imagens têm alt
        document.querySelectorAll('img:not([alt])').forEach(img => {
            img.alt = '';
            img.setAttribute('role', 'presentation');
        });

        // Garantir que links externos têm aviso
        document.querySelectorAll('a[href^="http"]').forEach(link => {
            if (!link.querySelector('.sr-only') && !link.getAttribute('aria-label')) {
                const domain = (() => {
                    try { return new URL(link.href).hostname; } catch (e) { return ''; }
                })();
                if (domain && !link.href.includes(window.location.hostname)) {
                    const notice = document.createElement('span');
                    notice.className = 'sr-only';
                    notice.textContent = ' (abre em nova aba)';
                    link.appendChild(notice);
                    link.setAttribute('target', '_blank');
                    link.setAttribute('rel', 'noopener noreferrer');
                }
            }
        });

        // Garantir que todos os botões sem texto têm aria-label
        document.querySelectorAll('button:not([aria-label])').forEach(btn => {
            if (!btn.textContent.trim()) {
                btn.setAttribute('aria-label', 'Botão');
            }
        });
    }

    /* ================================================================
       INICIALIZAÇÃO
    ================================================================ */
    function init() {
        buildPanel();
        applyState();   // aplica estado salvo antes de qualquer render
        bindEvents();
        bindShortcuts();

        // Auto-enhance após carregamento do DOM
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', autoEnhance);
        } else {
            autoEnhance();
        }

        // Respeitar preferência do sistema para redução de movimento
        const mq = window.matchMedia('(prefers-reduced-motion: reduce)');
        if (mq.matches && !state.semAnimacoes) {
            state.semAnimacoes = true;
            applyState();
        }
        mq.addEventListener('change', (e) => {
            state.semAnimacoes = e.matches;
            applyState();
        });
    }

    // Iniciar quando o DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
