<?php
    require_once('utils.php');

    session_start();

    // Obtem credenciais do ficheiro
    $credentials = analyze_credentials('../credentials.txt');

    if (isset($_POST['username']) and !empty($_POST['username'])        // Valida o username
        and isset($_POST['password']) and !empty($_POST['password'])) { // Valida a password
        
        // Obtem utilizador do ficheiro
        $user = get_user($_POST['username'], $credentials);

        // Valida as crenciais inseridas pelo utilizador
        if (!empty($user) and strcmp($user[0], $_POST['username']) == 0 and password_verify($_POST['password'], $user[1])) {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styles/style.css">

</head>

<body>
    <div class="login">
        <h1 class="text-center">Bem-Vindo!</h1>
        <form  method="post" class="needs-validation">
            <div class="form-group was-validated">
                <label class="form-label" for="email">Username</label>
                <input class="form-control" type="username" name="username" required>
                <div class="invalid-feedback">
                    Escreva o seu endereço de email
                </div>
            </div>
            <div class="form-group was-validated">
                <label class="form-label" for="password">Password</label>
                <input class="form-control" type="password" name="password" required>
                <div class="invalid-feedback">
                    Escreva a sua password
                </div>
            </div>
            <input class="btn btn-primary w-100" type="submit" value="ENTRA">
        </form>
    </div>
</body>
</html>