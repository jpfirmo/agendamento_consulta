<?php
$host = "localhost";
$dbname = "agenda_teste";
$user = "root";
$password = "28132813";

try {
    // Conexão com charset UTF-8 (evita erro com acentuação)
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);

    // Ativar modo de erros para exceções
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Opcional: modo FETCH_ASSOC para facilitar uso
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // echo "Conexão bem-sucedida!"; // (pode deixar comentado)
    
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit; // garante que o código para aqui se falhar
}
?>