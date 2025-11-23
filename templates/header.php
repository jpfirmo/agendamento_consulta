<?php

include_once("config/url.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento_consulta</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css" crossorigin="anonymous">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" crossorigin="anonymous">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= $BASE_URL ?>css/style.css">
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary d-flex justify-content-between align-items-center px-4">

        <!-- LOGO + TEXTO -->
        <div class="d-flex align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="<?= $BASE_URL ?>index.php">
                <img src="<?= $BASE_URL ?>img/logo.svg" alt="Agenda de Consultas" style="height: 40px;">
            </a>

            <span class="navbar-text text-white font-weight-bold ml-2" style="font-size: 1.2rem;">
                Agenda de Consulta
            </span>
        </div>

        <!-- BOTÃO SAIR -->
        <div>
            <a href="<?= $BASE_URL ?>logout.php" class="btn btn-danger px-4">
                Sair
            </a>
        </div>

    </nav>
</header>
