<?php
require 'vendor/autoload.php';

try{
    define( 'MYSQL_HOST', 'localhost' );
    define( 'MYSQL_USER', 'luccas' );
    define( 'MYSQL_PASSWORD', '061294' );
    define( 'MYSQL_DB_NAME', 'certificados' );

    $PDO = new PDO( 'mysql:host=' . MYSQL_HOST
        . ';dbname=' . MYSQL_DB_NAME, MYSQL_USER, MYSQL_PASSWORD);
}
catch ( PDOException $e ){
    echo 'Erro ao conectar com o MySQL: ' . $e->getMessage();
}

function alert($msg, $location) {
    return
        print  '<script type="text/javascript">'
               .     'alert(\'' . $msg . '\');'
               .    'location=\'' . $location .'\';'
               .'</script>';
}