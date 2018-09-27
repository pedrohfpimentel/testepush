<?php
    $pdo = new PDO("mysql:host=localhost; dbname=waldyrbecker; charset=utf8;", "root", "");
    $dados = $pdo->prepare("SELECT name FROM products");
    $dados->execute();
    echo json_encode($dados->fetchAll(PDO::FETCH_ASSOC));
