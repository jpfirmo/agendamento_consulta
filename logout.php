<?php

session_start();

// Remove todas as variáveis de sessão
session_unset();

// Destroi a sessão atual
session_destroy();

// Redireciona para a página inicial ou de login
header("Location: index.php");
exit;
?>
