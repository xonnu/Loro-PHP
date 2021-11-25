<?php   
    /* For sample use only */
    $databaseConnection = @mysqli_connect('localhost', 'root', '', 'test');
    
    function closeConnection(object $databaseConnection) : bool {
        return mysqli_close($databaseConnection);
    }

    function rowCount(object $databaseConnection, string $tableName) : int {
        return mysqli_num_rows(mysqli_query($databaseConnection, "SELECT * FROM $tableName"));
    }

    function sanitizeArray(object $databaseConnection, array $arrayOfData = []) : array {

        foreach ($arrayOfData as $columnName => $columnValue) {
            $columnName  = @mysqli_real_escape_string($databaseConnection, htmlentities($columnName, ENT_QUOTES, 'UTF-8'));
            $columnValue = @mysqli_real_escape_string($databaseConnection, htmlentities($columnValue, ENT_QUOTES, 'UTF-8'));
            $arrayOfData[$columnName] = $columnValue;
        }

        return $arrayOfData;
    }

    function sanitize(object $databaseConnection, string $data) : string {
        return @mysqli_real_escape_string($databaseConnection, htmlentities($data, ENT_QUOTES, 'UTF-8'));
    }

    // Create Function
    function createInsertQuery(object $databaseConnection, string $tableName, array $arrayOfData) : string {
        $cleanArrayOfData = sanitizeArray($databaseConnection, $arrayOfData);
        $columnNames = implode(', ', array_keys($cleanArrayOfData));
        $columnValues = implode("', '", array_values($cleanArrayOfData));

        return "INSERT INTO $tableName($columnNames) VALUES('$columnValues')";
    }

    function createData(object $databaseConnection, string $query) : bool {
        return mysqli_query($databaseConnection, $query);
    }

    // Read Function
    function createReadAllQuery(string $tableName, $selectColumn = '*', array $rowOption = [0, 0]) : string {
        if($selectColumn == '*') {
            $selectColumn = '*';
        } else {
            if(is_array($selectColumn)) {
                $selectColumn = implode(', ', array_values($selectColumn));
            }
        }

        $isRowOptionChanged = $rowOption[0] != 0 && $rowOption[1] != 0; 

        if($isRowOptionChanged) {
            return "SELECT $selectColumn FROM $tableName LIMIT {$rowOption[0]}, {$rowOption[1]}";
        }

        return "SELECT $selectColumn FROM $tableName";
    }

    function readAllData(object $databaseConnection, string $query, string $returnType = 'ARRAY') {
        $result = mysqli_query($databaseConnection, $query);

        if(mysqli_num_rows($result) <= 0) {
            return [];
        }

        $fetchData = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $fetchCount = count($fetchData);
        
        $tableValues = [];

        for ($i=0; $i < $fetchCount; $i++) { 
            $tableValues[] = $fetchData[$i];
        }

        switch ($returnType) {
            case 'JSON':
                return json_encode($tableValues);
                break;
            case 'ARRAY':
                return $tableValues;
                break;
            default:
                return "Error: Unknown return type";
                break;
        }
    }

    function createReadQuery(string $tableName, string $columnName, array $where = []) : string {
        if(count($where) == 0) {
            return "SELECT $columnName FROM $tableName";
        }

        $tableColumn = key($where);
        $columnValue = $where[$tableColumn];
        
        return "SELECT $columnName FROM $tableName WHERE $tableColumn = $columnValue";
    }

    function readData(object $databaseConnection, string $query) {
        return mysqli_fetch_row(mysqli_query($databaseConnection, $query))[0];
    }

    // Example here
    echo readData($databaseConnection, createReadQuery('account', 'name', ['id' => '8']));
    
    closeConnection($databaseConnection);
?>