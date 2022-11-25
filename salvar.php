<?php

include 'core.php';

/* file_put_contents('../arquivo.txt', json_encode($_POST)); */


if (isset($_POST['code']) and !empty($_POST['code'])) {
    echo file_put_contents('code.txt', $_POST['code']);
    shell_exec('./lexico code.txt');

    include 'sintatico.php';
}

header('Refresh:1; url=/');

echo '<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 50rem;"><h1>Processando...</h1></div>';
