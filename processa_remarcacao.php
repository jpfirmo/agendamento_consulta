<?php
session_start();
include_once("config/conexao.php");

// Verifica login
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    header("Location: login.php");
    exit;
}

$id_paciente = $_SESSION['usuario_id'];

// Campos do formulário
$id_consulta = $_POST['consulta_id'] ?? null;
$medico_id = $_POST['medico_id'] ?? null;
$data = $_POST['data'] ?? null;
$horario = $_POST['horario'] ?? null;

// Verificação básica
if (!$id_consulta || !$medico_id || !$data || !$horario) {
    $_SESSION['mensagem'] = "Preencha todos os campos.";
    $_SESSION['mensagem_tipo'] = "danger";
    header("Location: remarcar_consulta.php?consulta_id=" . $id_consulta);
    exit();
}

// 1️⃣ Verifica se a consulta pertence ao paciente
$sqlCons = "SELECT * FROM consulta WHERE id = ? AND paciente_id = ?";
$stmtCons = $conn->prepare($sqlCons);
$stmtCons->execute([$id_consulta, $id_paciente]);

if ($stmtCons->rowCount() == 0) {
    $_SESSION['mensagem'] = "Consulta não encontrada ou não pertence a você.";
    $_SESSION['mensagem_tipo'] = "danger";
    header("Location: ver_consulta.php");
    exit();
}

// Monta o campo final data_hora
$data_hora = $data . " " . $horario . ":00";

// 2️⃣ Médico ocupado?
$sqlCheckMedico = "SELECT id FROM consulta 
                   WHERE medico_id = ? AND data_hora = ? AND id != ?";
$stmtMedico = $conn->prepare($sqlCheckMedico);
$stmtMedico->execute([$medico_id, $data_hora, $id_consulta]);

if ($stmtMedico->rowCount() > 0) {
    $_SESSION['mensagem'] = "Este horário já está ocupado para esse médico.";
    $_SESSION['mensagem_tipo'] = "warning";
    header("Location: remarcar_consulta.php?consulta_id=" . $id_consulta);
    exit();
}

// 3️⃣ Paciente ocupado?
$sqlCheckPaciente = "SELECT id FROM consulta 
                     WHERE paciente_id = ? AND data_hora = ? AND id != ?";
$stmtPaciente = $conn->prepare($sqlCheckPaciente);
$stmtPaciente->execute([$id_paciente, $data_hora, $id_consulta]);

if ($stmtPaciente->rowCount() > 0) {
    $_SESSION['mensagem'] = "Você já tem outra consulta nesse horário.";
    $_SESSION['mensagem_tipo'] = "warning";
    header("Location: remarcar_consulta.php?consulta_id=" . $id_consulta);
    exit();
}

// 4️⃣ Atualiza consulta
$sqlUpdate = "UPDATE consulta 
              SET medico_id = ?, data_hora = ?
              WHERE id = ? AND paciente_id = ?";
$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->execute([$medico_id, $data_hora, $id_consulta, $id_paciente]);

$_SESSION['mensagem'] = "Consulta remarcada com sucesso!";
$_SESSION['mensagem_tipo'] = "success";

header("Location: ver_consulta.php");
exit();
