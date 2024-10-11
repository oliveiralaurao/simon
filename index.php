<?php
session_start(); // Inicia a sessão aqui

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simon - Jogo</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Jogo</a>
        </li>
      <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="ranking.php">Ranking</a>
        </li>
      <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="pontuar.php">Pontuação</a>
        </li>
      </ul>
      <form class="d-flex" role="search">
      <a href="logout.php" class="btn btn-danger">Sair</a>
      </form>
    </div>
  </div>
</nav>
    <div class="container text-center" id="ola">
        <div class="container" id="ol">
            <h1>SIMON</h1>
            <div id="score">Pontuação: <span id="score-value">0</span></div>
            <div class="simon">
                <div class="pad red"></div>
                <div class="pad green"></div>
                <div class="pad yellow"></div>
                <div class="pad blue"></div>
                <div class="display" id="display"></div>
            </div>
        </div>​
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
       (function() {
            var pads = document.getElementsByClassName('pad'),
                display = document.getElementById('display'),
                scoreDisplay = document.getElementById('score-value'),
                pattern = [],
                hold = [],
                level,
                speed,
                score = 0; // Variável para armazenar a pontuação

            function playerMouseDown(e) {
                this.className += ' active';
            }

            function playerMouseUp(e) {
                simonMouseUp.apply(this);
                playerClick.apply(this);
            }

            function simonMouseUp() {
                this.className = this.className.replace(/ active/, '');
            }

            function playerHoverOver(e) {
                this.className += ' hover';
            }

            function playerHoverOut(e) {
                this.className = this.className.replace(/ hover/, '');
            }

            function playerClick() {
                if (pads[hold.shift()] != this) {
                    gameOver();
                    return;
                }
                playerSays();
            }

            function clearContents(element) {
                while (element.hasChildNodes()) {
                    element.removeChild(element.lastChild);
                }
            }

            function updateDisplay(text) {
                clearContents(display);
                display.appendChild(document.createTextNode(text));
            }

            function registerHandlers() {
                Array.prototype.forEach.call(pads, function(pad) {
                    pad.style.cursor = 'pointer';
                    pad.addEventListener('mouseover', playerHoverOver, false);
                    pad.addEventListener('mouseout', playerHoverOut, false);
                    pad.addEventListener('mousedown', playerMouseDown);
                    pad.addEventListener('mouseup', playerMouseUp);
                });
            }

            function removeHandlers() {
                Array.prototype.forEach.call(pads, function(pad) {
                    pad.style.cursor = 'default';
                    pad.removeEventListener('mouseover', playerHoverOver);
                    pad.removeEventListener('mouseout', playerHoverOut);
                    pad.removeEventListener('mousedown', playerMouseDown);
                    pad.removeEventListener('mouseup', playerMouseUp);
                });
            }

            function gameOver() {
                updateDisplay('Você Perdeu!');
                setTimeout(function() {
                    saveScore();
                    init(); // Reinicia o jogo
                }, 2000);
            }

            function beginLevel() {
                removeHandlers();
                level++;
                speed -= level * 5;
                updateDisplay('Nível ' + level);
                setTimeout(function() {
                    updateDisplay('Pronto?');
                }, speed);
                setTimeout(function() {
                    updateDisplay('Começando!');
                }, speed * 2);
                setTimeout(function() {
                    generatePattern();
                    updateDisplay('SIMON DIZ');
                    simonSays();
                }, speed * 3);
            }

            function generatePattern() {
                var p = [];
                for (var i = 0, l = (3 + level); i < l; i++) {
                    p.push(Math.floor(Math.random() * 4)); // Corrigido para gerar valores entre 0 e 3
                }
                pattern = p.slice(0), hold = p.slice(0);
            }

            function simonSays() {
                var current = pads[pattern.shift()];
                playerMouseDown.apply(current);
                setTimeout(function() {
                    simonMouseUp.apply(current);
                    if (pattern.length > 0) {
                        setTimeout(simonSays, speed);
                    } else {
                        updateDisplay('Sua vez!');
                        setTimeout(function() {
                            registerHandlers();
                            playerSays();
                        }, 2000);
                    }
                }, 300);
            }

            function playerSays() {
                if (hold.length == 0) {
                    score += 10; // Incrementa a pontuação ao vencer o nível
                    scoreDisplay.textContent = score; // Atualiza a exibição da pontuação
                    updateDisplay('Você ganhou!');
                    setTimeout(function() {
                        beginLevel();
                    }, 2000);
                } else {
                    updateDisplay('Restam ' + hold.length + ' jogadas');
                }
            }

            function startHandler() {
                this.style.cursor = 'default';
                beginLevel();
                this.removeEventListener('click', startHandler);
            }

            function init() {
                level = 0;
                speed = 2005;
                score = 0; 
                scoreDisplay.textContent = score; 
                updateDisplay('Iniciar');
                display.style.cursor = 'pointer';
                display.addEventListener('click', startHandler);
            }
            function saveScore() {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "back/pontuacao.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        console.log(xhr.responseText);
                    }
                };
                xhr.send("pontuacao=" + score);
            }

            init();
        }());
    </script>
</body>
</html>
