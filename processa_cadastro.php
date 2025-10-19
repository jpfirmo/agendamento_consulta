<?php
session_start();
include_once("testes/conexao_teste.php"); // conexão com o banco

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_usuario = $_POST['tipo_usuario'];

    // Validação básica
    if (empty($tipo_usuario)) {
        $_SESSION['erro'] = "Selecione o tipo de usuário.";
        header("Location: cadastro.php");
        exit;
    }

    // --- PACIENTE ---
    if ($tipo_usuario === 'paciente') {
        $nome = trim($_POST['nome']);
        $cpf = str_replace(['.', '-'], '', trim($_POST['cpf'])); // remove pontos e traço
        $telefone = trim($_POST['telefone']);
        $senha = trim($_POST['senha']);

        if (empty($nome) || empty($cpf) || empty($telefone) || empty($senha)) {
            $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
            header("Location: cadastro.php");
            exit;
        }

        // Criptografar a senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare("INSERT INTO paciente (nome, cpf, telefone, senha) VALUES (:nome, :cpf, :telefone, :senha)");
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":cpf", $cpf);
            $stmt->bindParam(":telefone", $telefone);
            $stmt->bindParam(":senha", $senhaHash);
            $stmt->execute();

            // Cria sessão do usuário
            $_SESSION['usuario_id'] = $conn->lastInsertId();
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['tipo_usuario'] = 'paciente';
            $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";

            // Redireciona para a home do paciente
            header("Location: home_paciente.php");
            exit;

        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao cadastrar paciente: " . $e->getMessage();
            header("Location: cadastro.php");
            exit;
        }
    }

    // --- MÉDICO ---
    elseif ($tipo_usuario === 'medico') {
        $nome = trim($_POST['nome']);
        $crm = trim($_POST['crm']);
        $especialidade = trim($_POST['especialidade']);
        $senha = trim($_POST['senha']);

        if (empty($nome) || empty($crm) || empty($especialidade) || empty($senha)) {
            $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
            header("Location: cadastro.php");
            exit;
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare("INSERT INTO medico (nome, crm, especialidade, senha) VALUES (:nome, :crm, :especialidade, :senha)");
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":crm", $crm);
            $stmt->bindParam(":especialidade", $especialidade);
            $stmt->bindParam(":senha", $senhaHash);
            $stmt->execute();

            // Cria sessão do usuário
            $_SESSION['usuario_id'] = $conn->lastInsertId();
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['tipo_usuario'] = 'medico';
            $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";

            // Redireciona para a home do médico
            header("Location: home_medico.php");
            exit;

        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao cadastrar médico: " . $e->getMessage();
            header("Location: cadastro.php");
            exit;
        }
    }
}
?>
