<?php
require_once 'Loro.php';

// Database Configuration
$database_connection = new mysqli('localhost', 'root', '', 'loro-database-example');

// Loro Initialization
$loro = new Loro($database_connection);
$loro->tableName('fruits');

// Delete data from database using id.
$delete_statement = $loro->deleteQuery(['fruit_id' => '3']);
$is_executed = $delete_statement->execute();

if ($is_executed) {
    print "Fruit Deleted";
} else {
    print "Fruit Deletion failed";
}