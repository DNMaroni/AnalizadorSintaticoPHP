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

        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 50vw;">
            <h1 style="text-align: center">Analizador sintático/semântico</h1>

            <div style="height: 2rem; width: 100%; display: inline-block"></div>

            <h3>Operações suportadas:</h3>
            <ul>
                <li>Declarações</li>
                <li>Atribuições</li>
                <li>Expressões</li>
                <li>IFs</li>
            </ul>

            <div style="height: 2rem; width: 100%; display: inline-block"></div>

            <form action="p3rm1z/salvar.php" method="post">
                <label for="code">Inserir Código em <strong>C</strong></label>
                <textarea type="text" name="code" id="code"></textarea>

                <input type="submit" value="Enviar">

                <div style="height: 2rem; width: 100%; display: inline-block"></div>

                <?php
                    include 'p3rm1z/core.php';
    if (isset($_SESSION['retorno'])) {
        echo $_SESSION['retorno'];
        unset($_SESSION['retorno']);
    }
    ?>
            </form>

            <div style="height: 2rem; width: 100%; display: inline-block"></div>
            
            <p style="text-align: center; font-size: 14px;">Desenvolvido por Daniel M (169481@upf.br)</p>
        </div>

    </body>
</html>