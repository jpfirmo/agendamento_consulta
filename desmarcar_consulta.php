<?php

session_start();

include_once("config/conexao.php");
include_once("templates/header.php");

// 🔒 Verificação de login
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    header("Location: login.php");
    exit();
}

$paciente_id = $_SESSION['usuario_id'];
$consulta_id = $_GET['consulta_id'] ?? null;

// Verifica se ID da consulta existe
if (!$consulta_id) {
    $_SESSION['mensagem'] = "Consulta inválida.";
    $_SESSION['mensagem_tipo'] = "danger";
    header("Location: ver_consulta.php");
    exit();
}

// Verifica se a consulta pertence ao paciente
$sql = "SELECT c.id, c.data_hora, m.nome AS medico, m.especialidade
        FROM consulta c
        JOIN medico m ON c.medico_id = m.id
        WHERE c.id = ? AND c.paciente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$consulta_id, $paciente_id]);
$consulta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$consulta) {
    $_SESSION['mensagem'] = "Consulta não encontrada.";
    $_SESSION['mensagem_tipo'] = "danger";
    header("Location: ver_consulta.php");
    exit();
}
?>

<div class="container mt-5">
    <div class="card shadow-lg p-4 rounded-4" style="max-width: 650px; margin: auto;">

        <h2 class="text-center mb-4 text-danger">Desmarcar Consulta</h2>

        <!-- Mensagens -->
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="alert alert-<?= $_SESSION['mensagem_tipo'] ?> text-center">
                <?= $_SESSION['mensagem'] ?>
            </div>
            <?php unset($_SESSION['mensagem'], $_SESSION['mensagem_tipo']); ?>
        <?php endif; ?>

        <p class="text-center fs-5">
            Tem certeza que deseja <strong class="text-danger">desmarcar</strong> esta consulta?
        </p>

        <div class="p-3 bg-light rounded-3 border mb-3">
            <p><strong>Médico:</strong> <?= $consulta['medico'] ?></p>
            <p><strong>Especialidade:</strong> <?= $consulta['especialidade'] ?></p>
            <p><strong>Data e Hora:</strong> <?= date("d/m/Y H:i", strtotime($consulta['data_hora'])) ?></p>
        </div>

        <!-- Botões -->
        <div class="d-flex justify-content-between mt-4">
            <a href="ver_consulta.php" class="btn btn-secondary">← Voltar</a>

            <!-- Botão para abrir o modal -->
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal">
                Desmarcar
            </button>
        </div>

    </div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Desmarcação</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center fs-5">
                Tem certeza que deseja <strong class="text-danger">desmarcar</strong> esta consulta?
                Esta ação não pode ser desfeita.
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                <form action="processa_desmarcacao.php" method="POST">
                    <input type="hidden" name="consulta_id" value="<?= $consulta_id ?>">
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Fade-out das mensagens -->
<script>
setTimeout(() => {
    const alerta = document.querySelector(".alert");
    if (alerta) {
        alerta.style.transition = "opacity 0.6s ease";
        alerta.style.opacity = "0";
        setTimeout(() => alerta.remove(), 600);
    }
}, 3000);
</script>

<?php include_once("templates/footer.php"); ?>
