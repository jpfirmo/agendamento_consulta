<?php

session_start();

include_once("testes/conexao_teste.php"); // conexão com o banco

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tipo_usuario = strtolower(trim($_POST['tipo_usuario'] ?? ''));

    // Verifica se o tipo de usuário foi enviado
    if (empty($tipo_usuario)) {
        $_SESSION["mensagem"] = "Selecione o tipo de usuário.";
        $_SESSION["mensagem_tipo"] = "erro";
        header("Location: cadastro.php");
        exit;
    }

    /* CADASTRO DE PACIENTE */
    if ($tipo_usuario === 'paciente') {

        $nome = trim($_POST['nome'] ?? '');
        $cpf = str_replace(['.', '-'], '', trim($_POST['cpf'] ?? ''));
        $telefone = trim($_POST['telefone'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        // Validação de campos vazios
        if (empty($nome) || empty($cpf) || empty($telefone) || empty($senha)) {
            $_SESSION["mensagem"] = "Preencha todos os campos obrigatórios.";
            $_SESSION["mensagem_tipo"] = "erro";
            header("Location: cadastro.php");
            exit;
        }

        // Verifica se CPF já existe
        $check = $conn->prepare("SELECT id FROM paciente WHERE cpf = :cpf");
        $check->bindParam(":cpf", $cpf);
        $check->execute();

        if ($check->rowCount() > 0) {
            $_SESSION["mensagem"] = "CPF já cadastrado!";
            $_SESSION["mensagem_tipo"] = "erro";
            header("Location: cadastro.php");
            exit;
        }

        // Criptografa a senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare("
                INSERT INTO paciente (nome, cpf, telefone, senha)
                VALUES (:nome, :cpf, :telefone, :senha)
            ");
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":cpf", $cpf);
            $stmt->bindParam(":telefone", $telefone);
            $stmt->bindParam(":senha", $senhaHash);
            $stmt->execute();

            // Dados do usuário na sessão
            $_SESSION["usuario_id"] = $conn->lastInsertId();
            $_SESSION["usuario_nome"] = $nome;
            $_SESSION["tipo_usuario"] = 'paciente';

            // Mensagem de sucesso
            $_SESSION["mensagem"] = "Cadastro realizado com sucesso!";
            $_SESSION["mensagem_tipo"] = "sucesso";

            header("Location: home_paciente.php");
            exit;

        } catch (PDOException $e) {
            $_SESSION["mensagem"] = "Erro ao cadastrar paciente.";
            $_SESSION["mensagem_tipo"] = "erro";
            header("Location: cadastro.php");
            exit;
        }
    }

    /* CADASTRO DE MÉDICO */
    elseif ($tipo_usuario === 'medico') {

        $nome = trim($_POST['nome'] ?? '');
        $crm = trim($_POST['crm'] ?? '');
        $especialidade = trim($_POST['especialidade'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        // Validação de campos vazios
        if (empty($nome) || empty($crm) || empty($especialidade) || empty($senha)) {
            $_SESSION["mensagem"] = "Preencha todos os campos obrigatórios.";
            $_SESSION["mensagem_tipo"] = "erro";
            header("Location: cadastro.php");
            exit;
        }

        // Verifica se CRM já existe
        $check = $conn->prepare("SELECT id FROM medico WHERE crm = :crm");
        $check->bindParam(":crm", $crm);
        $check->execute();

        if ($check->rowCount() > 0) {
            $_SESSION["mensagem"] = "CRM já cadastrado!";
            $_SESSION["mensagem_tipo"] = "erro";
            header("Location: cadastro.php");
            exit;
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare("
                INSERT INTO medico (nome, crm, especialidade, senha)
                VALUES (:nome, :crm, :especialidade, :senha)
            ");
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":crm", $crm);
            $stmt->bindParam(":especialidade", $especialidade);
            $stmt->bindParam(":senha", $senhaHash);
            $stmt->execute();

            // Dados do usuário na sessão
            $_SESSION["usuario_id"] = $conn->lastInsertId();
            $_SESSION["usuario_nome"] = $nome;
            $_SESSION["tipo_usuario"] = 'medico';

            // Mensagem de sucesso
            $_SESSION["mensagem"] = "Cadastro realizado com sucesso!";
            $_SESSION["mensagem_tipo"] = "sucesso";

            header("Location: home_medico.php");
            exit;

        } catch (PDOException $e) {
            $_SESSION["mensagem"] = "Erro ao cadastrar médico.";
            $_SESSION["mensagem_tipo"] = "erro";
            header("Location: cadastro.php");
            exit;
        }
    }
}
?>
