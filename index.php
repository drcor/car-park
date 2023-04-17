<?php
session_start();

$username="admin";    
$password_hash='$2y$10$WhpkqfSmVl3l9ev1jLr0OeG07kvrPcT4R0FQUzOG3tXP11nvHYdAO'; //pass: admin

if (isset($_POST['username']) and isset($_POST['password'])) {
    if (password_verify($_POST['password'], $password_hash)) {
        $_SESSION["username"] = $_POST['username'];
        header("refresh:1;url=dashboard.php");
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
    <link rel="stylesheet" href="styles/style.css">

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
                <label class="form-label" for="email">Username</label>
                <input class="form-control" type="username" name="username" required> <!-- required -> obrigatorio escrever-->
                <div class="invalid-feedback">
                    Escreva o seu endereço de email
                </div>
            </div>
            <!-- Espaco password -->
            <div class="form-group was-validated">
                <label class="form-label" for="password">Password</label>
                <input class="form-control" type="password" name="password" required> <!-- required -> obrigatorio escrever-->
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