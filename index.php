<?php
    require_once('utils.php');

    session_start();

	// Obtem credencias do ficheiro
    $credentials = analyze_credentials('../credentials.txt');

    // Verifica se algum utilizador já se encontra logado
	if (isset($_SESSION['username']) and is_user($_SESSION['username'], $credentials)) {
		header("Location: /dashboard.php");
	}
    
    if (isset($_POST['username']) and !empty($_POST['username'])        // Valida o username
        and isset($_POST['password']) and !empty($_POST['password'])) { // Valida a password
        
        // Obtem as credencias do utilizador referentes aos dados inseridos pelo utilizador
        $user = get_user($_POST['username'], $credentials);
        // Se as credenciais forem válidas
        if (!empty($user) and strcmp($_POST['username'], $user[0]) == 0 and password_verify($_POST['password'], $user[1])) {
            $_SESSION["username"] = $_POST['username'];
            header("Location: /dashboard.php");
        } else { // Se não forem válidas

        }
    }
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <title>Parque de Estacionamento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- ficheiro css-->
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <!-- Card Login -->
    <div class="login">
        <!-- Titulo -->
        <h1 class="text-center">Parque de Estacionamento</h1>
        <!-- METODO USADO -->
        <form  method="post" class="needs-validation">
            <!-- Espaco Username -->
            <div class="form-group was-validated">
                <label class="form-label" for="username">Username</label>
                <input class="form-control" id="username" type="text" name="username" required> <!-- required -> obrigatorio escrever-->
                <div class="invalid-feedback">
                    Escreva o seu endereço de email
                </div>
            </div>
            <!-- Espaco password -->
            <div class="form-group was-validated">
                <label class="form-label" for="password">Password</label>
                <input class="form-control" id="password" type="password" name="password" required> <!-- required -> obrigatorio escrever-->
                <div class="invalid-feedback">
                    Escreva a sua password
                </div>
            </div>
            <!-- Butao submit-->
            <input class="btn btn-primary w-100" type="submit" value="ENTRA">  
        </form>
    </div>
</body>
</html>
