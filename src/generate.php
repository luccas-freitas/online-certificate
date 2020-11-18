<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    use Fpdf\Fpdf;

    require '../config.php';
    require '../vendor/autoload.php';

    $id = $_POST['certificado_id'];

    $sql = "SELECT eventos.filename, participantes.nome, participantes.cpf, accounts.email 
            FROM participantes, eventos, accounts
            WHERE participantes.evento_id = eventos.id
            AND participantes.cpf = accounts.cpf 
            AND participantes.id = '" . $id . "'";

    $stmt = $PDO->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        $pdf = new Fpdf();
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddFont('AlexBrush-Regular','', 'AlexBrush-Regular.php');

        // Frente
        $frente = "layout/" . $row->filename . ".jpg";
        $pdf->AddPage('L');
        $pdf->SetLineWidth(1.5);
        $pdf->Image($frente,0,0,300);

        // Nome
        $str = $row->nome;
        $nome = iconv(mb_detect_encoding($str), 'windows-1252', $str);

        $pdf->SetFont('AlexBrush-Regular', '', 40); // Tipo de fonte e tamanho
        $pdf->SetXY(53,83); //Parte chata onde tem que ficar ajustando a posição X e Y
        $pdf->MultiCell(220, 10, $nome, '', 'C', 0); // Tamanho width e height e posição

        // Verso
        $verso = "layout/" . $row->filename . "-verso.jpg";
        $pdf->AddPage('L');
        $pdf->SetLineWidth(1.5);
        $pdf->Image($verso,0,0,300);

        // Código
        $pdf->SetFont('courier', 'B', 10);
        $pdf->SetXY(90, 195);
        $str = utf8_decode('CÓDIGO: ');
        $pdf->MultiCell(130, 11, $str . $id, '', 'C', 0);

        $pdf->Output('I', 'certificado.pdf');

        // --------- Enviando documento por e-mail --- //
        $subject = 'Certificado - Silp Eventos & Treinamentos';
        $messageBody = "Olá $row->nome!
            <br><br>
        É com grande prazer entregamos o seu certificado.
        <br>
        Ele segue em anexo neste e-mail.
        <br><br>
        Atenciosamente,
        <br>SILP | Eventos & Treinamentos
        <br>
        <a href='https://www.silp.com.br/'>https://www.silp.com.br/</a>";

        $pdfdoc = $pdf->Output('', 'S');
        $mail = new PHPMailer(true);
        try {
            $mail->setLanguage('pt_br');
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();

            $mail->Host = 'smtp.umbler.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'contato@silp.com.br';
            $mail->Password = 'silp@@2018';
            $mail->Port = 587;

            $mail->SetFrom("contato@silp.com.br", "Certificado - Silp Eventos & Treinamentos");
            $mail->AddAddress($row->email);
            $mail->Subject = $subject;

            $mail->isHTML(true);
            $mail->Body = $messageBody;
            $mail->addStringAttachment($pdfdoc, 'certificado.pdf');

            $mail->send();
        } catch (Exception $e) {
            print
                '<script type="text/javascript">
                    alert(\'' . $e->errorMessage() . '\');
                </script>';
        }
    }
    else {
        print
            '<script type="text/javascript">
            alert("Erro ao gerar certificado. Favor entrar em contato com nosso " +
             "suporte via e-mail: contato@silp.com.br");
            location="../home/home.php";
        </script>';
    }

    $stmt->closeCursor();
