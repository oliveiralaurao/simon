let airplane = document.getElementById('airplane');
let gameArea = document.getElementById('game-area');
let scoreElement = document.getElementById('score');
let fuelElement = document.getElementById('fuel');

let pontuacao = 0;
let combustivel = 100;
let avioesAbatidos = 0;
let tirosDisparados = 0;
let jogando = true;

// Variáveis para armazenar a data/hora de início e fim
let dataInicio;
let dataFim;

const velocidadeAviao = 5; // Velocidade de movimento
const velocidadeJogo = 3; // Velocidade com que o cenário se move para baixo

// Captura a data/hora de início do jogo
function iniciarJogo() {
    dataInicio = new Date(); // Captura a data/hora de início
    console.log(`Início do jogo: ${dataInicio.toLocaleString('pt-BR', { timeZone: 'America/Sao_Paulo' })}`);
}

// Movimentação do avião (esquerda e direita)
document.addEventListener("keydown", function(event) {
    if (event.key === "ArrowLeft" && airplane.offsetLeft > 0) {
        airplane.style.left = (airplane.offsetLeft - velocidadeAviao) + "px";
    }
    if (event.key === "ArrowRight" && airplane.offsetLeft + airplane.offsetWidth < gameArea.offsetWidth) {
        airplane.style.left = (airplane.offsetLeft + velocidadeAviao) + "px";
    }
});

// Gerar inimigos e obstáculos
function gerarInimigo() {
    const inimigo = document.createElement('div');
    inimigo.classList.add('enemy');
    inimigo.style.top = '-128px'; // Aparecerá fora da tela
    inimigo.style.left = Math.random() * (gameArea.offsetWidth - 40) + 'px';
    
    // Criando a tag de imagem
    const imagem = document.createElement('img');
    imagem.src = './images/carvao.png'; // Caminho da imagem
    imagem.style.width = '128px'; // Defina a largura da imagem
    imagem.style.height = '128px'; // Defina a altura da imagem
    
    inimigo.appendChild(imagem); // Adiciona a imagem à div
    gameArea.appendChild(inimigo);
    moverInimigo(inimigo);
}

// Mover inimigos para baixo
function moverInimigo(inimigo) {
    let intervalo = setInterval(() => {
        if (!jogando) {
            clearInterval(intervalo);
            return;
        }

        // Verificar se colidiu com o avião
        if (verificarColisao(airplane, inimigo)) {
            perderVida();
            inimigo.remove();
            clearInterval(intervalo);
            return;
        }

        inimigo.style.top = (inimigo.offsetTop + velocidadeJogo) + 'px';

        if (inimigo.offsetTop > gameArea.offsetHeight) {
            inimigo.remove();
            clearInterval(intervalo);
        }
    }, 20);
}

// Verificar colisão
function verificarColisao(aviao, inimigo) {
    const rect1 = aviao.getBoundingClientRect();
    const rect2 = inimigo.getBoundingClientRect();

    return !(rect1.right < rect2.left ||
             rect1.left > rect2.right ||
             rect1.bottom < rect2.top ||
             rect1.top > rect2.bottom);
}

// Perder uma vida
function perderVida() {
    jogando = false;
    alert('Você colidiu! Fim de jogo.');
    dataFim = new Date(); // Captura a data/hora de fim
    console.log(`Fim do jogo: ${dataFim.toLocaleString('pt-BR', { timeZone: 'America/Sao_Paulo' })}`);
    salvarEstatisticas();
}

// Disparo automático
function disparar() {
    if (!jogando) return;

    tirosDisparados += 1;
    const tiro = document.createElement('div');
    tiro.classList.add('bullet'); // Agora a classe é bullet e não enemy
    tiro.style.top = airplane.offsetTop + 'px';
    tiro.style.left = (airplane.offsetLeft + airplane.offsetWidth / 6 - 3.5) + 'px'; // Centralizar o tiro
    gameArea.appendChild(tiro);
    moverTiro(tiro);
}

function moverTiro(tiro) {
    let intervalo = setInterval(() => {
        tiro.style.top = (tiro.offsetTop - 10) + 'px';

        const inimigos = document.querySelectorAll('.enemy');
        inimigos.forEach(inimigo => {
            if (verificarColisao(tiro, inimigo)) {
                avioesAbatidos += 1;
                pontuacao += 100;
                inimigo.remove();
                tiro.remove();
                clearInterval(intervalo);
            }
        });

        if (tiro.offsetTop < 0) {
            tiro.remove();
            clearInterval(intervalo);
        }
    }, 20);
}

// Combustível do avião
setInterval(() => {
    if (combustivel > 0 && jogando) {
        combustivel -= 1;
        fuelElement.textContent = `Combustível: ${combustivel}%`;
        if (combustivel <= 0) {
            combustivel = 0;
            perderVida();
        }
    }
}, 1000);

// Gerar inimigos periodicamente
setInterval(() => {
    if (jogando) {
        gerarInimigo();
    }
}, 2000);

// Disparar tiros a cada 500ms
setInterval(() => {
    if (jogando) {
        disparar();
    }
}, 500);

// Atualizar pontuação periodicamente
setInterval(() => {
    if (jogando) {
        pontuacao += 1;
        scoreElement.textContent = `Pontuação: ${pontuacao}`;
    }
}, 100);

// Salvar estatísticas no fim do jogo
function salvarEstatisticas() {
    const tempoTotal = Math.floor((dataFim - dataInicio) / 1000); // Calcular o tempo total em segundos
    const dados = {
        pontuacao: pontuacao,
        avioes_abatidos: avioesAbatidos,
        tiros_disparados: tirosDisparados,
        combustivel: combustivel,
        data_inicio: dataInicio.toISOString(), // Usando toISOString para salvar em formato padrão
        data_fim: dataFim.toISOString(), // Usando toISOString para salvar em formato padrão
        distancia_percorrida: pontuacao * 10, // Exemplo de cálculo de distância
        tempo_total: tempoTotal // Adicionando tempo total
    };
    
    fetch('back/salvar_estatisticas.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dados)
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        alert('Jogo salvo! Estatísticas atualizadas.');
    })
    .catch(error => console.error('Erro:', error));
}

// Inicia o jogo quando a página é carregada
window.onload = iniciarJogo;
