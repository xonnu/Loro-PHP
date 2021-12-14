<?php
    require_once 'chrono-crud.php';

    // Initialization
    $db = mysqli_connect('localhost', 'root', '', 'test');
    $tableName = 'account';

    $email = 'johndoe@example.com'; // example data

    if(!isExists($db, $tableName, 'email', $email)):
        // Insert logic here...
        echo "This email is new!";
    else:
        echo "This email is already on the database";
    endif;


    closeConnection($db); // close database connection.
?>