<?php
require_once('utils.php');

session_start();

// Obtem credencias do ficheiro
$credentials = analyze_credentials('../credentials.txt');

// Se o utilizador não estiver logado
if (!isset($_SESSION['username']) or !is_user($_SESSION['username'], $credentials)) {
    header("Location: index.php");
}

// Verifica se o utilizador tem permissões para aceder à página
$user = get_user($_SESSION['username'], $credentials);
if ($user[2] != 'admin') {
    header("Location: dashboard.php");
}

$files = get_dirs_list('api/files/');

// Verifica se foi passado o parâmetro correto
if (!isset($_GET['nome'])) {
    header('Refresh: 3; url=/dashboard.php');
    die('Pedido incompleto!');
}

// Verifica se sensor/atuador inserido existe
if (in_array($_GET['nome'], $files)) {
    // Obtem dados correspondentes ao sensor/atuador
    $nome = file_get_contents('api/files/' . $_GET['nome'] . '/nome.txt');
    $valor = file_get_contents('api/files/' . $_GET['nome'] . '/valor.txt');
    $hora = file_get_contents('api/files/' . $_GET['nome'] . '/hora.txt');
    $logs = parse_logs('api/files/' . $_GET['nome'] . '/log.txt');
} elseif ($_GET['nome'] == 'webcam') {    // Se for o histórico sobre as imagens
    $nome = "Webcam";
    $image_files = get_files_list('api/images/older/'); // Obtem a lista do historico de webcam
    arsort($image_files);                             // e ordena descendentemente

} else {    // Caso não tenha sido passado o nome de um sensor/atuador ou webcam
    header('Refresh: 3; url=/dashboard.php');
    die('Sensor não disponível!');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <title>Histórico - <?php echo $nome ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <!-- Font Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <!--- Topbar -->
    <nav id="topbar" class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a id="sidebarCollapse" class="btn btn-secondary">
                <i class="fas fa-bars"></i>
            </a>
            <h4 class="text-white">Histórico</h4>
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
                    <a href="dashboard.php">Dashboard</a>
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
            <!-- Sensor/Actuator/Webcam --->
            <div class="container pt-4">
                <h2><?php echo $nome; ?></h2>
                <?php
                if ($_GET['nome'] != 'webcam') {
                ?>
                    <div class="card mt-4 mb-4"  id="logs-container">
                        <div class="card-header">
                            <b>Tabela de Logs</b>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="width:100%">
                                <canvas id="chart"></canvas>
                            </div>
                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th scope="col">Data de Atualização</th>
                                        <th scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="historico-logs">
                                    <!-- Aqui é colocado o historico de logs -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <div class="row mt-4" id="historico-imagens">
                        <!-- Aqui é colocado o historico de imagens -->
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <script>
        // Abrir/Fechar sidebar
        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            let sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        });

        <?php
        if ($_GET['nome'] == "webcam") {
        ?>
            // Obtem a lista de imagens do historico
            const getOlderImages = async () => {
                const response = await fetch('/api/api.php?nome=oldercam');
                // check if the response is ok
                if (response.ok) {
                    const textValue = await response.text();

                    // Separa o nome das imagens em strings diferentes
                    let images = textValue.trim().split("\r\n").sort();

                    let historico = document.getElementById("historico-imagens");
                    images.forEach(img => {
                        // Obtem a hora da imagem com base no timestamp presente no nome
                        let date = new Date(parseInt(img.split('_')[1]) * 1000);
                        let hora = date.toLocaleString();

                        // Adiciona um novo elemento para colocar a imagem
                        historico.innerHTML += `<div class="col-3""><img id="${img}" src="" alt="" class="img-fluid"><span>${hora}</span></div>`;

                        // Obtem a imagem
                        const image = document.getElementById(img);
                        fetch("/api/api.php?nome=oldercam&img=" + img)
                            .then((response) => response.blob())
                            .then((blob) => {
                                const objectURL = URL.createObjectURL(blob);
                                document.getElementById(img).src = objectURL;
                            });
                    });
                }
            }
            getOlderImages().catch(error => console.error(error));
        <?php
        } else {
        ?>
            // Obtem e processa os dados dos logs dos sensore e atuadores
            const getLogs = async () => {
                const response = await fetch("/api/api.php?nome=<?php echo $_GET['nome']; ?>&tipo=log");
                // check if the response is ok
                if (response.ok) {
                    const textValue = await response.text();

                    // Se não houver nenhum log para mostar
                    if (textValue !== "") {
                        let lines = textValue.trim().split("\r\n");
                        
                        let xValues = new Array(),
                        yValues = new Array();
                        
                        
                        // Adiciona os logs á tabela e processa os dados para o grafico
                        let historico = document.getElementById('historico-logs');
                        lines.forEach(line => {
                            let [date, value] = line.split(";");

                            historico.innerHTML += `<tr><td>${date}</td><td>${value}</td></tr>`;

                            xValues.push(date);

                            // Converte os valores dos atuadores para 1 ou 0
                            if (value === "On") {
                                yValues.push(1);
                            } else if (value === "Off") {
                                yValues.push(0);
                            } else {
                                yValues.push(value);
                            }
                        });

                        // Mostra apenas os ultimos 20 elementos dos logs
                        new Chart("chart", {
                            type: "line",
                            data: {
                                labels: xValues.slice(-20),
                                datasets: [{
                                    fill: true,
                                    lineTension: 0,
                                    backgroundColor: "rgba(173, 219, 255, 0.6)",
                                    borderColor: "rgba(0,0,255,0.1)",
                                    data: yValues.slice(-20)
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                legend: {
                                    display: false
                                },
                                scales: {
                                    y: {
                                        suggestedMin: 0,
                                        suggestedMax: 100
                                    }
                                }
                            }
                        });
                    } else {    // Caso não exista nenhum log para mostrar
                        let logsContainer = document.getElementById('logs-container');
                        logsContainer.innerHTML = '<div class="card-header"><b>Sem logs para mostrar de momento.</b></div>';
                    }
                }
            }
            getLogs().catch(error => console.error(error));
        <?php
        }
        ?>
    </script>
    <!-- Popper.JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <!--Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
