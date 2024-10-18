<?php
session_start(); 

require_once('startup/connectBD.php');

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'diario';

switch ($periodo) {
    case 'diario':
        $intervalo = '1 DAY';
        break;
    case 'semanal':
        $intervalo = '1 WEEK';
        break;
    case 'mensal':
        $intervalo = '1 MONTH';
        break;
    default:
        $intervalo = '1 DAY';
}

$query = "
    SELECT u.nome_usuario, r.data_atualizacao, p.pontuacao 
    FROM rankings r
    JOIN usuarios u ON r.usuarios_id_usuario = u.id_usuario
    JOIN (
        SELECT usuarios_id_usuario, MAX(pontuacao) AS pontuacao 
        FROM pontuacoes 
        WHERE data_atualizacao >= NOW() - INTERVAL $intervalo
        GROUP BY usuarios_id_usuario
    ) p 
    ON r.usuarios_id_usuario = p.usuarios_id_usuario
    ORDER BY p.pontuacao DESC";

$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking - Simon</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
<div class="container mt-5">
    <h1 class="text-center">Ranking de Pontuações</h1>

    <form action="ranking.php" method="GET" class="text-center mb-4">
        <label for="periodo" class="form-label">Selecione o Período:</label>
        <select id="periodo" name="periodo" class="form-select w-25 d-inline-block">
            <option selected disabled >Selecione um período</option>
            <option value="diario">Diário</option>
            <option value="semanal">Semanal</option>
            <option value="mensal">Mensal</option>
        </select>
        <button type="submit" class="btn btn-primary ms-2">Filtrar</button>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Nome de Usuário</th>
                <th scope="col">Pontuação</th>
                <th scope="col">Última Atualização</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nome_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($row['pontuacao']); ?></td>
                        <td><?php echo htmlspecialchars(date('d/m/Y H:i:s', strtotime($row['data_atualizacao']))); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">Nenhum ranking encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php
$mysqli->close(); // Fecha a conexão com o banco de dados
?>

