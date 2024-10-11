<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simon - Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-4">
                <h2 class="text-center mb-4">Cadastro</h2>
                <form action="back/cadastroUser.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nome de usuário</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Escolha um nome de usuário" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" required>
                    </div>
                    <div id="aviso">
                        <?php
                        session_start(); // Inicie a sessão
                        if (isset($_SESSION['aviso'])) {
                            echo '<div class="alert alert-warning">' . $_SESSION['aviso'] . '</div>';
                            unset($_SESSION['aviso']); // Limpe a mensagem após exibi-la
                        }
                        ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                </form>
                <div class="mt-3 text-center">
                    <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
