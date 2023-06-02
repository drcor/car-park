<?php
// Retorna um array com o nome de todas as pastas no caminho passado por parâmetro
function get_dirs_list($path = '.') {
    $dirs = array();

    foreach (new DirectoryIterator($path) as $file) {
        if ($file->isDir() && !$file->isDot()) {
            $dirs[] = $file->getFilename();
        }
    }

    return $dirs;
}

// Retorna um array com o nome de todos os ficheiros no caminho passado por parâmetro
function get_files_list($path = '.') {
    $files = array();

    foreach (new DirectoryIterator($path) as $file) {
        if ($file->isFile() && !$file->isDot()) {
            $files[] = $file->getFilename();
        }
    }

    

    return $files;
}

// Retorna o símbolo de unidade associado a cada sensor
function get_sensor_symbol($sensor_name) {
    switch ($sensor_name) {
        case 'Temperatura':
            return 'ºC';
        case 'Humidade':
            return '%';
        case 'CO2':
            return 'ppm';
        default:
            return '';
    }
}

// Retorna um array bi-dimensional com os dados de todos os utilizadores que estão no ficheiro passado por parâmetro
function analyze_credentials($file_name) {
    $file = fopen($file_name, 'r');
    $credentials = array();

    while (!feof($file)) {                      // enquanto não atingir o fim do ficheiro
        $line = trim(fgets($file));             // remove os espaços a mais em cada linha
        $credentials[] = explode(':', $line);   // divide a linha através dos divisores ':'
    }
    fclose($file);

    return $credentials;
}

// Retorna um array bi-dimensional com todos os logs do ficheiro passado por parâmetro
function parse_logs($file_name) {
    $file = fopen($file_name, 'r');
    $logs = array();

    while (!feof($file)) {              // enquanto não atingir o fim do ficheiro
        $line = trim(fgets($file));     // remove os espaços a mais em cada linha
        $logs[] = explode(';', $line);  // divide a linha através dos divisores ';'
    }
    fclose($file);

    $result = array_map('array_filter', $logs); // Remove subarrays que estejam vazios
    $result = array_filter($result);            // Remove elements que estejam vazios

    return $result;
}

// Verifica se o nome do utilizador existe nas credênciais
function is_user($username, $credentials) {
    foreach ($credentials as $row) {
        if ($username == $row[0]) {
            return true;
        }
    }

    return false;
}

// Retorna todos os dados do utilizador associados ao nome passado por parâmetro
function get_user($username, $credentials) {
    $user = array();
    foreach ($credentials as $row) {    // Para cada linha do ficheiro de credenciais
        if ($username == $row[0]) {     // Verifica se são as credencias do utilizador
            $user = $row;
            break;
        }
    }

    return $user;
}
