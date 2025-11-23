<?php
session_start();
include_once("config/conexao.php");
include_once("templates/header.php");

// Bloqueia acesso caso não seja paciente
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    header("Location: login.php");
    exit;
}

// Buscar especialidades
$sqlEsp = "SELECT DISTINCT especialidade FROM medico ORDER BY especialidade";
$stmtEsp = $conn->prepare($sqlEsp);
$stmtEsp->execute();
$especialidades = $stmtEsp->fetchAll(PDO::FETCH_ASSOC);

// Variáveis para repovoar formulário
$especialidadeSel = $_GET['especialidade'] ?? '';
$medicoSel = $_GET['medico_id'] ?? '';
$dataSel = $_GET['data'] ?? '';

$medicos = [];
$horarios_disponiveis = [];

// Buscar médicos da especialidade
if ($especialidadeSel) {
    $sqlMed = "SELECT id, nome FROM medico WHERE especialidade = ?";
    $stmtMed = $conn->prepare($sqlMed);
    $stmtMed->execute([$especialidadeSel]);
    $medicos = $stmtMed->fetchAll(PDO::FETCH_ASSOC);
}

// Buscar horários disponíveis
if ($medicoSel && $dataSel) {

    $paciente_id = $_SESSION['usuario_id'];

    $horarios_fixos = ["08:00","09:00","10:00","11:00","13:00","14:00","15:00","16:00"];

    foreach ($horarios_fixos as $h) {

        $data_hora = $dataSel . " " . $h . ":00";

        // Médico ocupado?
        $sql1 = "SELECT id FROM consulta WHERE medico_id = ? AND data_hora = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute([$medicoSel, $data_hora]);
        $ocupado_medico = $stmt1->fetch();

        // Paciente já tem consulta?
        $sql2 = "SELECT id FROM consulta WHERE paciente_id = ? AND data_hora = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$paciente_id, $data_hora]);
        $ocupado_paciente = $stmt2->fetch();

        if (!$ocupado_medico && !$ocupado_paciente) {
            $horarios_disponiveis[] = $h;
        }
    }
}
?>

<link rel="stylesheet" href="css/style.css">

<div class="container mt-5">
    <div class="card shadow-lg p-4 rounded-4" style="max-width: 550px; margin: auto;">

        <h2 class="text-center mb-4">Marcar Consulta</h2>

        <!-- Mensagens -->
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="alert alert-<?= $_SESSION['mensagem_tipo'] ?> text-center">
                <?= $_SESSION['mensagem'] ?>
            </div>
            <?php unset($_SESSION['mensagem'], $_SESSION['mensagem_tipo']); ?>
        <?php endif; ?>

        <form method="POST" action="processa_marcacao.php">

            <!-- Especialidade -->
            <div class="mb-3">
                <label class="form-label fw-bold">Especialidade:</label>
                <select name="especialidade" class="form-select"
                    required onchange="window.location='marcar_consulta.php?especialidade='+this.value">
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
                <select name="medico_id" class="form-select"
                    required onchange="window.location='marcar_consulta.php?especialidade=<?= $especialidadeSel ?>&medico_id='+this.value">
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
                    onchange="window.location='marcar_consulta.php?especialidade=<?= $especialidadeSel ?>&medico_id=<?= $medicoSel ?>&data='+this.value">
            </div>

            <!-- Horário -->
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

            <!-- Descrição -->
            <div class="mb-3">
                <label class="form-label fw-bold">Descrição (opcional):</label>
                <textarea name="descricao" class="form-control" rows="3"></textarea>
            </div>

            <!-- Botões -->
            <div class="d-flex justify-content-between mt-4">
                <a href="home_paciente.php" class="btn btn-secondary">
                    ← Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    Agendar Consulta
                </button>
            </div>

        </form>
    </div>
</div>

<?php include_once("templates/footer.php"); ?>
