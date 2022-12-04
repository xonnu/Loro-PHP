<?php
/**
 *  2022 - DEV
 *  Developer: Justin Pascual
 *  GitHub: https://github.com/devkurono
 *  License: MIT
 */

declare(strict_types=1);

use JetBrains\PhpStorm\NoReturn;

class Loro
{
    public string $sql_statement = '';
    protected string $table_name;

    /**
     * Initialize your database connection
     * @param object $database_connection
     */
    public function __construct(protected object $database_connection)
    {

    }

    /**
     * Set database table name;
     * @param string $table_name
     * @return string
     */
    public function tableName(string $table_name = ''): string
    {
        return $this->table_name = $table_name;
    }

    /**
     * Create `INSERT` query statement
     * @param array $array_data
     * @return Loro
     */
    public function insertQuery(array $array_data = []): Loro
    {
        $clean_array_data = $this->sanitizeArray($array_data);
        $column_names = implode(', ', array_keys($clean_array_data));
        $column_values = implode("', '", array_values($clean_array_data));
        $this->sql_statement = "INSERT INTO $this->table_name($column_names) VALUES('$column_values')";
        return $this;
    }

    /**
     * This avoids malicious code to be executed.
     * @param array $array_of_data
     * @return array
     */
    public function sanitizeArray(array $array_of_data = []): array
    {
        foreach ($array_of_data as $column_key => $column_value) {
            $sanitized_column_key = $this->sanitize($column_key);
            $sanitized_column_value = $this->sanitize($column_value);
            $array_of_data[$sanitized_column_key] = $sanitized_column_value;
        }

        return $array_of_data;
    }

    /**
     * This avoids malicious code to be executed.
     * @param string $data
     * @return string
     */
    public function sanitize(string $data = ''): string
    {
        $filtered_data = htmlentities(preg_replace('/<[^>]*>/', '*', $data));
        return $this->database_connection->real_escape_string($filtered_data);
    }

    /**
     * Generate UPDATE SQL statement
     * @param $update_data_array
     * @param array $where
     * @return $this
     */
    public function updateQuery($update_data_array, array $where = []): Loro
    {
        $where_array_length = count($where);

        if ($where_array_length === 0) {
            die("Loro error: Second parameter 'where' cannot be empty");
        }

        if ($where_array_length !== 1) {
            die("Loro error: Please use only one key and one value");
        }

        $update_data_statement = [];

        foreach ($update_data_array as $array_key => $array_value) {
            $array_key_clean = $this->sanitize($array_key);
            $array_value_clean = $this->sanitize($array_value);
            $update_data_statement[] = "$array_key_clean = '$array_value_clean'";
        }


        $update_data = implode(', ', $update_data_statement);
        $table_column = key($where);
        $table_column_value = $where[$table_column];

        $this->sql_statement = "UPDATE $this->table_name SET $update_data WHERE $table_column = '$table_column_value'";
        return $this;
    }

    /**
     * Count row of specific table
     * @param string $table_name
     * @return int
     */
    public function rowCount(string $table_name = ''): int
    {
        if ($table_name !== '') {
            $this->table_name = $table_name;
        }

        $result = $this->execute("SELECT * FROM $this->table_name");

        return $result->num_rows;
    }

    /**
     * Execute your query statement
     * @param string $query_statement
     * @return bool|object
     */
    public function execute(string $query_statement = ''): bool|object
    {
        if ($query_statement === '') {
            $query_statement = $this->sql_statement;
        }

        return $this->database_connection->query($query_statement);
    }

    /**
     * Generate user ID
     * @return string
     */
    public function uid(): string
    {
        return uniqid('LORO_' . rand(1, 999));
    }

    /**
     * Checks if you are connected to the database.
     * Remove or comment this method after using.
     * @return void
     */
    #[NoReturn] public function is_connected(): void
    {
        if ($this->database_connection->connect_error) {
            printf($this->errorMessage(), $this->database_connection->connect_error);
            die();
        }

        printf("Connect success: <strong>%s</strong>", "Database is connected");
        die();
    }

    /**
     * Gives styled error message
     * @return string
     */
    private function errorMessage(): string
    {
        return "<div style='font-family: sans-serif;font-size: 14px;position: fixed;top: 24px;right: 16px;padding-inline: 16px;padding-block: 8px;border-radius: 8px;color:white;background-color: red;width: max-content;'>Connect failed: <strong>%s</strong></div>";
    }

    public function __destruct()
    {
        $this->database_connection->close();
    }

}