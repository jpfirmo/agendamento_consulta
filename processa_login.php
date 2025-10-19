<?php
session_start();
include_once("testes/conexao_teste.php"); // conexão com o banco

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tipo_usuario = strtolower(trim($_POST["tipo_usuario"]));
    $login = trim($_POST["login"]);
    $senha = trim($_POST["senha"]);

    // Validação básica
    if (empty($tipo_usuario) || empty($login) || empty($senha)) {
        $_SESSION["mensagem"] = "Preencha todos os campos.";
        header("Location: login.php");
        exit;
    }

    // Seleciona a tabela de acordo com o tipo de usuário
    if ($tipo_usuario === "paciente") {
        // Remove pontos e traços do CPF
        $login = str_replace(['.', '-'], '', $login);
        $stmt = $conn->prepare("SELECT * FROM paciente WHERE cpf = :login");
    } elseif ($tipo_usuario === "medico") {
        $login = trim($login); // CRM geralmente sem máscara
        $stmt = $conn->prepare("SELECT * FROM medico WHERE crm = :login");
    } else {
        $_SESSION["mensagem"] = "Tipo de usuário inválido!";
        header("Location: login.php");
        exit;
    }

    $stmt->bindParam(":login", $login);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica a senha
        if (password_verify($senha, $usuario["senha"])) {
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nome"] = $usuario["nome"];
            $_SESSION["tipo_usuario"] = $tipo_usuario;
            $_SESSION["mensagem"] = "Login realizado com sucesso!";

            // Redireciona conforme o tipo de usuário
            if ($tipo_usuario === "paciente") {
                header("Location: home_paciente.php");
            } else { // medico
                header("Location: home_medico.php");
            }
            exit;

        } else {
            $_SESSION["mensagem"] = "Senha incorreta!";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION["mensagem"] = "Usuário não encontrado! Verifique se o cadastro foi feito.";
        header("Location: login.php");
        exit;
    }
}
?>
