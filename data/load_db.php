<?php

$tables = [
    "apps",
    "attributes",
    "groups",
    "groupApps",
    "groupTypes", # Needs to seed
    "groupGroups",
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

/** CODIST - CSV PROCESSING TIME **/

$rows   = array_map('str_getcsv', file(__DIR__ . "/schools.csv"));
$header = array_shift($rows);
$csv    = array();
foreach($rows as $row) {
    $csv[] = array_combine($header, $row);
}

$db = new PDO('pgsql:host=db;dbname=portal', 'postgres', 'asdfgh');

foreach($csv as $row)
{
/*
    // Add Counties

    try
    {
        $db->exec("INSERT INTO groups (\"groupType\", slug, name) VALUES ('j25lhv', '" . explode("-", $row["CODIST"])[0] . "', '" . ucwords(strtolower($row["COUNTY"])) . " County');");
    }
    catch (PDOException $e)
    {
        die("DB ERROR: ". $e->getMessage() . "\n");
    }
*/

    // Add Orgranization (schools)

    try
    {
        $db->exec("INSERT INTO groups (\"groupType\", slug, name) VALUES ('ri12io', '" . substr($row["CODIST"], 0, 7) . "', '" . ucwords(strtolower($row["SCHOOL NAME"])) . "');");
    }
    catch (PDOException $e)
    {
        die("DB ERROR: ". $e->getMessage() . "\n");
    }

/*
    // Add Buildings

    try
    {
        $db->exec("INSERT INTO groups (\"groupType\", slug, name) VALUES ('ri12io', '" . $row["CODIST"] . "', '" . ucwords(strtolower($row["SCHOOL NAME"])) . "');");
    }
    catch (PDOException $e)
    {
        die("DB ERROR: ". $e->getMessage() . "\n");
    }
*/
}
