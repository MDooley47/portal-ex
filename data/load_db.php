<?php

$tables = [
    "apps",
    "attributes",
    "groups",
    "groupApps",
    "groupTypes", # Needs to seed
    "ipAddresses",
    "ownerTabs",
    "ownerTypes", # Needs to seed
    "privileges",
    "settings",
    "tabApps",
    "tabs",
    "userGroups",
    "userPrivileges",
    "users",
];

$baseDir = __DIR__ . "/schemas";

$db = new PDO('pgsql:host=db', 'postgres', 'asdfgh');

$db->exec("CREATE DATABASE portal;");

foreach ($tables as $table)
{
    $db = new PDO('pgsql:host=db;dbname=portal', 'postgres', 'asdfgh');

    try
    {

        $fh = fopen($baseDir . "/{$table}_schema.sql", 'r');
        while ($line = fread($fh, 4096))
        {
            $db->exec($line);
        }
        fclose($fh);
        echo "Loaded {$table}_schema.sql\n";
    }
    catch (PDOException $e)
    {
        die("DB ERROR: ". $e->getMessage() . "\n");
    }
}
