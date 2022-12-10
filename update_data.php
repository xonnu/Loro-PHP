<?php

require_once 'Loro.php';

// Database Configuration
$database_connection = new mysqli('localhost', 'root', '', 'loro-database-example');

// Loro Initialization
$loro = new Loro($database_connection);
$loro->tableName('fruits');

// Creating Update Query
$update_statement = $loro->updateQuery([
    "fruit" => '🍊'
], ['fruit_id' => 2]);

// Checking if data update success
$is_update_success = $update_statement->execute();

if ($is_update_success) {
    print "Fruit updated!";
} else {
    print "Fruit update failed!";
}

