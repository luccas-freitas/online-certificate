<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="login/css/styles.css" type="text/css">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="login-page">
        <div class="form">
            <form class="login-form" action="login/login.php" method="post" autocomplete="off">
                <label>
                    <input type="text" id="cpf" name="cpf" placeholder="CPF" required/>
                </label>
                <label>
                    <input type="password" id="password" name="password" placeholder="Senha" required/>
                </label>
                <button type="submit">login</button>
                <input type="submit" style="position: absolute; left: -9999px"/>
                <p class="message">Sem acesso? <a href="#">Cadastre-se agora</a></p>
            </form>
            <form class="register-form" action="login/register.php" method="post" autocomplete="off">
                <label>
                    <input type="text" id="email" name="email" placeholder="E-mail" required/>
                </label>
                <label>
                    <input type="text" id="newcpf" name="newcpf" placeholder="CPF" required/>
                </label>
                <label>
                    <input type="password" id="newpassword" name="newpassword" placeholder="Senha" required/>
                </label>
                <button type="submit">Registrar</button>
                <input type="submit" style="position: absolute; left: -9999px"/>
                <p class="message">Já possui conta? <a href="#">Voltar para Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>
<script src="login/js/scripts.js"></script>
