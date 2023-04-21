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
	$files = get_dirs('api/files/');
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
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
						if ($info == 'atuador') {
							$imagem = strtolower($primeiro_nome . '_' . $valor);
						} else {
							$imagem = strtolower($primeiro_nome);
						}

						echo '<div class="col-sm-4">
								<div class="card text-center mb-3">
									<div class="card-header fw-bold sensor">' . $nome . '</div>
									<img src="images/' . $imagem . '.png" alt="' . $nome . '" class="card-image-top">
									<div class="card-body">
										<h5 class="card-title mb-3"><span id="' . $file . '">' . $valor . '</span> ' . get_sensor_symbol($nome) . '</h5>
										<small><b>Ultima atualização:</b> ' . $hora . '</small>
										' . ($user[2] == 'admin' ? "<a href=\"historico.php?nome=$file\" class=\"text-primary\">Histórico</a>" : '') . '
									</div>
								</div>
							</div>';
					}
					?>
				</div>
				<div class="card mt-4 mb-4">
					<div class="card-header">
						<b>Tabela de Atuadores</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th scope="col">Nome</th>
									<th scope="col">Estado</th>
									<th scope="col">Data de Atualização</th>
									<th scope="col">On/Off</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($files as $file) {
									// Obtem dados referentes a cada sensor/atuador
									$nome = file_get_contents('api/files/' . $file . '/nome.txt');
									$valor = file_get_contents('api/files/' . $file . '/valor.txt');
									$hora = file_get_contents('api/files/' . $file . '/hora.txt');
									$info = file_get_contents('api/files/' . $file . '/info.txt');

									$switch = '';
									if ($info == 'atuador') {
										$switch = '<div class="form-check form-switch">
													<input id="' . $file . '" type="checkbox" role="switch" class="form-check-input" ' . ($valor == 'On' ? 'checked' : '') . '>
												</div>';
									}
									echo '<tr>
											<td>' . $nome . '</td>
											<td>' . $valor . '</td>
											<td>' . $hora . '</td>
											<td>' . $switch . '</td>
										</tr>';
								}
								?>
							</tbody>
						</table>
					</div>
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
	</script>
	<script src="scripts/dashboard.js"></script>
	<!--Popper.JS -->
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<!--Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>