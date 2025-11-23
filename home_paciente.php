<?php

session_start();

include_once("templates/header.php");

// Verificação de login
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

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<!-- 🔵 Conteúdo Centralizado -->
<div class="container d-flex justify-content-center mt-5">
    <div class="card p-5 shadow-lg rounded-4 home-card text-center">

        <h2 class="fw-bold mb-3">Bem-vindo, <?= $nome ?> 👨‍⚕️</h2>

        <h4 class="text-secondary">Sua área de paciente</h4>
        <p class="text-muted mb-4">Aqui você poderá visualizar suas consultas marcadas e agendar novas.</p>

        <div class="d-flex justify-content-center gap-3 mt-3">
            <a href="ver_consulta.php" class="btn btn-primary btn-lg px-4">Ver Consultas</a>
            <a href="marcar_consulta.php" class="btn btn-primary btn-lg px-4">Marcar Consulta</a>
        </div>

    </div>
</div>

<!-- Fade-out mensagens -->
<script>
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
