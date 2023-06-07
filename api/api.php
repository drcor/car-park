<?php
include_once('../utils.php');

header('Content-Type: text/html; charset=UTF-8');

$files = get_dirs_list('files/');

// Método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        isset($_POST['nome'])
        and in_array($_POST['nome'], $files)                        // Verifica se o nome do sensor/atuador existe
        and (isset($_POST['valor']) and !empty($_POST['valor']))  // Verifica se o valor e a hora estão atribuidos e não são nulos
    ) {

        $timestamp = time(); // Obter o timestamp atual
        $date = date('Y-m-d H:i:s', $timestamp);

        file_put_contents('files/' . $_POST['nome'] . '/valor.txt', $_POST['valor']);
        file_put_contents('files/' . $_POST['nome'] . '/hora.txt', $date);
        file_put_contents('files/' . $_POST['nome'] . '/log.txt', $date . ';' . $_POST['valor'] . PHP_EOL, FILE_APPEND);

        $nome = file_get_contents('files/' . $_POST['nome'] . '/nome.txt');
        $info = file_get_contents('files/' . $_POST['nome'] . '/info.txt');

        echo $_POST['nome'] . ';' . $nome . ';' . $info;
    } else {
        echo "Faltam parametros no POST";
        http_response_code(400);
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {   // Método GET
    if (isset($_GET['nome']) and in_array($_GET['nome'], $files)) {
        // Obter específicamente a data da última atualização
        if (isset($_GET['tipo']) and $_GET['tipo'] == "hora") {
            echo file_get_contents('files/' . $_GET['nome'] . '/hora.txt');
        } elseif (isset($_GET['tipo']) and $_GET['tipo'] == "log") { // Obter os logs do sensor/atuador
            echo file_get_contents('files/' . $_GET['nome'] . '/log.txt');
        } else {    // Senão retorna normalmente o valor do sensor/atuador
            echo file_get_contents('files/' . $_GET['nome'] . '/valor.txt');
        }
    } else if (isset($_GET['nome']) and $_GET['nome'] == "webcam") {    // Obter imagem de webcam
        if (file_exists('images/webcam.jpg')) {
            echo file_get_contents('images/webcam.jpg');
        } else {
            http_response_code(404);
            die('Webcam image not available at the moment.');
        }
    } else {
        echo "Faltam parametros no GET";
        http_response_code(400);
    }
} else {    // Qualquer outro método
    echo 'Metodo nao permitido';
    http_response_code(403);
}
