<?php
session_start(); 
require_once('../startup/connectBD.php'); 

$email = $_POST['email-login'];
$senha = $_POST['senha-login'];
$mensagem = "";

if (!empty($email) && !empty($senha)) {
    $query = "SELECT * FROM usuarios WHERE email_usuario='$email'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_usuario = $row['id_usuario'];
        $nome_usuario = $row['nome_usuario'];
        $senha_hash = $row['senha_usuario'];

        if (password_verify($senha, $senha_hash)) {
            $_SESSION['email'] = $email;
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['nome_usuario'] = $nome_usuario;

            header("Location: ../index.php");
            exit();
        } else {
            $mensagem = "Email ou senha incorretos.";
        }
    } else {
        $mensagem = "E-mail não encontrado.";
    }
} else {
    $mensagem = "Preencha todos os campos!";
}

$_SESSION['aviso'] = $mensagem;  // Alterado para 'aviso'
header("Location: ../login.php"); 
exit();
?>
