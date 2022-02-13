<?php
    require_once 'chrono-crud.php';

    // Initialization.
    $db = mysqli_connect('localhost', 'root', '', 'example-uses');
    $table_name = 'account';

    // // Collecting user data
    // $user_data =  [
    //     'name' => 'John Doe',
    //     'email' => '<h1>qwe</h1>',
    //     'password' => password_hash('johndoe@123', PASSWORD_DEFAULT)
    // ];

    // // Sanitize array data
    // $user_data = sanitizeArray($db, $user_data);

    // // Creating Insert Statement.
    // $statement = createQuery($db, $tableName, $user_data);

    // // Checking if the data is already existing in the database.
    // if(isExists($db, $tableName, 'email', $user_data['email'])) {
    //     echo "{$user_data['email']} is already exising in the database.";
    //     return false;
    // }

    // $isExecuted = execQuery($db, $statement);

    // if($isExecuted):
    //     echo "Successfully inserted to database.";
    // else: 
    //     echo "Operation failed.";
    // endif;

    registerAccount($db, $table_name, [
        'email' => 'chrono',
        'password' => password_hash('foo', PASSWORD_DEFAULT)
    ]);

    
    
    closeConnection($db); // Close database connection.
?>