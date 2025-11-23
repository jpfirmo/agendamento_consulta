<?php
session_start();
?>

<?php include_once("templates/header_home_principal.php"); ?>


<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="container mt-5">
    <div class="card shadow-lg p-4 rounded-4" style="max-width: 400px; margin: auto;">
        <h2 class="text-center mb-4">Login</h2>

        <!-- Mensagem -->
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="mensagem <?= htmlspecialchars($_SESSION['mensagem_tipo']) ?>">
                <?= htmlspecialchars($_SESSION['mensagem']) ?>
            </div>
            <?php unset($_SESSION['mensagem'], $_SESSION['mensagem_tipo']); ?>
        <?php endif; ?>

        <form action="processa_login.php" method="POST">

            <!-- Tipo usuário -->
            <div class="mb-3">
                <label for="tipo_usuario" class="form-label fw-bold">Tipo de Usuário:</label>
                <select name="tipo_usuario" id="tipo_usuario" class="form-select">
                    <option value="">Selecione...</option>
                    <option value="paciente">Paciente</option>
                    <option value="medico">Médico</option>
                </select>
            </div>

            <!-- CPF -->
            <div class="mb-3" id="campo_cpf" style="display:none;">
                <label for="cpf" class="form-label fw-bold">CPF:</label>
                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Digite seu CPF">
            </div>

            <!-- CRM -->
            <div class="mb-3" id="campo_crm" style="display:none;">
                <label for="crm" class="form-label fw-bold">CRM:</label>
                <input type="text" class="form-control" id="crm" name="crm" placeholder="Digite seu CRM">
            </div>

            <!-- Senha com ícone igual ao cadastro.php -->
            <div class="mb-3">
                <label for="senha" class="form-label fw-bold">Senha:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha">
                    <button type="button" class="btn btn-outline-secondary" id="toggleSenha">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>

        <div class="text-center mt-3">
            <p>Ainda não tem conta?</p>
            <a href="cadastro.php" class="text-decoration-none fw-bold">Cadastre-se</a>
        </div>
    </div>
</div>

<script>
// Alternar campos CPF/CRM
document.getElementById("tipo_usuario").addEventListener("change", function() {
    const tipo = this.value;
    document.getElementById("campo_cpf").style.display = tipo === "paciente" ? "block" : "none";
    document.getElementById("campo_crm").style.display = tipo === "medico" ? "block" : "none";
});

// Mostrar/ocultar senha — MESMO PADRÃO DO CADASTRO
document.getElementById("toggleSenha").addEventListener("click", function() {
    const input = document.getElementById("senha");
    const icon = this.querySelector("i");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    }
});

// Fade-out da mensagem
setTimeout(() => {
    const msg = document.querySelector('.mensagem');
    if (msg) msg.style.opacity = "0";
}, 5000);
</script>

<?php include_once("templates/footer.php"); ?>
