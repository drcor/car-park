<?php
require_once('utils.php');

session_start();

$credentials = analyze_credentials('/home/drcorreia/Git/credentials.txt');

if (!isset($_SESSION['username']) or !is_user($_SESSION['username'], $credentials)) {
	header("Location: /");
}

// get list of existing sensors
$sensors = get_dirs('api/files/');
// print_r($sensors);
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
	<link rel="stylesheet" href="/styles/style.css">
</head>

<body>
	<div class="wrapper">
		<!-- Sidebar -->
		<nav id="sidebar" class="d-flex flex-column">
			<div class="sidebar-header">
				<h4>Parque de Estacionamento</h4>
			</div>

			<ul class="flex-column mb-auto components">
				<li>
					<a href="/dashboard.php">Visão geral</a>
				</li>
				<li>
					<a href="#">Portfolio</a>
				</li>
			</ul>
			<ul class="flex-column components">
				<li>
					<a href="#">Configurações</a>
				</li>
				<li>
					<a href="/logout.php">Log out</a>
				</li>
			</ul>
		</nav>
		<!-- Page Content -->
		<div id="content">
			<!--- Topbar --->
			<nav id="topbar" class="navbar navbar-expand-lg bg-body-tertiary">
				<div class="container-fluid">
					<a id="sidebarCollapse" class="btn btn-secondary">
						<i class="fas fa-bars"></i>
					</a>
				</div>
			</nav>
			<div class="container pt-2">
				<h2></h2>
				<div class="row">
					<?php
					foreach ($sensors as $sensor) {
						$nome = file_get_contents('api/files/' . $sensor . '/nome.txt');
						$nomeLower = strtolower($nome);
						$valor = file_get_contents('api/files/' . $sensor . '/valor.txt');
						$hora = file_get_contents('api/files/' . $sensor . '/hora.txt');

						echo '<div class="col-sm-4">
								<div class="card text-center">
									<div class="card-header fw-bold sensor">' . $nome . '</div>
									<img src="/images/' . $nomeLower . '.png" alt="' . $nome . '" class="card-image-top img-fluid">
									<div class="card-body">
										<h5 class="card-title mb-3"><span id="' . $nomeLower . '">' . $valor . '</span> ' . get_sensor_symbol($nome) . '</h5>
										<small><b>Ultima atualização:</b> ' . $hora . '</small>
									</div>
								</div>
							</div>';
					}
					?>
				</div>
				<div class="container mt-5">
					<div class="card">
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
									</tr>
								</thead>
								<tbody>
									<tr>
									</tr>
									<tr>
									</tr>
									<tr>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="/scripts/dashboard.js"></script>
	<!-- Popper.JS -->
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
		< /> <!--Bootstrap JS-- > <
		script src = "https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
		integrity = "sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
		crossorigin = "anonymous" >
	</script>
</body>

</html>