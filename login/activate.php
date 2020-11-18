<?php
session_start();
require_once '../config.php';

if (isset($_GET['email'], $_GET['code'])) {
    if ($stmt = $PDO->prepare('SELECT * FROM accounts WHERE email = ? AND activation_code = ?')) {
        $stmt->execute(array($_GET['email'], $_GET['code']));

        if ($stmt->rowCount() > 0) {
            if ($stmt = $PDO->prepare('UPDATE accounts SET activation_code = ? WHERE email = ? AND activation_code = ?')) {
                $newcode = 'activated';
                $stmt->execute(array($newcode, $_GET['email'], $_GET['code']));

                alert('Cadastro validado com sucesso. Agora você já pode acessar seus certificados.', '../home/home.php');
            }
        } else {
            alert('Este cadastro já está ativado.', '../index.php');
        }
    } else {
        alert('Parâmetros inválidos.', '../index.php');
    }
} else {
    header('Location: ../index.php');
}