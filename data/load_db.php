<?php

try
{
    $db = new PDO('pgsql:host=db', 'postgres', 'asdfgh');

    $db->exec("CREATE DATABASE portal;");

    $db = new PDO('pgsql:host=db;dbname=portal', 'postgres', 'asdfgh');

    $fh = fopen(__DIR__ . '/schema.sql', 'r');
    while ($line = fread($fh, 4096))
    {
        $db->exec($line);
    }
    fclose($fh);
    echo "Successfully loaded schema.sql\n";
}
catch (PDOException $e)
{
    die("DB ERROR: ". $e->getMessage() . "\n");
}
