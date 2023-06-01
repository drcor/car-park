<?php
	include_once('../utils.php');

	header('Content-Type: text/html; charset=UTF-8');
	$options = get_dirs('files/');

    print_r($_FILES);
	
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        print_r($_FILES['imagem']);
        if (isset($_FILES['imagem'])                            // Verifica se foi definida alguma imagem como upload
            and $_FILES['imagem']['error'] == 0                 // Verifica se não ocorreu nenhum erro a fazer o upload
            and is_uploaded_file($_FILES['imagem']['tmp_name']) // É util para verificar se nenhum utilizar mau intencionado enganou o sistema para aceder a ficheiros que não deve
            ) {

            // Obtem o tipo de ficheiro para verificar se é uma imagem válida
            $mimetype = mime_content_type($_FILES['imagem']['tmp_name']);
            $allowedfiles = ['image/png', 'image/jpeg'];

            // Valida se o tipo de ficheiro corresponde a uma imagem
            if (!in_array($mimetype, $allowedfiles)) {
                echo 'Tipo de ficheiro não permitido';
                http_response_code(400);
            }

            // Valida se o tamanho do ficheiro carregado é tem o tamanho máximo de 1000kB (1024000 Bytes)
            if ($_FILES['imagem']['size'] > 1024000) {
                echo 'A imagem excedeu o tamanho máximo permitido';
                http_response_code(400);
            }

            // Move a atual imagem de webcam para a pasta das imagens antigas
            $olderwebcam_name = "./images/older/webcam_".time().".jpg";
            rename("./images/webcam.jpg", $olderwebcam_name);

            // Move a imagem carregada para a pasta api/images/
            move_uploaded_file($_FILES['imagem']['tmp_name'], "./images/webcam.jpg");

        } else {
            echo 'Imagem não encontrada';
            http_response_code(400);
        }
	} else {
		echo 'Método não permitido';
		http_response_code(403);
	}
?>
