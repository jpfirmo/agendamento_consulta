<?php
session_start();
include_once("config/conexao.php");

// Verifica se ID veio pelo GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['mensagem'] = "Consulta inválida.";
    $_SESSION['mensagem_tipo'] = "danger";
    header("Location: ver_consulta.php");
    exit;
}

$consulta_id = $_GET['id'];

// Verifica login
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    $_SESSION['mensagem'] = "Você não tem permissão para isso.";
    $_SESSION['mensagem_tipo'] = "danger";
    header("Location: ver_consulta.php");
    exit;
}

$paciente_id = $_SESSION['usuario_id'];

// Verifica se consulta pertence ao paciente
$sql_check = "SELECT id FROM consulta WHERE id = ? AND paciente_id = ?";
$stmt = $conn->prepare($sql_check);
$stmt->execute([$consulta_id, $paciente_id]);

if ($stmt->rowCount() === 0) {
    $_SESSION['mensagem'] = "Consulta não encontrada.";
    $_SESSION['mensagem_tipo'] = "danger";
    header("Location: ver_consulta.php");
    exit;
}

// Deleta a consulta
$sql_delete = "DELETE FROM consulta WHERE id = ?";
$stmt = $conn->prepare($sql_delete);

if ($stmt->execute([$consulta_id])) {
    $_SESSION['mensagem'] = "Consulta desmarcada com sucesso!";
    $_SESSION['mensagem_tipo'] = "success";
} else {
    $_SESSION['mensagem'] = "Erro ao desmarcar consulta.";
    $_SESSION['mensagem_tipo'] = "danger";
}

header("Location: ver_consulta.php");
exit;
