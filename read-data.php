<?php
    require_once 'chrono-crud.php';

    // Initialization.
    $db = mysqli_connect('localhost', 'root', '', 'test');
    $tableName = 'account';

    // Single data read.
    // $query = readQuery($tableName, 'name', ['id' => '9']);
    // echo execRead($db, $query);

    // Multiple or more data read.
    $query = readAllQuery($db, $tableName, '*');
    
    echo "<pre>";
    print_r(execReadAll($db, $query)); // JSON or ARRAY (default)
    echo "</pre>";
    
    
    closeConnection($db); // close database connection.
?>