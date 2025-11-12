<?php
session_start();
?>

<?php include_once("templates/header.php"); ?>

<!-- Importa o CSS -->
<link rel="stylesheet" href="css/style.css">

<div class="container mt-5">
    <div class="card shadow-lg p-4 rounded-4" style="max-width: 400px; margin: auto;">
        <h2 class="text-center mb-4">Login</h2>
        <!-- Mensagem de erro (fade-out) -->
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="mensagem <?= htmlspecialchars($_SESSION['mensagem_tipo']) ?>">
                <?= htmlspecialchars($_SESSION['mensagem']) ?>
            </div>
            <?php unset($_SESSION['mensagem']); unset($_SESSION['mensagem_tipo']); ?>
        <?php endif; ?>

    <form action="processa_login.php" method="POST">
        <div class="mb-3">
            <label for="tipo_usuario" class="form-label">Tipo de Usuário:</label>
            <select name="tipo_usuario" id="tipo_usuario" class="form-select">
                <option value="">Selecione</option>
                <option value="paciente">Paciente</option>
                <option value="medico">Médico</option>
            </select>
        </div>
        <div class="mb-3" id="campo_cpf" style="display:none;">
            <label for="cpf" class="form-label">CPF:</label>
            <input type="text" name="cpf" id="cpf" class="form-control" placeholder="Digite seu CPF">
        </div>
        <div class="mb-3" id="campo_crm" style="display:none;">
            <label for="crm" class="form-label">CRM:</label>
            <input type="text" name="crm" id="crm" class="form-control" placeholder="Digite seu CRM">
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha:</label>
            <input type="password" name="senha" id="senha" class="form-control" placeholder="Digite sua senha">
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
        <p class="text-center mt-3">
            Ainda não tem uma conta? <a href="cadastro.php">Cadastre-se</a>
        </p>
    </div>
</div>

<!-- Script para alternar CPF/CRM e fade-out -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const tipoUsuario = document.getElementById("tipo_usuario");
    const campoCpf = document.getElementById("campo_cpf");
    const campoCrm = document.getElementById("campo_crm");

    tipoUsuario.addEventListener("change", () => {
        if (tipoUsuario.value === "paciente") {
            campoCpf.style.display = "block";
            campoCrm.style.display = "none";
        } else if (tipoUsuario.value === "medico") {
            campoCrm.style.display = "block";
            campoCpf.style.display = "none";
        } else {
            campoCpf.style.display = "none";
            campoCrm.style.display = "none";
        }
    });

    // Oculta a mensagem após 5 segundos
    setTimeout(() => {
        const msg = document.querySelector('.mensagem');
        if (msg) msg.style.display = 'none';
    }, 5000);
});
</script>

<?php include_once("templates/footer.php"); ?>
