<?php
require_once 'Loro.php';

// Database Configuration
$database_connection = new mysqli('localhost', 'root', '', 'chrono-crud-example');

// Loro Initialization
$loro = new Loro($database_connection);
$loro->tableName('account');

// Print single data from database.
$read_statement = $loro->readQuery('username', ['user_id' => 'LORO_386639']);
print $read_statement->read();