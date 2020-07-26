<?php
session_start();
// Change this to your connection info.
require_once '../config.php';

if ($stmt = $PDO->prepare('SELECT accounts.password, participantes.nome FROM accounts, participantes 
                                WHERE accounts.cpf = participantes.cpf AND accounts.cpf = ?')) {
    $stmt->execute(array($_POST['cpf']));

    if ($stmt->rowCount() > 0) {
        $account = $stmt->fetch(PDO::FETCH_OBJ);

        if (password_verify($_POST['password'], $account->password)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['cpf'] = $_POST['cpf'];
            $_SESSION['nome'] = $account->nome;

            header('Location: ../home/home.php');
        } else {
            alert('Senha inválida!', '../index.html');
        }
    } else {
        alert('CPF não cadastrado.', '../index.html');
    }

    $stmt->closeCursor();
}
