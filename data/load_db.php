<?php

try
{
    $db = new PDO('pgsql:host=db;user=postgres;password=asdfgh');

    $db->exec("CREATE DATABASE portal;");

    $fh = fopen(__DIR__ . '/schema.sql', 'r');
    while ($line = fread($fh, 4096))
    {
        $db->exec($line);
    }
    fclose($fh);
}
catch (PDOException $e)
{
    die("DB ERROR: ". $e->getMessage() . "\n");
}
