<?php
    session_start();
    require_once '../config.php';

    if (!isset($_SESSION['loggedin'])) {
        header('Location: ../index.php');
        exit;
    } else {
        $query = 'SELECT participantes.nome, participantes.id, eventos.titulo, eventos.dia, eventos.cidade, eventos.uf
            FROM eventos, participantes 
            WHERE eventos.id = participantes.evento_id 
            AND participantes.cpf = ' . $_SESSION['cpf'];
        if ($stmt = $PDO->prepare($query)) {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $certificados = $stmt->fetchAll(PDO::FETCH_OBJ);
                $first_name = explode(" ", $certificados[0]->nome)[0];
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SILP - Eventos & Treinamentos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <a class="navbar-brand mr-auto mr-lg-0" href="#">Bem-vindo,
        <?=
            $first_name;
        ?>!
    </a>
    <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="../login/logout.php" tabindex="-1" aria-disabled="true">
                    <span style="color: #f2f2f2">Sair</span>
                    <i class="fa fa-sign-out" aria-hidden="true" style="color: #f2f2f2;"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>

<main role="main" class="container">
    <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded shadow-sm">
        <img class="mr-3" style="filter: invert(100%)" src="img/degree.png" alt="" width="48" height="48">
        <div class="lh-100">
            <h6 class="mb-0 text-white lh-100">Meus Certificados</h6>
            <small>SILP - Eventos & Treinamentos</small>
        </div>
    </div>
    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <?php
            foreach ($certificados as $row) {
                print
            '<div class="media text-muted pt-3">
                <img class="bd-placeholder-img mr-2 rounded" width="32" height="32" src="img/certificate.png" alt=""/>
                <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <strong class="text-gray-dark">' . $row->titulo . '</strong>
                        <form action="../src/generate.php" method="post" id="generate_form" >
                            <input type="hidden" name="certificado_id" value=' . $row->id . '>
                            <a href="#" onClick="document.getElementById(\'generate_form\').submit();">
                                Download
                            </a>
                        </form>
                    </div>
                    <span class="d-block">'
                    . date('d/m/Y', strtotime($row->dia))
                    . ' - '
                    . $row->cidade
                    . '/'
                    . $row->uf
                    .
                    '</span>
                </div>
            </div>';
            }
        ?>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
