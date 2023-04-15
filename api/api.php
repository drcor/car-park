<?php
include_once('../utils.php');

header('Content-Type: text/html; charset=UTF-8');

$files = get_dirs('files/');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['nome'])
		and in_array($_POST['nome'], $files)						// Verifica se o nome do sensor/atuador existe
		and ( isset($_POST['valor']) and !empty($_POST['valor']) )		// Verifica se o valor e a hora estão atribuidos e não são nulos
		and ( isset($_POST['hora']) and !empty($_POST['hora']) )) {
		
		file_put_contents('files/' . $_POST['nome'] . '/valor.txt', $_POST['valor']);
		file_put_contents('files/' . $_POST['nome'] . '/hora.txt', $_POST['hora']);
		file_put_contents('files/' . $_POST['nome'] . '/log.txt', $_POST['hora'] . ';' . $_POST['valor'].PHP_EOL, FILE_APPEND);
	} else {
		echo "Faltam parametros no POST";
		http_response_code(400);
	}
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (isset($_GET['nome']) and in_array($_GET['nome'], $options)) {
		echo file_get_contents('files/' . $_GET['nome'] . '/valor.txt');
	} else {
		echo "Faltam parametros no GET";
		http_response_code(400);
	}
} else {
	echo 'Metodo nao permitido';
	http_response_code(403);
}
