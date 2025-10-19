<?php
session_start();
include_once("config/conexao.php");
include_once("templates/header.php");

// Verifica se há mensagem na sessão
if (isset($_SESSION['mensagem'])) {
    echo '<div class="mensagem">' . htmlspecialchars($_SESSION['mensagem']) . '</div>';
    unset($_SESSION['mensagem']); // Remove a mensagem após exibir
}

$logado = isset($_SESSION['usuario_id']);
$tipo_usuario = $_SESSION['tipo_usuario'] ?? null;
?>

<!-- Link para o CSS -->
<link rel="stylesheet" href="css/style.css">

<div class="container">
    <?php if (!$logado): ?>
        <!-- Se o usuário não estiver logado -->
        <div class="home-minimalista">
            <h1>Bem-vindo ao Sistema de Agendamento de Consultas 🩺</h1>
            <p>Faça seu <a href="login.php">login</a> ou <a href="cadastro.php">cadastro</a> para continuar.</p>
        </div>
    
    <?php else: ?>
        <!-- Se o usuário estiver logado -->
        <div class="home-logado">
            <h1>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h1>

            <?php if ($tipo_usuario === 'paciente'): ?>
                <h2>Suas consultas marcadas</h2>

                <?php
                $stmt = $conn->prepare("SELECT c.id, m.nome AS medico, m.especialidade, c.data_consulta 
                                        FROM consulta c
                                        JOIN medico m ON c.id_medico = m.id
                                        WHERE c.id_paciente = :id_paciente
                                        ORDER BY c.data_consulta ASC");
                $stmt->bindParam(':id_paciente', $_SESSION['usuario_id']);
                $stmt->execute();
                $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php if (count($consultas) > 0): ?>
                    <table class="tabela-consultas">
                        <tr>
                            <th>Médico</th>
                            <th>Especialidade</th>
                            <th>Data</th>
                        </tr>
                        <?php foreach ($consultas as $consulta): ?>
                            <tr>
                                <td><?= htmlspecialchars($consulta['medico']) ?></td>
                                <td><?= htmlspecialchars($consulta['especialidade']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($consulta['data_consulta'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>Você ainda não marcou nenhuma consulta.</p>
                <?php endif; ?>

                <p><a class="botao" href="marcar_consulta.php">Marcar nova consulta</a></p>

            <?php elseif ($tipo_usuario === 'medico'): ?>
                <h2>Consultas do dia</h2>

                <?php
                $hoje = date('Y-m-d');
                $stmt = $conn->prepare("SELECT c.id, p.nome AS paciente, c.data_consulta 
                                        FROM consulta c
                                        JOIN paciente p ON c.id_paciente = p.id
                                        WHERE c.id_medico = :id_medico
                                        AND DATE(c.data_consulta) = :hoje
                                        ORDER BY c.data_consulta ASC");
                $stmt->bindParam(':id_medico', $_SESSION['usuario_id']);
                $stmt->bindParam(':hoje', $hoje);
                $stmt->execute();
                $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php if (count($consultas) > 0): ?>
                    <table class="tabela-consultas">
                        <tr>
                            <th>Paciente</th>
                            <th>Horário</th>
                        </tr>
                        <?php foreach ($consultas as $consulta): ?>
                            <tr>
                                <td><?= htmlspecialchars($consulta['paciente']) ?></td>
                                <td><?= date('H:i', strtotime($consulta['data_consulta'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>Você não tem consultas marcadas para hoje.</p>
                <?php endif; ?>

            <?php endif; ?>

            <p><a class="botao botao-sair" href="logout.php">Sair</a></p>
        </div>
    <?php endif; ?>
</div>

<?php include_once("templates/footer.php"); ?>

