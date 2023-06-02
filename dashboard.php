<?php
require_once('utils.php');

session_start();

// Obtem credencias do ficheiro
$credentials = analyze_credentials('../credentials.txt');

// Verifica se algum utilizador já se encontra logado
if (!isset($_SESSION['username']) or !is_user($_SESSION['username'], $credentials)) {
    header("Location: index.php");
}

// Obter crecenciais referentes ao utilizador logado
$user = get_user($_SESSION['username'], $credentials);

// Obter lista de sensores/atuadores
$files = get_dirs_list('api/files/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <!-- Import Robot Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <!-- Topbar -->
    <nav id="topbar" class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a id="sidebarCollapse" class="btn btn-secondary">
                <i class="fas fa-bars"></i>
            </a>
            <h4 class="text-white">Dashboard</h4>
        </div>
    </nav>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="d-flex flex-column">
            <div class="sidebar-header">
                <h4>Parque de Estacionamento</h4>
            </div>

            <ul class="flex-column mb-auto components">
                <li>
                    <a href="dashboard.php" class="active">Dashboard</a>
                </li>
            </ul>
            <ul class="flex-column components">
                <li>
                    <a href="logout.php">Log out</a>
                </li>
            </ul>
        </nav>
        <!-- Page Content -->
        <div id="content">
            <div class="container pt-2">
                <div class="row">
                    <?php
                    foreach ($files as $file) {
                        // Obtem dados referentes a cada sensor/atuador
                        $nome = file_get_contents('api/files/' . $file . '/nome.txt');
                        $valor = file_get_contents('api/files/' . $file . '/valor.txt');
                        $hora = file_get_contents('api/files/' . $file . '/hora.txt');
                        $info = file_get_contents('api/files/' . $file . '/info.txt');

                        // Configura automaticamente o nome da imagem para cada sensor/atuador
                        $primeiro_nome = explode(' ', $nome)[0];
                        $switch = '';
                        if ($info == 'atuador') {
                            $switch = '<div class="form-check form-switch">
                                            <label for="' . $file . '-switch">Off/On</label>
                                            <input id="' . $file . '-switch" name="' . $file . '" type="checkbox" role="switch" class="form-check-input" ' . ($valor == 'On' ? 'checked' : '') . '>
                                        </div>';
                            $imagem = strtolower($primeiro_nome . '_' . $valor);
                        } else {
                            $imagem = strtolower($primeiro_nome);
                        }

                        echo '<div class="col-sm-4">
                                <div class="card text-center mb-3">
                                    <div class="card-header fw-bold sensor">' . $nome . '</div>
                                    <img id="' . $file . '-image" src="images/' . $imagem . '.png" alt="' . $nome . '" class="card-image-top">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3"><span id="' . $file . '">' . $valor . '</span> ' . get_sensor_symbol($nome) . '</h5>
                                        <small><b>Última atualização:</b> <span id="' . $file . '-hora">' . $hora . '</span></small>
                                        ' . ($user[2] == 'admin' ? "<a href=\"historico.php?nome=$file\" class=\"text-primary\">Histórico</a>" : '') .
                            $switch . '
                                    </div>
                                </div>
                            </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Abrir/Fechar sidebar
        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            let sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        });

        function updateFromAPI(id, nome, isAtuador = false) {
            // console.log("Updated " + id + "");

            // Obtem a data e hora da ultima atualização do sensor/atuador na API
            let spanHora = document.getElementById(id + "-hora");
            const getHora = async () => {
                const response = await fetch('/api/api.php?nome=' + id + "&tipo=hora");
                // check if the response is ok
                if (response.ok) {
                    const textValue = await response.text();
                    spanHora.innerHTML = textValue; // Atualiza a hora na pagina
                }
            }
            getHora().catch(error => console.error(error));

            // Obtem o valor do sensor/atuador
            let spanValor = document.getElementById(id)
            const getValor = async () => {
                const response = await fetch('/api/api.php?nome=' + id);
                // check if the response is ok
                if (response.ok) {
                    const textValue = await response.text();
                    spanValor.innerHTML = textValue; // Atualiza o estado na pagina

                    // Atualiza o botão switch caso seja um atuador
                    let inputSwitch = document.getElementById(id + '-switch');
                    if (isAtuador) {
                        if (textValue === "On") {
                            inputSwitch.checked = true;
                        } else {
                            inputSwitch.checked = false;
                        }
                    }

                    // Atualiza a imagem
                    // Cria o caminha para obter a imagem
                    let imageName = '';
                    let primeiroNome = nome.split(" ")[0];
                    if (isAtuador && textValue != null) { // Configura o nome caso seja um atuador
                        imageName = (primeiroNome + "_" + textValue).toLowerCase();
                    } else { // Ou caso seja um sensor
                        imageName = primeiroNome.toLowerCase();
                    }
                    // Obtem a imagem correspondente ao sensor/atuador
                    const image = document.getElementById(id + "-image");
                    fetch("/images/" + imageName + ".png")
                        .then((response) => response.blob())
                        .then((blob) => {
                            const objectURL = URL.createObjectURL(blob);
                            image.src = objectURL;
                        });
                }
            }
            getValor().catch(error => console.error(error));
        }

        // Define um evento de click para todos os botões switch
        document.querySelectorAll('.form-check-input').forEach(element => {
            element.addEventListener('click', async function() {
                let value = 'Off';

                // Se o switch for ativado define o valor a enviar como ligado
                if (this.checked) {
                    value = 'On';
                }
                // Envia o POST para atualizar o estado do atuador através da API
                const sendPost = async () => {
                    const response = await fetch('api/api.php', {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: "nome=" + this.name + "&valor=" + value,
                    });

                    if (response.ok) {
                        const textValue = await response.text();
                        const valores = textValue.split(';');

                        updateFromAPI(valores[0], valores[1], valores[2]);
                    } else {
                        // Caso ocorra algum erro retorna o botão switch ao estado original
                        this.checked = false;
                    }
                }
                sendPost().catch(error => console.error(error));
            });
        });

        <?php
        foreach ($files as $file) {
            $nome = file_get_contents('api/files/' . $file . '/nome.txt');
            $info = file_get_contents('api/files/' . $file . '/info.txt');

            // Cria um setInterval para cada sensor/atuador
            echo "setInterval(updateFromAPI, 5000, '" . $file . "', '" . $nome . "', " . ($info == 'atuador' ? 'true' : 'false') . ");\n";
        }
        ?>
    </script>
    <!--Popper.JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <!--Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>