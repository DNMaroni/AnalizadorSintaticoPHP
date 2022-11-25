<?php

    if ($_SERVER['REQUEST_URI'] !== '/') {
        header('Location: /error/404.html');
        exit;
    }

    if (isset($_POST['nome'])) {
        include '../salvar.php';
        exit;
    }
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Teste</title>
        <style> 
            textarea {
                width: 100%;
                height: 150px;
                padding: 12px 20px;
                box-sizing: border-box;
                border: 2px solid #ccc;
                border-radius: 4px;
                background-color: #f8f8f8;
                font-size: 16px;
                resize: none;
            }
        </style>
    </head>
    <body>

        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 50rem;">
            <h1>Titulo</h1>

            <form action="/salvar.php" method="post">
                <label for="code">Código</label>
                <textarea type="text" name="code" id="code"></textarea>

                <input type="submit" value="Enviar">

                <?php
                    include 'core.php';
    if (isset($_SESSION['retorno'])) {
        echo $_SESSION['retorno'];
        unset($_SESSION['retorno']);
    }
    ?>
            </form>
        </div>

    </body>
</html>