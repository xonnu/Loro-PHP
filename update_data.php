<?php
require_once 'loro.php';

// Database Configuration
$database_connection = new mysqli('localhost', 'root', '', 'chrono-crud-example');

// Loro Initialization
$loro = new Loro($database_connection);
$loro->tableName('account');

// Creating Update Query
$update_statement = $loro->updateQuery([
    "username" => 'loro-update'
], ['user_id' => 'LORO_109638']);

// Checking if data update success
$is_update_success = $update_statement->execute();

if ($is_update_success) {
    print "Data updated!";
} else {
    print "Data update failed!";
}

