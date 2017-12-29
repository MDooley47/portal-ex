<?php

$tables = [
    "apps",
    "users",
];

$db = new PDO('pgsql:host=db', 'postgres', 'asdfgh');

$db->exec("CREATE DATABASE portal;");

foreach ($tables as $table)
{
    $db = new PDO('pgsql:host=db;dbname=portal', 'postgres', 'asdfgh');

    try
    {

        $fh = fopen(__DIR__ . "/{$table}Schema.sql", 'r');
        while ($line = fread($fh, 4096))
        {
            $db->exec($line);
        }
        fclose($fh);
        echo "Loaded {$table}Schema.sql\n";
    }
    catch (PDOException $e)
    {
        die("DB ERROR: ". $e->getMessage() . "\n");
    }
}
