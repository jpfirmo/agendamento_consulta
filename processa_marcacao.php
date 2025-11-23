<?php

session_start();

include_once("config/conexao.php");

// Verifica login
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    header("Location: login.php");
    exit;
}

// Dados enviados pelo formulário
$paciente_id = $_SESSION['usuario_id'];
$especialidade = $_POST['especialidade'] ?? '';
$medico_id = $_POST['medico_id'] ?? '';
$data = $_POST['data'] ?? '';
$horario = $_POST['horario'] ?? '';
$descricao = $_POST['descricao'] ?? null;

// Verifica se todos os campos necessários vieram preenchidos
if (!$especialidade || !$medico_id || !$data || !$horario) {
    $_SESSION['mensagem'] = "Preencha todos os campos!";
    $_SESSION['mensagem_tipo'] = "erro";
    header("Location: marcar_consulta.php");
    exit;
}

// Monta datetime
$data_hora = $data . " " . $horario . ":00";

// VERIFICA 1: médico já tem consulta nesse horário?
$sql1 = "SELECT id FROM consulta WHERE medico_id = ? AND data_hora = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->execute([$medico_id, $data_hora]);

if ($stmt1->fetch()) {
    $_SESSION['mensagem'] = "Este médico já possui consulta neste horário!";
    $_SESSION['mensagem_tipo'] = "erro";
    header("Location: marcar_consulta.php");
    exit;
}

// VERIFICA 2: paciente já tem consulta no mesmo horário
$sql2 = "SELECT id FROM consulta WHERE paciente_id = ? AND data_hora = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute([$paciente_id, $data_hora]);

if ($stmt2->fetch()) {
    $_SESSION['mensagem'] = "Você já possui uma consulta marcada neste horário!";
    $_SESSION['mensagem_tipo'] = "erro";
    header("Location: marcar_consulta.php");
    exit;
}

// Inserir consulta
$sqlInsert = "INSERT INTO consulta (paciente_id, medico_id, data_hora, descricao)
              VALUES (?, ?, ?, ?)";
$stmtInsert = $conn->prepare($sqlInsert);

if ($stmtInsert->execute([$paciente_id, $medico_id, $data_hora, $descricao])) {
    $_SESSION['mensagem'] = "Consulta agendada com sucesso!";
    $_SESSION['mensagem_tipo'] = "sucesso";
} else {
    $_SESSION['mensagem'] = "Erro ao agendar consulta. Tente novamente.";
    $_SESSION['mensagem_tipo'] = "erro";
}

header("Location: marcar_consulta.php");
exit;
?>
