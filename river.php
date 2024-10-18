<?php

session_start(); // Inicia a sessão

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// Pega o ID do usuário da sessão
$id_usuario = $_SESSION['id_usuario'];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>River Raid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            margin: 0;
            overflow: hidden;
            background: black;
            
            font-family: Arial, sans-serif;
        }
        #game-area {
            position: relative;
            width: 100vw;
            height: 100vh;
            /* background: blue; */
            background: rgb(180,124,186);
background: radial-gradient(circle, rgba(180,124,186,1) 0%, rgba(64,25,70,1) 100%);
            overflow: hidden;
        }
        #airplane {
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 128px;
            height: 30px;
            /* background-color: red; */
            left: 50%;
            top: 80%;
            transform: translateX(-50%);
        }
        .enemy {
            position: absolute;
            width: 128px;
            height: 40px;
            /* background-color: green; */
        }
        .bullet {
            position: absolute;
            width: 5px;
            height: 20px;
            background-color: yellow;
        }
        #score, #fuel {
            position: absolute;
            top: 10px;
            color: white;
            font-size: 24px;
        }
        #score {
            left: 10px;
        }
        #fuel {
            right: 10px;
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
          <a class="nav-link active" aria-current="page" href="index.php">Simon</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="river.php">River Raid</a>
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
<div id="game-area">
    <div id="airplane"><img src="./images/aviao.png" alt=""></div>
    <div id="score">Pontuação: 0</div>
    <div id="fuel">Combustível: 100%</div>
</div>

<script src="controle_aviao.js"></script>

</body>
</html>
