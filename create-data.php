<?php
    require_once 'chrono-crud.php';

    // Initialization.
    $db = mysqli_connect('localhost', 'root', '', 'test');
    $tableName = 'account';

    // Creating Insert Statement.
    $statement = createQuery($db, $tableName, [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => password_hash('pass123', PASSWORD_DEFAULT)
    ]);

    // Check if the data was inserted.
    $isExecuted = execQuery($db, $statement);

    if($isExecuted):
        echo "Successfully inserted to database.";
    else: 
        echo "Operation failed.";
    endif;
    
    closeConnection($db); // Close database connection.
?>