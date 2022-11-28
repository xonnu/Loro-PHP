<?php

use JetBrains\PhpStorm\NoReturn;

class Loro
{
    protected string $table_name;

    /**
     * Initialize your database connection
     * @param object $database_connection
     */
    public function __construct(protected object $database_connection)
    {

    }

    public function tableName($table_name = '')
    {
        return $this->table_name = $table_name;
    }

    /**
     * Create `INSERT` query statement
     * @param array $array_data
     * @return string
     */
    public function insertQuery(array $array_data = []): string
    {
        $clean_array_data = $this->sanitizeArray($array_data);
        $column_names = implode(', ', array_keys($clean_array_data));
        $column_values = implode("', '", array_values($clean_array_data));
        return "INSERT INTO $this->table_name($column_names) VALUES('$column_values')";
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

$database_connection = new mysqli('localhost', 'root', '', 'chrono-crud-example');

$loro = new Loro($database_connection);
//$loro->is_connected();
$loro->tableName('account');
$insert_query = $loro->insertQuery([
    "user_id" => 1,
    "username" => 'Kurono',
    "email" => 'kurono@gmail.com',
    "password" => password_hash('password', PASSWORD_DEFAULT)
]);

echo $insert_query;


