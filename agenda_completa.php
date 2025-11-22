<?php
session_start();
include_once("testes/conexao_teste.php");
include_once("templates/header_home_principal.php");

// 🔒 Verificar login como médico
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'medico') {
    header("Location: login.php");
    exit;
}

$medico_id = $_SESSION['usuario_id'];

// 📅 Data atual para filtrar somente consultas de hoje em diante
$hoje = date("Y-m-d H:i:s");

// 🔍 Buscar consultas futuras do médico
$sql = "SELECT c.id, c.data_hora, c.descricao,
               p.nome AS paciente_nome
        FROM consulta c
        INNER JOIN paciente p ON p.id = c.paciente_id
        WHERE c.medico_id = ?
          AND c.data_hora >= ?
        ORDER BY c.data_hora ASC";

$stmt = $conn->prepare($sql);
$stmt->execute([$medico_id, $hoje]);
$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agenda Completa</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="container mt-4">

<h1 class="mb-4">Agenda Completa</h1>

<!-- Botão Voltar -->
<a href="home_medico.php" class="btn btn-primary mb-3">
    <i class="fas fa-arrow-left"></i> Voltar
</a>

<!-- Mensagens -->
<?php
if (isset($_SESSION['mensagem'])): ?>
    <div class="alert alert-<?= $_SESSION['mensagem_tipo'] ?> text-center">
        <?= $_SESSION['mensagem'] ?>
    </div>
<?php 
    unset($_SESSION['mensagem'], $_SESSION['mensagem_tipo']);
endif; 
?>

<?php if (count($consultas) === 0): ?>

    <div class="alert alert-info text-center">
        Não há consultas agendadas daqui pra frente.
    </div>

<?php else: ?>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Paciente</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Descrição</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($consultas as $c):
            $data = date('d/m/Y', strtotime($c['data_hora']));
            $hora = date('H:i', strtotime($c['data_hora']));
        ?>
        <tr>
            <td><?= htmlspecialchars($c['paciente_nome']) ?></td>
            <td><?= $data ?></td>
            <td><?= $hora ?></td>
            <td><?= htmlspecialchars($c['descricao']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
