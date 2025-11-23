<?php
include_once("templates/header_home_principal.php");
?>



<!-- Importar o arquivo CSS -->
<link rel="stylesheet" href="css/style.css">

<div class="container mt-5">
    <div class="text-center">
        <h1>Bem-vindo ao Sistema de Agendamento de Consultas 🩺</h1>
        <p class="mt-3">Acesse sua conta ou cadastre-se para continuar.</p>

        <div class="d-flex justify-content-center mt-4">
            <a href="login.php" class="btn btn-primary mx-2">Fazer Login</a>
            <a href="cadastro.php" class="btn btn-success mx-2">Cadastrar-se</a>
        </div>
    </div>
</div>

<?php
include_once("templates/footer.php");
?>
