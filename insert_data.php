<?php
require_once 'loro.php';

// Database Configuration
$database_connection = new mysqli('localhost', 'root', '', 'chrono-crud-example');

// Loro Initialization
$loro = new Loro($database_connection);
$loro->tableName('account');

// Creating Insert Query
$insert_statement = $loro->insertQuery([
    "user_id" => $loro->uid(),
    "username" => 'user-' . rand(1, 9999),
    "email" => 'user' . rand(1, 9999) . '@gmail.com',
    "password" => password_hash('password' . rand(1, 9999), PASSWORD_DEFAULT)
]);

// Checking if data insert success
$is_insert_success = $insert_statement->execute();

if ($is_insert_success) {
    print "Data inserted!";
} else {
    print "Data insertion failed!";
}


