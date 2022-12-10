<?php
require_once 'Loro.php';

// Database Configuration
$database_connection = new mysqli('localhost', 'root', '', 'loro-database-example');

// Loro Initialization
$loro = new Loro($database_connection);
$loro->tableName('fruits');

// Print single data from database.
$read_statement = $loro->readQuery('fruit', ['fruit_id' => '2']);
printf("Fruit: %s", $read_statement->read());