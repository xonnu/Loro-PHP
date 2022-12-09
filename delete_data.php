<?php
require_once 'Loro.php';

// Database Configuration
$database_connection = new mysqli('localhost', 'root', '', 'chrono-crud-example');

// Loro Initialization
$loro = new Loro($database_connection);
$loro->tableName('account');

// Delete data from database using id.
$delete_statement = $loro->deleteQuery(['user_id' => 'LORO_700639']);
$is_executed = $delete_statement->execute();

if ($is_executed) {
    print "Deleted";
} else {
    print "Deletion failed";
}