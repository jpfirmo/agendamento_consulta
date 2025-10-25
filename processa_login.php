<?php
session_start();
include_once("testes/conexao_teste.php"); // conexão com o banco

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Captura o tipo de usuário (paciente ou médico)
    $tipo_usuario = strtolower(trim($_POST["tipo_usuario"] ?? ''));

    // Captura a senha (campo comum)
    $senha = trim($_POST["senha"] ?? '');

    // Define o login de acordo com o tipo de usuário
    if ($tipo_usuario === "paciente") {
        // Pega o CPF informado e remove pontos e traços
        $login = str_replace(['.', '-'], '', $_POST["cpf"] ?? '');
        $stmt = $conn->prepare("SELECT * FROM paciente WHERE cpf = :login");

    } elseif ($tipo_usuario === "medico") {
        // Pega o CRM informado (geralmente sem máscara)
        $login = trim($_POST["crm"] ?? '');
        $stmt = $conn->prepare("SELECT * FROM medico WHERE crm = :login");

    } else {
        // Tipo de usuário inválido (proteção extra)
        $_SESSION["mensagem"] = "Tipo de usuário inválido!";
        $_SESSION["mensagem_tipo"] = "erro";
        header("Location: login.php");
        exit;
    }

    // Verifica se algum campo obrigatório está vazio
    if (empty($tipo_usuario) || empty($login) || empty($senha)) {
        $_SESSION["mensagem"] = "Preencha todos os campos.";
        $_SESSION["mensagem_tipo"] = "erro";
        header("Location: login.php");
        exit;
    }

    // Executa a consulta
    $stmt->bindParam(":login", $login);
    $stmt->execute();

    // Verifica se o usuário existe
    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica a senha com hash (password_hash/password_verify)
        if (password_verify($senha, $usuario["senha"])) {
            // Armazena dados do usuário na sessão
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nome"] = $usuario["nome"];
            $_SESSION["tipo_usuario"] = $tipo_usuario;

            // Mensagem de sucesso
            $_SESSION["mensagem"] = "Login realizado com sucesso!";
            $_SESSION["mensagem_tipo"] = "sucesso";

            // Redireciona conforme o tipo de usuário
            if ($tipo_usuario === "paciente") {
                header("Location: home_paciente.php");
            } else {
                header("Location: home_medico.php");
            }
            exit;

        } else {
            // Senha incorreta
            $_SESSION["mensagem"] = "Senha incorreta!";
            $_SESSION["mensagem_tipo"] = "erro";
            header("Location: login.php");
            exit;
        }

    } else {
        // Usuário não encontrado no banco
        $_SESSION["mensagem"] = "Usuário não encontrado! Verifique se o cadastro foi feito.";
        $_SESSION["mensagem_tipo"] = "erro";
        header("Location: login.php");
        exit;
    }
}
?>
