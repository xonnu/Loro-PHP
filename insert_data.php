<?php
require_once 'Loro.php';

// Database Configuration
$database_connection = new mysqli('localhost', 'root', '', 'loro-database-example');

// Loro Initialization
$loro = new Loro($database_connection);
$loro->tableName('fruits');

// Creating Insert Query
$insert_statement = $loro->insertQuery([
    "fruit_id" => $loro->uid(),
    "fruit" => '🥕',
    "fruit_rate" => "Bad",
]);

// Checking if data insert success
$is_insert_success = $insert_statement->execute();

if ($is_insert_success) {
    print "Fruit inserted!";
} else {
    print "Fruit insertion failed!";
}


