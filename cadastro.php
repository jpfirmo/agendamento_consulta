<?php
include_once("templates/header.php");
?>

<div class="container mt-5">
    <div class="card shadow-lg p-4 rounded-4" style="max-width: 450px; margin: auto;">
        <h2 class="text-center mb-4">Cadastro de Usuário</h2>

        <form action="processa_cadastro.php" method="POST" id="formCadastro">
            <!-- Selecionar tipo de usuário -->
            <div class="mb-3">
                <label for="tipo_usuario" class="form-label fw-bold">Cadastrar como:</label>
                <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
                    <option value="">Selecione...</option>
                    <option value="paciente">Paciente</option>
                    <option value="medico">Médico</option>
                </select>
            </div>

            <!-- Campos comuns -->
            <div class="mb-3">
                <label for="nome" class="form-label fw-bold">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite seu nome completo" required>
            </div>

            <!-- Campos específicos do PACIENTE -->
            <div id="camposPaciente" style="display:none;">
                <div class="mb-3">
                    <label for="cpf" class="form-label fw-bold">CPF:</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Digite seu CPF">
                </div>

                <div class="mb-3">
                    <label for="telefone" class="form-label fw-bold">Telefone:</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(xx) xxxxx-xxxx">
                </div>
            </div>

            <!-- Campos específicos do MÉDICO -->
            <div id="camposMedico" style="display:none;">
                <div class="mb-3">
                    <label for="crm" class="form-label fw-bold">CRM:</label>
                    <input type="text" class="form-control" id="crm" name="crm" placeholder="Digite seu CRM">
                </div>

                <div class="mb-3">
                    <label for="especialidade" class="form-label fw-bold">Especialidade:</label>
                    <input type="text" class="form-control" id="especialidade" name="especialidade" placeholder="Ex: Cardiologia, Pediatria...">
                </div>
            </div>

            <!-- Senha -->
            <div class="mb-3">
                <label for="senha" class="form-label fw-bold">Senha:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Crie uma senha" required>
                    <button type="button" class="btn btn-outline-secondary" id="toggleSenha">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100">Cadastrar</button>
        </form>
        <div class="text-center mt-3">
            <p>Já tem conta?</p>
            <a href="login.php" class="text-decoration-none fw-bold">Faça login</a>
        </div>
    </div>
</div>

<!-- Script para alternar os campos dinamicamente -->
<script>
document.getElementById("tipo_usuario").addEventListener("change", function() {
    const tipo = this.value;
    const paciente = document.getElementById("camposPaciente");
    const medico = document.getElementById("camposMedico");

    // Oculta todos inicialmente
    paciente.style.display = "none";
    medico.style.display = "none";

    // Mostra conforme o tipo
    if (tipo === "paciente") {
        paciente.style.display = "block";
    } else if (tipo === "medico") {
        medico.style.display = "block";
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
