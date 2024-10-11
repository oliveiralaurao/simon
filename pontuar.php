<?php
session_start(); // Inicia a sessão

require_once('startup/connectBD.php'); // Inclui a configuração do banco de dados

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php'); // Redireciona se o usuário não estiver logado
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Busca as pontuações do usuário
$query = "SELECT pontuacao, data_partida FROM pontuacoes WHERE usuarios_id_usuario = ? ORDER BY data_partida DESC";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pontuações - Simon</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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

    <div class="container mt-5">
        <h1 class="text-center">Suas Pontuações</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Pontuação</th>
                    <th scope="col">Data</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['pontuacao']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y H:i:s', strtotime($row['data_partida']))); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center">Nenhuma pontuação encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php
$stmt->close();
$mysqli->close(); // Fecha a conexão com o banco de dados
?>
