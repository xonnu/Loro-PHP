**
Usage
    $query = createInsertQuery($databaseConnection, "account", [
        "name" => "Justin Pascual",
        "email" =>  "example@gmail.com",
        "password" => password_hash("pass123", PASSWORD_DEFAULT)
    ]);

    $isInserted = createData($databaseConnection, $query);
    
    if($isInserted) {
        echo "data inserted";
    }
**