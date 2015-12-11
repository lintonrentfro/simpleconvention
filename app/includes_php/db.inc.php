<?php
/**
 * this is the db user connection that can do anything to this database
 */

try {
    $pdo = new PDO('mysql:host=localhost;dbname=simpleco_demo3',
        'simpleco_demo3', 'supermouse3245434');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('SET NAMES "utf8"');
}
catch (PDOException $e) {
    $error = 'Unable to connect to the database server.';
    require '/home/simpleco/demo2/app/pages_public/error.inc.html.php';
    exit();
}