<?php
session_start();
include_once("templates/header.php");


// Se o usuário não estiver logado ou não for paciente, redireciona
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    header("Location: login.php");
    exit;
}

$nome = htmlspecialchars($_SESSION['usuario_nome']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Paciente</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
// Exibe mensagem de login bem-sucedido (ou qualquer outra)
if (isset($_SESSION['mensagem'])) {
    $mensagem = htmlspecialchars($_SESSION['mensagem']);
    $tipo = $_SESSION['mensagem_tipo'] ?? 'sucesso';
    echo "<div class='mensagem $tipo'>$mensagem</div>";

    // Limpa da sessão após exibir
    unset($_SESSION['mensagem']);
    unset($_SESSION['mensagem_tipo']);
}
?>

    <div class="container">
        <header>
            <h1>Bem-vindo, <?= $nome ?> 🧑‍⚕️</h1>
            <a href="logout.php" class="btn-logout">Sair</a>
        </header>

        <main>
            <h2>Sua área de paciente</h2>
            <p>Aqui você poderá visualizar suas consultas marcadas e agendar novas.</p>

            <div class="acoes">
                <a href="ver_consulta.php" class="btn">Ver Consultas</a>
                <a href="marcar_consulta.php" class="btn">Marcar Consulta</a>
            </div>
        </main>
    </div>

<script>
// Efeito de fade-out para mensagens
setTimeout(() => {
    const msg = document.querySelector('.mensagem');
    if (msg) {
        msg.classList.add('fade-out');
        setTimeout(() => msg.remove(), 500);
    }
}, 3000);
</script>

</body>
</html>
