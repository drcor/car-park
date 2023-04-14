<!DOCTYPE html>
<html lang="en">

<head>

    <title>Parque de Estacionamento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap v5.1.3 CDNs -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CSS File -->
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="login">
        <h1 class="text-center">Bem-Vindo!</h1>
        <form class="needs-validation">
            <div class="form-group was-validated">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" required>
                <div class="invalid-feedback">
                    Escreva o seu endereço de email
                </div>
            </div>
            <div class="form-group was-validated">
                <label class="form-label" for="password">Password</label>
                <input class="form-control" type="password" id="password" required>
                <div class="invalid-feedback">
                    Escreva a sua password
                </div>
            </div>
            <input class="btn btn-primary w-100" type="submit" value="SIGN IN">
        </form>
    </div>
</body>
</html>