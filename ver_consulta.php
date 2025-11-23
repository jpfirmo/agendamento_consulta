<?php
session_start();
include_once("testes/conexao_teste.php");
include_once("templates/header.php");

//  Verificar login como paciente
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    header("Location: login.php");
    exit;
}

$paciente_id = $_SESSION['usuario_id'];

//  Buscar consultas do paciente
$sql = "SELECT c.id, c.data_hora, c.descricao, 
               m.nome AS medico_nome, m.especialidade
        FROM consulta c
        INNER JOIN medico m ON m.id = c.medico_id
        WHERE c.paciente_id = ?
        ORDER BY c.data_hora ASC";

$stmt = $conn->prepare($sql);
$stmt->execute([$paciente_id]);
$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minhas Consultas</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<h1 class="mb-4">Minhas Consultas</h1>

<!-- Botão Voltar Bootstrap -->
<a href="home_paciente.php" class="btn btn-primary mb-3">
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

    <div class="alert alert-info text-center">Você ainda não possui consultas marcadas.</div>

<?php else: ?>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Médico</th>
            <th>Especialidade</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($consultas as $c):
            $data = date('d/m/Y', strtotime($c['data_hora']));
            $hora = date('H:i', strtotime($c['data_hora']));
        ?>
        <tr>
            <td><?= htmlspecialchars($c['medico_nome']) ?></td>
            <td><?= htmlspecialchars($c['especialidade']) ?></td>
            <td><?= $data ?></td>
            <td><?= $hora ?></td>
            <td><?= htmlspecialchars($c['descricao']) ?></td>
            <td>
                <a href="remarcar_consulta.php?id=<?= $c['id'] ?>" class="btn btn-warning btn-sm">
                    Remarcar
                </a>

                <!-- BOTÃO ABRE O MODAL -->
                <button 
                    class="btn btn-danger btn-sm"
                    onclick="abrirModalDesmarcar(<?= $c['id'] ?>)">
                    Desmarcar
                </button>

            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

<!-- MODAL BOOTSTRAP CONFIRMAÇÃO -->
<div class="modal fade" id="modalDesmarcar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Confirmar Desmarcação</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        Tem certeza de que deseja desmarcar esta consulta?
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        
        <a id="btnConfirmar" href="#" class="btn btn-danger">
            Confirmar
        </a>
      </div>

    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function abrirModalDesmarcar(id) {
    // Define o link de confirmação para o id correto
    document.getElementById('btnConfirmar').href = "processa_desmarcacao.php?id=" + id;

    // Abre o modal
    const modal = new bootstrap.Modal(document.getElementById('modalDesmarcar'));
    modal.show();
}
</script>

</body>
</html>
