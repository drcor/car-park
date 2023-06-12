<?php
include_once('../utils.php');

header('Content-Type: text/html; charset=UTF-8');

date_default_timezone_set("Europe/Lisbon");

$files = get_dirs_list('files/');

// METODO POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        isset($_POST['nome'])
        and in_array($_POST['nome'], $files)                        // Verifica se o nome do sensor/atuador existe
        and (isset($_POST['valor']) and !empty($_POST['valor']))    // Verifica se o valor e a hora estão atribuidos e não são nulos
    ) {

        $timestamp = time(); // Obter o timestamp atual
        $date = date('Y-m-d H:i:s', $timestamp);

        // Guarda os dados nos ficheiros da API
        file_put_contents('files/' . $_POST['nome'] . '/valor.txt', $_POST['valor']);
        file_put_contents('files/' . $_POST['nome'] . '/hora.txt', $date);
        file_put_contents('files/' . $_POST['nome'] . '/log.txt', $date . ';' . $_POST['valor'] . "\r\n", FILE_APPEND);

        // Retorna o nome e o tipo de sensor/atuador
        $nome = file_get_contents('files/' . $_POST['nome'] . '/nome.txt');
        $info = file_get_contents('files/' . $_POST['nome'] . '/info.txt');
        echo $_POST['nome'] . ';' . $nome . ';' . $info;
    } else {
        echo "Faltam parametros no POST";
        http_response_code(400);
    }
}

// METODO GET
else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // SENSORES/ATUADORES GERAIS
    if (isset($_GET['options'])) {   // Devolve a lista de sensores e atuadores
        $files = get_dirs_list('files/');
        foreach ($files as $file) {
            echo $file . "\r\n";
        }
    } else if (isset($_GET['nome']) and in_array($_GET['nome'], $files)) {
        // Devolve específicamente a data da última atualização
        if (isset($_GET['tipo']) and $_GET['tipo'] == "hora") {
            echo file_get_contents('files/' . $_GET['nome'] . '/hora.txt'); 
        }

        // Devolve a descrião do sensor/atuador
        elseif (isset($_GET['tipo']) and $_GET['tipo'] == "desc") {
            echo file_get_contents('files/' . $_GET['nome'] . '/nome.txt');
        }

        // Devolve os logs do sensor/atuador
        elseif (isset($_GET['tipo']) and $_GET['tipo'] == "log") {
            echo file_get_contents('files/' . $_GET['nome'] . '/log.txt');
        }

        // Devolve os a info do sensor/atuador
        elseif (isset($_GET['tipo']) and $_GET['tipo'] == "info") {
            echo file_get_contents('files/' . $_GET['nome'] . '/info.txt');
        }

        // Devolve o simbolo correspondente ao sensor/atuador
        elseif (isset($_GET['tipo']) and $_GET['tipo'] == "simbolo") {
            echo get_sensor_symbol($_GET['nome']);
        }

        // Senão retorna normalmente o valor do sensor/atuador
        else {
            echo file_get_contents('files/' . $_GET['nome'] . '/valor.txt');
        }
    }
    
    // WEBCAM
    else if (isset($_GET['nome']) and $_GET['nome'] == "webcam") {    // Obter dados de webcam
        if (file_exists('images/webcam.jpg')) {
            if (isset($_GET['tipo']) and $_GET['tipo'] == "hora") {
                echo date("Y/m/d H:i:s", filectime("images/webcam.jpg"));   // Devolve a data e hora da webcam
            } else {
                // Envia a imagem solicitada
                header('Content-Type: application/image');
                header('Content-Disposition: attachment; filename="webcam.jpg"');
                echo file_get_contents('images/webcam.jpg');                // Senao devolve a imagem da webcam
            }
        } else {
            http_response_code(404);
            die('Webcam image not available at the moment.');
        }
    }

    // HISTORICO DE WEBCAM
    else if (isset($_GET['nome']) and $_GET['nome'] == "oldercam") {
        $older_cam_list = get_files_list('images/older/');

        if (count($older_cam_list) > 0) {
            if (isset($_GET['img']) and in_array($_GET['img'], $older_cam_list)) {
                // Envia a imagem solicitada
                header('Content-Type: application/image');
                header('Content-Disposition: attachment; filename="' . $_GET['img'] . '"');
                echo file_get_contents("images/older/".$_GET['img']);
            } else {
                foreach ($older_cam_list as $older_image) {
                    echo $older_image. "\r\n";
                }
            }
        }
        
        // Caso não exista historico para retornar
        else {
            http_response_code(404);
        }
    }
    
    // GET INCORRETO
    else {
        print_r($_GET);
        echo "Faltam parametros no GET";
        http_response_code(400);
    }
} else {    // Qualquer outro método
    echo 'Metodo nao permitido';
    http_response_code(403);
}
