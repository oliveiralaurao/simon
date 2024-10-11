<?php
session_start(); 

require_once '../startup/connectBD.php';

$nome_usuario = addslashes($_POST['username']);
$email = addslashes($_POST['email']);
$senha = addslashes($_POST['password']);

if (!empty($nome_usuario) && !empty($email) && !empty($senha)) {
    $query_email_check = "SELECT * FROM usuarios WHERE email_usuario = '$email'";
    $result_email = $mysqli->query($query_email_check);
    
    $query_username_check = "SELECT * FROM usuarios WHERE nome_usuario = '$nome_usuario'";
    $result_username = $mysqli->query($query_username_check);
    
    if ($result_email->num_rows > 0) {
        $_SESSION['aviso'] = "Este e-mail já está cadastrado. Tente outro.";
        header("Location: ../cadastro.php"); 
        exit();
    } elseif ($result_username->num_rows > 0) {
        $_SESSION['aviso'] = "Este nome de usuário já está em uso. Escolha outro.";
        header("Location: ../cadastro.php"); 
        exit();
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $query = "INSERT INTO usuarios (`id_usuario`, `nome_usuario`, `email_usuario`, `senha_usuario`, `data_criacao`) 
                  VALUES (null, '$nome_usuario', '$email', '$senha_hash', NOW())";

        if ($mysqli->query($query)) {
            $_SESSION['aviso'] = "Cadastro realizado com sucesso! Você pode fazer login agora.";
            header("Location: ../login.php");
            exit();
        } else {
            $_SESSION['aviso'] = "Erro ao inserir registro: " . $mysqli->error;
            header("Location: ../cadastro.php"); 
            exit();
        }
    }
} else {
    $_SESSION['aviso'] = "Preencha todos os campos!";
    header("Location: ../cadastro.php"); // Redirecione para a página de cadastro
    exit();
}
?>
