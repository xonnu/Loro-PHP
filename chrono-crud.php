<?php   
    function closeConnection(object $connection) : bool {
        return mysqli_close($connection);
    }

    function execQuery(object $connection, string $query) : bool {
        return mysqli_query($connection, $query);
    }
    
    function rowCount(object $connection, string $table_name) : int {
        return mysqli_num_rows(mysqli_query($connection, "SELECT * FROM $table_name"));
    }

    function sanitize(object $connection, string $data) : string {
        return @mysqli_real_escape_string($connection, htmlentities(preg_replace('/<[^>]*>/', '*', @$data), ENT_COMPAT, 'UTF-8'));
    }

    function sanitizeArray(object $connection, array $array_of_data = []) : array {

        foreach ($array_of_data as $column_key => $column_value) {
            $column_key   = sanitize($connection, $column_key);
            $column_value = sanitize($connection, $column_value);
            $array_of_data[$column_key] = $column_value;
        }

        return $array_of_data;
    }

    function createQuery(object $connection, string $table_name, array $array_of_data) : string {
        $clean_array_of_data = sanitizeArray($connection, $array_of_data);
        $column_names = implode(', ', array_keys($clean_array_of_data));
        $column_values = implode("', '", array_values($clean_array_of_data));
        return "INSERT INTO $table_name($column_names) VALUES('$column_values')";
    }

    function isColumnExists(object $connection, string $table_name, $select_column) {
        if(is_array($select_column)) {
            for ($i=0; $i < count($select_column); $i++) { 
                $query = "SHOW COLUMNS FROM $table_name LIKE '{$select_column[$i]}'";
                $result = mysqli_query($connection, $query);
                $isExists = (mysqli_num_rows($result) == 0) ? true : false;

                if($isExists) {
                    return die("Error: \"{$select_column[$i]}\" column name doesn't exist in the table.");
                }
            }

            return false;
        }
        
        $query = "SHOW COLUMNS FROM $table_name LIKE '$select_column'";
        $result = mysqli_query($connection, $query);;

        return (mysqli_num_rows($result) == 0) ? die("Error: \"$select_column\" column name doesn't exist in the table.") : false;
    }

    function readAllQuery(object $connection, string $table_name, $select_column = '', array $where = [], array $row_option = []) : string {
        $query = "";
   
        if(!is_array($select_column)) {
            if(isEmpty($select_column) || $select_column == '*') {
                $query = "SELECT * FROM $table_name";
            }
        }   

        if(is_array($select_column)) {

            if(isEmpty($select_column) && $select_column != []) {
                die("Error: This array has an empty value.");
                return false;
            }
            
            if($select_column == []) {
                $query = "SELECT * FROM $table_name"; 
            }

            isColumnExists($connection, $table_name, $select_column);
            
            $select_column = implode(', ', array_values($select_column));
            
            if(strlen($select_column) <= 0) {
                $select_column = '*';
            }
            
            $query = "SELECT $select_column FROM $table_name";
        }

        if($where != null) {
            if(count($where) != 0 && count($where) == 1) {
                isColumnExists($connection, $table_name, array_keys($where)[0]); 
                
                $table_column = key($where);
                $query .= " WHERE $table_column = '$where[$table_column]'";
            } else {
                return die("Error: Please use only one key and one value");
            }
        }

        if($row_option != null) {
            if(count($row_option) != 2) {
                return die("Error: This array parameter has two (2) value. [OFFSET, LIMIT]");
            }
            
            list($offset, $limit) = $row_option;
            $query .= " LIMIT $offset, $limit";
        }

        return $query;
    }

    function execReadAll(object $connection, string $query, string $return_type = 'ARRAY') {
        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) <= 0) {
            return [];
        }

        $fetchData = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $fetchCount = count($fetchData);
        
        $tableValues = [];

        for ($i=0; $i < $fetchCount; $i++) { 
            $tableValues[] = $fetchData[$i];
        }

        switch ($return_type) {
            case 'JSON':
                return json_encode($tableValues, JSON_PRETTY_PRINT);
                break;
            case 'ARRAY':
                return $tableValues;
                break;
            default:
                return "Error: Unknown return type";
                break;
        }
    }

    function readQuery(string $table_name, string $column_name, array $where = []) : string {
        $where_array_count = count($where);
        if($where_array_count == 0) {
            return die("Error: Where paramter cannot be empty.");
        }

        if($where_array_count != 1) {
            return die("Error: Where parameter has one key and one value. ['column_name' => 'column_value]");
        }

        if(strlen($column_name) <= 0) {
            return die("Error: Column name parameter cannot be empty.");
        }

        $table_column = key($where);
        $column_value = $where[$table_column];
        
        return "SELECT $column_name FROM $table_name WHERE $table_column = '$column_value'";
    }

    function execRead(object $connection, string $query) {
        return @mysqli_fetch_row(mysqli_query($connection, $query))[0];
    }

    function updateQuery(string $table_name, $set, array $where = []) : string {
        $where_array_count = count($where);
        
        if($where_array_count == 0) {
            die("Error: WHERE cannot be empty");
            return "";
        }

        if($where_array_count != 1) {
            die("Error: Please use only one key and one value");
            return "";
        }

        $newSet = [];
        foreach ($set as $key => $value) {
           $newSet[] = "$key = '$value'";
        }

        $setData = implode(', ', $newSet);
        $table_column = key($where);

        return "UPDATE $table_name SET $setData WHERE $table_column = '$where[$table_column]'";
    }

    function deleteQuery(string $table_name, array $where = []) : string {
        $where_array_count = count($where);
        
        if($where_array_count == 0) {
            return die("Error: WHERE cannot be empty");
        }

        if($where_array_count != 1) {
            return die("Error: Please use only one key and one value");
        }

        $table_column = key($where);
        
        return "DELETE FROM $table_name WHERE $table_column = '$where[$table_column]'";
    }

    function isEmpty($array_of_data) : bool {
        if(is_array($array_of_data)) {
            if(count($array_of_data) <= 0){
                return true;
            }

            foreach ($array_of_data as $key => $value) {
                if(empty($value)) {
                    return true;
                    break;
                }
            }
            return false;
        }

        return (empty($array_of_data) || strlen($array_of_data) <= 0) ? true : false;
    }

    function isExists(object $connection, string $table_name, string $table_column, string $data) : bool {
        $clean_data = sanitize($connection, $data);
        $check_data_query = readQuery($table_name, $table_column, [$table_column => $clean_data]);
        $check_data = execRead($connection, $check_data_query);

        return ($check_data == $clean_data && strlen($check_data) == strlen($clean_data)) ? true : false;
    }

    function validateEmail(object $connection, string $email) {
        $clean_email = sanitize($connection, $email);
        return (filter_var($clean_email, FILTER_VALIDATE_EMAIL) && checkdnsrr(explode('@', $clean_email)[1], 'MX')) ? filter_var($clean_email, FILTER_VALIDATE_EMAIL) : false;
    }

?>