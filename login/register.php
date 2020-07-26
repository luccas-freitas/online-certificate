<?php
session_start();
require_once '../config.php';

if ($stmt = $PDO->prepare('SELECT id, password FROM accounts WHERE cpf = ?')) {
    $stmt->execute(array($_POST['newcpf']));

    if ($stmt->rowCount() > 0) {
        alert('CPF já cadastrado.', '../index.html');
    } else {
        if ($stmt = $PDO->prepare('INSERT INTO accounts (cpf, password, email, activation_code) VALUES (?, ?, ?, ?)')) {
            if (preg_match('/[0-9]+/', $_POST['newcpf']) == 0) {
                alert('CPF inválido!', '../index.html');
            }
            $uniqid = uniqid();
            $password = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
            $stmt->execute(array($_POST['newcpf'], $password, $_POST['email'], $uniqid));

            $from    = 'noreply@silp.com.br';
            $subject = 'Account Activation Required';
            $headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
            $activate_link = 'http://www.silp.com.br/cursos/certificados/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;
            $message = '<p>Por favor, clique no link abaixo para ativar seu cadastro: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';
            mail($_POST['email'], $subject, $message, $headers);

            alert('E-mail de ativação enviado. Por favor, cheque sua caixa de entrada.', '../index.html');
        } else {
            alert('Erro Interno. Por favor, contate o suporte: contato@silp.com.br', '../index.html');
        }
    }
    $stmt->closeCursor();
} else {
    alert('Erro Interno. Por favor, contate o suporte: contato@silp.com.br', '../index.html');
}