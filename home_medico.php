<?php
session_start();

// Se o usuário não estiver logado ou não for médico, redireciona
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'medico') {
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
    <title>Home - Médico</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Bem-vindo, Dr(a). <?= $nome ?> 🩺</h1>
            <a href="logout.php" class="btn-logout">Sair</a>
        </header>

        <main>
            <h2>Sua área de médico</h2>
            <p>Aqui você pode visualizar suas consultas marcadas e gerenciar sua agenda.</p>

            <div class="acoes">
                <a href="consultas_hoje.php" class="btn">Consultas de Hoje</a>
                <a href="agenda_completa.php" class="btn">Ver Agenda Completa</a>
            </div>
        </main>
    </div>
</body>
</html>
