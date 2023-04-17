<?php
	require_once('utils.php');

	session_start();

	// Obtem credencias do ficheiro
	$credentials = analyze_credentials('../credentials.txt');

	// Se o utilizador não estiver logado
	if (!isset($_SESSION['username']) or !is_user($_SESSION['username'], $credentials)) {
		header("Location: /");
	}

	// Verifica se o utilizador tem permissões para aceder à página
	$user = get_user($_SESSION['username'], $credentials);
	if ($user[2] != 'admin') {
		header("Location: /dashboard.php");
	}

	$files = get_dirs('api/files/');

	// Verifica se sensor/atuador inserido existe
	if (!isset($_GET['nome']) or !in_array($_GET['nome'], $files)) {
		header('Refresh: 3; url=/dashboard.php');
		die('Sensor não disponível!');
	}

	// Obtem dados correspondentes ao sensor/atuador
	$nome = file_get_contents('api/files/' . $_GET['nome'] . '/nome.txt');
	$valor = file_get_contents('api/files/' . $_GET['nome'] . '/valor.txt');
	$hora = file_get_contents('api/files/' . $_GET['nome'] . '/hora.txt');
	$logs = parse_logs('api/files/' . $_GET['nome'] . '/log.txt')
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Histórico - <?php echo $nome ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
	<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
	<link rel="stylesheet" href="/styles/style.css">
</head>

<body>
	<!--- Topbar -->
	<nav id="topbar" class="navbar navbar-expand-lg bg-body-tertiary">
		<div class="container-fluid">
			<a id="sidebarCollapse" class="btn btn-secondary">
				<i class="fas fa-bars"></i>
			</a>
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
					<a href="/dashboard.php">Dashboard</a>
				</li>
			</ul>
			<ul class="flex-column components">
				<li>
					<a href="/logout.php">Log out</a>
				</li>
			</ul>
		</nav>
		<!-- Page Content -->
		<div id="content">
			<div class="container pt-4">
				<h2><?php echo $nome; ?></h2>

				<div class="card mt-4 mb-4">
					<div class="card-header">
						<b>Tabela de Logs</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th scope="col">Data de Atualização</th>
									<th scope="col">Estado</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($logs as $row) {
									echo '<tr>
												<td>' . $row[0] . '</td>
												<td>' . $row[1] . '</td>
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
	<!-- Popper.JS -->
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<!--Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>