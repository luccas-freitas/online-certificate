<?php
session_start();
require_once '../config.php';

if ($stmt = $PDO->prepare('SELECT * FROM accounts WHERE accounts.cpf = ?')) {
    $stmt->execute(array($_POST['cpf']));

    if ($stmt->rowCount() > 0) {
        $account = $stmt->fetch(PDO::FETCH_OBJ);

        if ($account->activation_code == 'activated') {
            if (password_verify($_POST['password'], $account->password)) {
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['cpf'] = $_POST['cpf'];

                header('Location: ../home/home.php');
            } else {
                alert('Senha inválida!', '../index.php');
            }
        } else {
            alert('Cadastro não validado. Favor verificar sua caixa de e-mail.', '../index.php');
        }
    } else {
        alert('CPF não cadastrado.', '../index.php');
    }

    $stmt->closeCursor();
}
