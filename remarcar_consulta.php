<?php
session_start();
include_once("config/conexao.php");
include_once("templates/header.php");

// Bloqueia acesso caso não seja paciente
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    header("Location: login.php");
    exit;
}

$paciente_id = $_SESSION['usuario_id'];

// Buscar consultas do paciente
$sqlCons = "SELECT c.id, c.data_hora, m.nome AS medico, m.especialidade 
            FROM consulta c
            JOIN medico m ON c.medico_id = m.id
            WHERE c.paciente_id = ?
            ORDER BY c.data_hora ASC";
$stmtCons = $conn->prepare($sqlCons);
$stmtCons->execute([$paciente_id]);
$consultas = $stmtCons->fetchAll(PDO::FETCH_ASSOC);

// Recebe dados selecionados
$consulta_id = $_GET['consulta_id'] ?? '';
$especialidadeSel = $_GET['especialidade'] ?? '';
$medicoSel = $_GET['medico_id'] ?? '';
$dataSel = $_GET['data'] ?? '';

$medicos = [];
$horarios_disponiveis = [];

// Buscar especialidades
$sqlEsp = "SELECT DISTINCT especialidade FROM medico ORDER BY especialidade";
$stmtEsp = $conn->prepare($sqlEsp);
$stmtEsp->execute();
$especialidades = $stmtEsp->fetchAll(PDO::FETCH_ASSOC);

// Se selecionou especialidade → carregar médicos
if ($especialidadeSel) {
    $sqlMed = "SELECT id, nome FROM medico WHERE especialidade = ?";
    $stmtMed = $conn->prepare($sqlMed);
    $stmtMed->execute([$especialidadeSel]);
    $medicos = $stmtMed->fetchAll(PDO::FETCH_ASSOC);
}

// Se selecionou médico + data → buscar horários disponíveis
if ($medicoSel && $dataSel) {

    $horarios_fixos = ["08:00","09:00","10:00","11:00","13:00","14:00","15:00","16:00"];

    foreach ($horarios_fixos as $h) {

        $data_hora = $dataSel . " " . $h . ":00";

        // Médico ocupado?
        $sql1 = "SELECT id FROM consulta WHERE medico_id = ? AND data_hora = ? AND id != ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute([$medicoSel, $data_hora, $consulta_id]);
        $ocupado_medico = $stmt1->fetch();

        // Paciente já tem outra consulta nesse horário?
        $sql2 = "SELECT id FROM consulta WHERE paciente_id = ? AND data_hora = ? AND id != ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$paciente_id, $data_hora, $consulta_id]);
        $ocupado_paciente = $stmt2->fetch();

        if (!$ocupado_medico && !$ocupado_paciente) {
            $horarios_disponiveis[] = $h;
        }
    }
}
?>

<div class="container mt-5">
    <div class="card shadow-lg p-4 rounded-4" style="max-width: 650px; margin: auto;">

        <h2 class="text-center mb-4">Remarcar Consulta</h2>

        <!-- MENSAGENS COM FADE-OUT -->
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="alert alert-<?= $_SESSION['mensagem_tipo'] ?> text-center" role="alert">
                <?= htmlspecialchars($_SESSION['mensagem']) ?>
            </div>

            <script>
                setTimeout(() => {
                    const alert = document.querySelector(".alert");
                    if (alert) {
                        alert.style.transition = "opacity 0.6s ease";
                        alert.style.opacity = "0";
                        setTimeout(() => alert.remove(), 600);
                    }
                }, 3000);
            </script>

            <?php unset($_SESSION['mensagem'], $_SESSION['mensagem_tipo']); ?>
        <?php endif; ?>


        <!-- Seleção da consulta -->
        <form method="GET" action="remarcar_consulta.php">
            <div class="mb-3">
                <label class="form-label fw-bold">Selecione a consulta:</label>
                <select name="consulta_id" class="form-select" required onchange="this.form.submit()">
                    <option value="">Selecione...</option>

                    <?php foreach ($consultas as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($consulta_id == $c['id']) ? 'selected' : '' ?>>
                            <?= date("d/m/Y H:i", strtotime($c['data_hora'])) ?> — <?= $c['medico'] ?> (<?= $c['especialidade'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if ($consulta_id): ?>

        <form method="POST" action="processa_remarcacao.php">

            <input type="hidden" name="consulta_id" value="<?= $consulta_id ?>">

            <!-- Especialidade -->
            <div class="mb-3">
                <label class="form-label fw-bold">Especialidade:</label>
                <select name="especialidade" class="form-select" required
                    onchange="window.location='remarcar_consulta.php?consulta_id=<?= $consulta_id ?>&especialidade='+this.value">
                    <option value="">Selecione...</option>

                    <?php foreach ($especialidades as $e): ?>
                        <option value="<?= $e['especialidade'] ?>"
                            <?= ($especialidadeSel == $e['especialidade']) ? 'selected' : '' ?>>
                            <?= $e['especialidade'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Médico -->
            <div class="mb-3">
                <label class="form-label fw-bold">Médico:</label>
                <select name="medico_id" class="form-select" required
                    onchange="window.location='remarcar_consulta.php?consulta_id=<?= $consulta_id ?>&especialidade=<?= $especialidadeSel ?>&medico_id='+this.value">
                    <option value="">Selecione...</option>

                    <?php foreach ($medicos as $m): ?>
                        <option value="<?= $m['id'] ?>"
                            <?= ($medicoSel == $m['id']) ? 'selected' : '' ?>>
                            <?= $m['nome'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Data -->
            <div class="mb-3">
                <label class="form-label fw-bold">Data:</label>
                <input type="date" name="data" class="form-control"
                    required min="<?= date('Y-m-d') ?>" value="<?= $dataSel ?>"
                    onchange="window.location='remarcar_consulta.php?consulta_id=<?= $consulta_id ?>&especialidade=<?= $especialidadeSel ?>&medico_id=<?= $medicoSel ?>&data='+this.value">
            </div>

            <!-- Horários -->
            <div class="mb-3">
                <label class="form-label fw-bold">Horário:</label>
                <select name="horario" class="form-select" required>
                    <option value="">Selecione...</option>

                    <?php foreach ($horarios_disponiveis as $h): ?>
                        <option value="<?= $h ?>"><?= $h ?></option>
                    <?php endforeach; ?>

                    <?php if ($medicoSel && $dataSel && empty($horarios_disponiveis)): ?>
                        <option value="">Nenhum horário disponível</option>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Botões -->
            <div class="d-flex justify-content-between mt-4">
                <a href="home_paciente.php" class="btn btn-secondary">← Voltar</a>
                <button type="submit" class="btn btn-primary">Confirmar Remarcação</button>
            </div>

        </form>

        <?php endif; ?>

    </div>
</div>

<?php include_once("templates/footer.php"); ?>
