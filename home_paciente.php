<?php
session_start();

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
    <div class="container">
        <header>
            <h1>Bem-vindo, <?= $nome ?> 🧑‍⚕️</h1>
            <a href="logout.php" class="btn-logout">Sair</a>
        </header>

        <main>
            <h2>Sua área de paciente</h2>
            <p>Aqui você poderá visualizar suas consultas marcadas e agendar novas.</p>

            <div class="acoes">
                <a href="ver_consultas.php" class="btn">Ver Consultas</a>
                <a href="marcar_consulta.php" class="btn">Marcar Consulta</a>
            </div>
        </main>
    </div>
</body>
</html>
