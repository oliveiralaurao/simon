<?php
session_start(); // Inicia a sessão

// Define o fuso horário para -3 (Horário de Brasília)
date_default_timezone_set('America/Sao_Paulo');

if (!isset($_SESSION['email'])) {
    header('Location: ../login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

require_once('../startup/connectBD.php'); 

$data = json_decode(file_get_contents("php://input"), true);

var_dump($data); // Remova ou comente esta linha em produção

$data_inicio = $data['data_inicio']; // Use o formato ISO para facilitar a manipulação
$data_fim = $data['data_fim'];

// Converter as datas para o horário correto (se necessário)
$data_inicio = date('Y-m-d H:i:s', strtotime($data_inicio));
$data_fim = date('Y-m-d H:i:s', strtotime($data_fim));

$pontuacao = $data['pontuacao'];
$avioes_abatidos = $data['avioes_abatidos'];
$tiros_disparados = $data['tiros_disparados'];
$distancia_percorrida = $data['distancia_percorrida'];
$tempo_total = $data['tempo_total']; // Agora estamos pegando o tempo total diretamente do JSON

// Salvar a jogada no banco de dados com o ID do usuário
$sql = "INSERT INTO partidas (data_inicio, data_fim, pontuacao, tempo_total, avioes_abatidos, tiros_disparados, distancia_percorrida, usuarios_id_usuario) 
        VALUES ('$data_inicio', '$data_fim', $pontuacao, $tempo_total, $avioes_abatidos, $tiros_disparados, $distancia_percorrida, '$id_usuario')";

if ($mysqli->query($sql) === TRUE) {
    echo "Estatísticas da jogada salvas com sucesso.";
} else {
    echo "Erro ao salvar estatísticas: " . $mysqli->error;
}

$sql = "UPDATE estatisticas_totais SET 
            total_avioes_abatidos = total_avioes_abatidos + $avioes_abatidos,
            total_tiros_disparados = total_tiros_disparados + $tiros_disparados,
            total_distancia_percorrida = total_distancia_percorrida + $distancia_percorrida 
        WHERE usuarios_id_usuario = '$id_usuario'";

$mysqli->query($sql);

?>
