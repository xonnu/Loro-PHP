<?php   
    
    function closeConnection(object $databaseConnection) : bool {
        return mysqli_close($databaseConnection);
    }

    function executeQuery(object $databaseConnection, string $query) : bool {
        return mysqli_query($databaseConnection, $query);
    }
    
    function rowCount(object $databaseConnection, string $tableName) : int {
        return mysqli_num_rows(mysqli_query($databaseConnection, "SELECT * FROM $tableName"));
    }

    function sanitize(object $databaseConnection, string $data) : string {
        return @mysqli_real_escape_string($databaseConnection, htmlentities(preg_replace('/<[^>]*>/', '*', $data), ENT_COMPAT, 'UTF-8'));
    }

    function sanitizeArray(object $databaseConnection, array $arrayOfData = []) : array {

        foreach ($arrayOfData as $columnKey => $columnValue) {
            $columnKey   = sanitize($databaseConnection, $columnKey);
            $columnValue = sanitize($databaseConnection, $columnValue);
            $arrayOfData[$columnKey] = $columnValue;
        }

        return $arrayOfData;
    }

    function createInsertStatement(object $databaseConnection, string $tableName, array $arrayOfData) : string {
        $cleanArrayOfData = sanitizeArray($databaseConnection, $arrayOfData);
        $columnNames = implode(', ', array_keys($cleanArrayOfData));
        $columnValues = implode("', '", array_values($cleanArrayOfData));
        return "INSERT INTO $tableName($columnNames) VALUES('$columnValues')";
    }

    // Read Query Function
    function createReadAllQuery(string $tableName, $selectColumn = '*', array $where = [], array $rowOption = [0, 0]) : string {
        if($selectColumn == '*') {
            $selectColumn = '*';
        } else if(is_array($selectColumn)) {
            $selectColumn = implode(', ', array_values($selectColumn));
        }

        $isRowOptionChanged = $rowOption[0] != 0 && $rowOption[1] != 0; 
        if($isRowOptionChanged) {
            return "SELECT $selectColumn FROM $tableName LIMIT {$rowOption[0]}, {$rowOption[1]}";
        }

        if(count($where) != 0 && count($where) == 1) {
            $tableColumn = key($where);
            return "SELECT $selectColumn FROM $tableName WHERE $tableColumn= $where[$tableColumn]";
        } else {
            return "Error: Please use only one key and one value";
        }

        return "SELECT $selectColumn FROM $tableName";
    }

    // Does not use @executeQuery Function
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
        $whereArrayCount = count($where);
        if($whereArrayCount == 0) {
            return "SELECT $columnName FROM $tableName";
        }

        if($whereArrayCount != 1) {
            return "Error: Please use only one key and one value";
        }

        $tableColumn = key($where);
        $columnValue = $where[$tableColumn];
        
        return "SELECT $columnName FROM $tableName WHERE $tableColumn = '$columnValue'";
    }

    function readData(object $databaseConnection, string $query) {
        return @mysqli_fetch_row(mysqli_query($databaseConnection, $query))[0];
    }

    function createUpdateStatement(string $tableName, $set, array $where = []) : string {
        $whereArrayCount = count($where);
        
        if($whereArrayCount == 0) {
            die("Error: WHERE cannot be empty");
            return "";
        }

        if($whereArrayCount != 1) {
            die("Error: Please use only one key and one value");
            return "";
        }

        $newSet = [];
        foreach ($set as $key => $value) {
           $newSet[] = "$key = '$value'";
        }

        $setData = implode(', ', $newSet);
        $tableColumn = key($where);

        return "UPDATE $tableName SET $setData WHERE $tableColumn = '$where[$tableColumn]'";
    }

    function createDeleteStatement(string $tableName, array $where = []) : string {
        $whereArrayCount = count($where);
        
        if($whereArrayCount == 0) {
            die("Error: WHERE cannot be empty");
            return "";
        }

        if($whereArrayCount != 1) {
            die("Error: Please use only one key and one value");
            return "";
        }

        $tableColumn = key($where);
        
        return "DELETE FROM $tableName WHERE $tableColumn = '$where[$tableColumn]'";
    }

    function isFieldsEmpty(array  $arrayOfData) : bool {
        foreach ($arrayOfData as $key => $value) {
            if(empty($value)) {
                return true;
                break;
            }
        }
        return false;
    }
?>