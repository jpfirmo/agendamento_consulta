<?php
include_once("templates/header.php");
?>

<div class="container mt-5">
    <div class="card shadow-lg p-4 rounded-4" style="max-width: 400px; margin: auto;">
        <h2 class="text-center mb-4">Login</h2>

        <form action="processa_login.php" method="POST">
            <!-- Selecionar tipo de usuário -->
            <div class="mb-3">
                <label for="tipo_usuario" class="form-label fw-bold">Entrar como:</label>
                <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
                    <option value="">Selecione...</option>
                    <option value="paciente">Paciente</option>
                    <option value="medico">Médico</option>
                </select>
            </div>

            <!-- CPF (para paciente) -->
            <div id="campoPaciente" style="display:none;">
                <div class="mb-3">
                    <label for="cpf" class="form-label fw-bold">CPF:</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Digite seu CPF">
                </div>
            </div>

            <!-- CRM (para médico) -->
            <div id="campoMedico" style="display:none;">
                <div class="mb-3">
                    <label for="crm" class="form-label fw-bold">CRM:</label>
                    <input type="text" class="form-control" id="crm" name="crm" placeholder="Digite seu CRM">
                </div>
            </div>

            <!-- Campo de senha -->
            <div class="mb-3">
                <label for="senha" class="form-label fw-bold">Senha:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required>
                    <button type="button" class="btn btn-outline-secondary" id="toggleSenha">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">Entrar</button>
        </form>

        <div class="text-center">
            <p class="mb-0">Ainda não tem conta?</p>
            <a href="cadastro.php" class="text-decoration-none fw-bold">Cadastre-se aqui</a>
        </div>
    </div>
</div>

<!-- Script para alternar campos conforme o tipo de usuário -->
<script>
document.getElementById("tipo_usuario").addEventListener("change", function() {
    const tipo = this.value;
    const campoPaciente = document.getElementById("campoPaciente");
    const campoMedico = document.getElementById("campoMedico");

    // Esconde todos por padrão
    campoPaciente.style.display = "none";
    campoMedico.style.display = "none";

    // Mostra o campo conforme o tipo selecionado
    if (tipo === "paciente") {
        campoPaciente.style.display = "block";
    } else if (tipo === "medico") {
        campoMedico.style.display = "block";
    }
});

// Mostrar/ocultar senha
document.getElementById("toggleSenha").addEventListener("click", function() {
    const senhaInput = document.getElementById("senha");
    const icone = this.querySelector("i");
    if (senhaInput.type === "password") {
        senhaInput.type = "text";
        icone.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        senhaInput.type = "password";
        icone.classList.replace("fa-eye-slash", "fa-eye");
    }
});
</script>

<?php
include_once("templates/footer.php");
?>
