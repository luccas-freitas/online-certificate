<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

session_start();
require_once '../config.php';

if ($stmt = $PDO->prepare('SELECT null FROM accounts WHERE cpf = ?')) {
    $stmt->execute(array($_POST['newcpf']));

    if ($stmt->rowCount() > 0) {
        alert('CPF já cadastrado.', '../index.php');
    } else {
        if ($stmt = $PDO->prepare('INSERT INTO accounts (cpf, password, email, activation_code) VALUES (?, ?, ?, ?)')) {

            $uniqid = uniqid();
            $password = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
            $stmt->execute(array($_POST['newcpf'], $password, $_POST['email'], $uniqid));

            try {
                $mail = new PHPMailer(true);

                $activate_link = 'localhost:8080/login/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;
                $message = '<p>Por favor, clique no link abaixo para ativar seu cadastro: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';

                $mail->setLanguage('pt_br');
/*                $mail->SMTPDebug = SMTP::DEBUG_SERVER;*/
                $mail->SMTPSecure = 'ssl';
                $mail->isSMTP();

                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'contact@company.com';
                $mail->Password = 'CH4NG3_M3';
                $mail->Port = 465;

                $mail->SetFrom("contact@company.com", "FAKE - Fictional Account Knowledge Enterprise");
                $mail->AddAddress($_POST['email']);
                $mail->Subject = 'Complete seu cadastro para acessar seus certificados!';

                $mail->isHTML(true);
                $mail->Body = $message;

                $mail->send();

                alert('E-mail de ativação enviado. Por favor, cheque sua caixa de entrada.', '../index.php');
            } catch (Exception $e) {
                print
                '<script type="text/javascript">
                    alert(\'' . $e->errorMessage() . '\');
                </script>';
            }
        } else {
            alert('Erro Interno. Por favor, contate o suporte: contato@silp.com.br', '../index.php');
        }
    }
    $stmt->closeCursor();
} else {
    alert('Erro Interno. Por favor, contate o suporte: contato@silp.com.br', '../index.php');
}