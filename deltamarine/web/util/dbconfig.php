<?php
    $host = '127.0.0.1';
    $dbname = 'deltamarine';
    $username = 'deltamarine';
    $password = 'delt@55!marine';

    $pdo = NULL;
    $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;
    
    try {
        $pdo = new PDO($dsn, $username, $password);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $pe) {
        die("Could not connect to the database $dbname :" . $pe->getMessage());
    }
