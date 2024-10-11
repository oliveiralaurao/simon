<?php
session_start();

require_once('../startup/connectBD.php'); 

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.php'); 
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$pontuacao = $_POST['pontuacao']; 

if (!empty($pontuacao) && is_numeric($pontuacao) && $pontuacao >= 0) {
    $query = "INSERT INTO pontuacoes (usuarios_id_usuario, pontuacao, data_partida) VALUES (?, ?, NOW())";
    $stmt = $mysqli->prepare($query);
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $mysqli->error);
    }
    
    $stmt->bind_param("ii", $id_usuario, $pontuacao);
    if ($stmt->execute()) {
        // Atualização de ranking
        $query_ranking = "INSERT INTO rankings (data_atualizacao, usuarios_id_usuario) VALUES (NOW(), ?)
                          ON DUPLICATE KEY UPDATE data_atualizacao = NOW()";
        $stmt_ranking = $mysqli->prepare($query_ranking);
        $stmt_ranking->bind_param("i", $id_usuario);
        
        if (!$stmt_ranking->execute()) {
            die("Erro ao atualizar ranking: " . $stmt_ranking->error);
        }
        
        echo "Pontuação salva com sucesso!";
        header('Location: ../index.php'); 
        exit();
    } else {
        die("Erro ao salvar a pontuação: " . $stmt->error);
    }
    
    $stmt->close();
} else {
    echo "Pontuação inválida.";
}


$mysqli->close(); // Fecha a conexão com o banco de dados
?>
