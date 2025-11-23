<?php
session_start();
include_once("templates/header.php");

// Se o usuário não estiver logado ou não for médico, redireciona
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'medico') {
    header("Location: login.php");
    exit;
}

$nome = htmlspecialchars($_SESSION['usuario_nome']);
?>

<div class="container my-5">

    <div class="card shadow-lg border-0 rounded-4 p-5 mx-auto" style="max-width: 800px;">
        <h1 class="text-center mb-4" style="font-weight: 700;">
            Bem-vindo, Dr(a). <?= $nome ?> 🩺
        </h1>

        <h4 class="text-center text-muted mb-3">Sua área de médico</h4>

        <p class="text-center text-secondary mb-4">
            Aqui você pode visualizar suas consultas marcadas e gerenciar sua agenda.
        </p>

        <div class="d-flex justify-content-center gap-3">

            <a href="agenda_completa.php" 
               class="btn btn-primary px-4 py-2 mx-2" 
               style="font-size: 1.1rem; border-radius: 12px;">
               Ver Agenda Completa
            </a>

        </div>
    </div>

</div>

<?php include_once("templates/footer.php"); ?>
