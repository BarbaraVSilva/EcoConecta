let map; // Variável global para o mapa

document.addEventListener('DOMContentLoaded', () => {
    // Inicializa o mapa com as coordenadas do Brasil
    map = L.map('map-container').setView([-14.235, -51.925], 4);

    // Adiciona o tile layer do OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // Ícones personalizados
    const hortaIcon = L.divIcon({
        className: 'custom-div-icon',
        html: "<div style='background-color:#2ECC71; color:white; border-radius:50%; width:30px; height:30px; display:flex; justify-content:center; align-items:center; font-weight:bold; border:2px solid white; box-shadow:0 0 10px rgba(0,0,0,0.3);'>🌱</div>",
        iconSize: [30, 30],
        iconAnchor: [15, 15]
    });

    const coletaIcon = L.divIcon({
        className: 'custom-div-icon',
        html: "<div style='background-color:#F39C12; color:white; border-radius:50%; width:30px; height:30px; display:flex; justify-content:center; align-items:center; font-weight:bold; border:2px solid white; box-shadow:0 0 10px rgba(0,0,0,0.3);'>♻️</div>",
        iconSize: [30, 30],
        iconAnchor: [15, 15]
    });

    const lojaIcon = L.divIcon({
        className: 'custom-div-icon',
        html: "<div style='background-color:#9B59B6; color:white; border-radius:50%; width:30px; height:30px; display:flex; justify-content:center; align-items:center; font-weight:bold; border:2px solid white; box-shadow:0 0 10px rgba(0,0,0,0.3);'>🛍️</div>",
        iconSize: [30, 30],
        iconAnchor: [15, 15]
    });

    const trocaIcon = L.divIcon({
        className: 'custom-div-icon',
        html: "<div style='background-color:#3498DB; color:white; border-radius:50%; width:30px; height:30px; display:flex; justify-content:center; align-items:center; font-weight:bold; border:2px solid white; box-shadow:0 0 10px rgba(0,0,0,0.3);'>🔄</div>",
        iconSize: [30, 30],
        iconAnchor: [15, 15]
    });

    // Busca dados reais do banco de dados
    fetch('controllers/get_pontos.php')
        .then(response => response.json())
        .then(pontos => {
            pontos.forEach(ponto => {
                let icon = coletaIcon;
                if(ponto.tipo === 'horta') icon = hortaIcon;
                else if(ponto.tipo === 'loja') icon = lojaIcon;
                else if(ponto.tipo === 'troca') icon = trocaIcon;
                
                let statusLabel = "";
                let sponsorLabel = "";
                if(ponto.tipo === 'horta') {
                    const statusColors = { 'degradado': '#E74C3C', 'em_restauracao': '#F1C40F', 'produtivo': '#2ECC71' };
                    statusLabel = `<br><span style="color:${statusColors[ponto.status]}; font-weight:bold; font-size:0.8rem;">Status: ${ponto.status.replace('_', ' ')}</span>`;
                    
                    if (ponto.padrinho_nome) {
                        sponsorLabel = `<br><span style="color:#27AE60; font-weight:bold; font-size:0.85rem;">🌿 Apadrinhado por: ${ponto.padrinho_nome}</span>`;
                    } else {
                        sponsorLabel = `<br><span style="color:gray; font-size:0.8rem; font-style:italic;">Disponível para Apadrinhamento Corporativo</span>`;
                    }
                }

                let popupContent = `<b>${ponto.titulo}</b>${statusLabel}${sponsorLabel}<br>${ponto.descricao}`;
                
                if (typeof isLoggedIn !== 'undefined' && isLoggedIn) {
                    popupContent += `<br><br><div style="display:flex; flex-direction:column; gap:5px;">`;
                    
                    // Doação solidária (apenas para cidadãos, e em hortas produtivas)
                    if(ponto.tipo === 'horta' && ponto.status === 'produtivo' && typeof userPerfil !== 'undefined' && userPerfil === 'cidadao') {
                        popupContent += `<button onclick="abrirDoacaoModal(${ponto.id})" style="background:#2ECC71; color:white; border:none; padding:5px 10px; border-radius:5px; cursor:pointer; width:100%;">🍲 Doar Colheita</button>`;
                    }
                    
                    // Apadrinhamento corporativo (apenas para empresas, em hortas sem patrocinador)
                    if(ponto.tipo === 'horta' && !ponto.empresa_padrinho_id && typeof userPerfil !== 'undefined' && userPerfil === 'empresa') {
                        popupContent += `<button onclick="apadrinharHorta(${ponto.id})" style="background:#27AE60; color:white; border:none; padding:5px 10px; border-radius:5px; cursor:pointer; width:100%;">🌿 Apadrinhar esta Horta</button>`;
                    }
                    
                    popupContent += `<button onclick="abrirReportModal(${ponto.id})" style="background:#E74C3C; color:white; border:none; padding:5px 10px; border-radius:5px; cursor:pointer; width:100%;">⚠️ Reportar Problema</button></div>`;
                } else {
                    popupContent += `<br><br><small style="color:gray;">Faça login para interagir com este ponto.</small>`;
                }

                L.marker([ponto.latitude, ponto.longitude], { icon: icon }).addTo(map)
                    .bindPopup(popupContent);
            });
            
            // Ajusta o zoom se houver pontos e o usuário não buscou nada
            if(pontos.length > 0) {
                const group = new L.featureGroup(pontos.map(p => L.marker([p.latitude, p.longitude])));
                map.fitBounds(group.getBounds().pad(0.1));
            }
        });
});

// Função para Apadrinhar Horta (Exclusivo Empresa)
window.apadrinharHorta = function(pontoId) {
    if (confirm("Deseja apadrinhar esta horta comunitária? Sua empresa constará como patrocinadora no mapa!")) {
        fetch('controllers/processa_apadrinhamento.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'ponto_id=' + pontoId
        })
        .then(response => response.text())
        .then(res => {
            alert(res);
            location.reload();
        });
    }
};

// Funções para abrir os modais
window.abrirReportModal = function(id) {
    document.getElementById('ponto_id').value = id;
    document.getElementById('modal-report').style.display = 'block';
};

window.abrirDoacaoModal = function(id) {
    document.getElementById('horta_id').value = id;
    document.getElementById('modal-doacao').style.display = 'block';
};

// Funções de Localização e Busca
window.minhaLocalizacao = function() {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition((position) => {
            const { latitude, longitude } = position.coords;
            map.flyTo([latitude, longitude], 15);
            L.marker([latitude, longitude], { 
                icon: L.divIcon({ html: '<div style="background:blue; width:12px; height:12px; border-radius:50%; border:2px solid white; box-shadow:0 0 5px blue;"></div>', className: 'user-loc' }) 
            }).addTo(map).bindPopup("Você está aqui!").openPopup();
        }, () => {
            alert("Não foi possível acessar sua localização. Verifique as permissões do seu navegador.");
        });
    }
};

window.buscarEndereco = function() {
    const query = document.getElementById('address-search').value;
    if (!query) return;

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                const { lat, lon } = data[0];
                map.flyTo([lat, lon], 14);
            } else {
                alert("Endereço não encontrado.");
            }
        });
};
