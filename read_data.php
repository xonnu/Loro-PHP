<?php
require_once 'Loro.php';

// Database Configuration
$database_connection = new mysqli('localhost', 'root', '', 'chrono-crud-example');

// Loro Initialization
$loro = new Loro($database_connection);
$loro->tableName('account');

$read_statement = $loro->readQuery('username', ['user_id' => 'LORO_386639']);
$read_statement->execute();
$is_executed = $read_statement->execute();

if ($is_executed) {
    print "Executed";
} else {
    print "Failed";
}