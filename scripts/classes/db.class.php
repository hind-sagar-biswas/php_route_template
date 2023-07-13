<?php

/**
 * Class DataBase
 * 
 * A class for handling database operations.
 */
class DataBase
{
    /**
     * @var array $tables An associative array mapping table names to their corresponding names in the database.
     */
    protected $tables = [
        'user.login.data' => 'users',
        'user.login.token' => 'login_tokens',
    ];

    /**
     * @var string $dbhost The database host.
     */
    private $dbhost;

    /**
     * @var string $dbuser The database username.
     */
    private $dbuser;

    /**
     * @var string $dbpass The database password.
     */
    private $dbpass;

    /**
     * @var string $dbname The database name.
     */
    private $dbname;

    /**
     * @var mysqli $conn The database connection.
     */
    protected $conn;

    /**
     * Constructor method to initialize the database connection.
     */
    public function __construct()
    {
        $this->dbhost = $_ENV['DB_HOST'];
        $this->dbuser = $_ENV['DB_USERNAME'];
        $this->dbpass = $_ENV['DB_PASSWORD'];
        $this->dbname = $_ENV['DB_DATABASE'];

        $this->conn = $this->connect();
    }

    /**
     * Establishes a connection to the database using MySQLi.
     *
     * @return mysqli The database connection object.
     * @throws Exception If an error occurs during the connection.
     */
    protected function connect()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $mysqli = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
            $mysqli->set_charset("utf8mb4");
            return $mysqli;
        } catch (Exception $e) {
            dd("ERROR!: " . $e->getMessage());
        }
    }

    /**
     * Establishes a connection to the database using PDO.
     *
     * @param string $pdo_type The type of PDO connection.
     * @return PDO The database connection object.
     * @throws PDOException If an error occurs during the connection.
     */
    protected function connect_pdo($pdo_type = 'fetch')
    {
        try {
            $dsn = 'mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname;
            $pdo = new PDO($dsn, $this->dbuser, $this->dbpass);
            $pdo->query('SET NAMES utf8');
            $pdo->query('SET CHARACTER_SET utf8_unicode_ci');

            if ($pdo_type == 'put') {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } else {
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }

            return $pdo;
        } catch (PDOException $e) {
            print "ERROR!: " . $e->getMessage() . "<BR>";
            die();
        }
    }

    /**
     * Inserts data into a table in the database.
     *
     * @param string $table The table name.
     * @param array $values An associative array of column-value pairs.
     * @return mixed The last inserted entry if successful, otherwise false.
     */
    public function insert(string $table, array $values = [])
    {
        $columns = array_keys($values);
        $parsed_values = array_map(function ($value) {
            return "'" . $value . "'";
        }, $values);

        $column_clause = '`' . $this->tables[$table] . '` (' . implode(', ', $columns) . ')';
        $value_clause = '(' . implode(', ', $parsed_values) . ')';

        $query = "INSERT INTO $column_clause VALUES $value_clause;";

        if (!$this->conn->query($query)) return False;
        return $this->get_last_entry($table);
    }

    /**
     * Updates data in a table in the database.
     *
     * @param string $table The table name.
     * @param array $values An associative array of column-value pairs to update.
     * @param string $conditions The conditions to match for the update.
     * @return bool True if successful, otherwise false.
     */
    public function update(string $table, array $values, string $conditions)
    {
        if (!$this->entry_exists($table, $conditions)) return False;

        $table_clause = '`' . $this->tables[$table] . '`';
        $value_clause = flatenAssocArray($values);
        $condition_clause = $this->get_condition_clause($conditions);

        $query = "UPDATE $table_clause SET $value_clause WHERE $condition_clause;";

        if (!$this->conn->query($query)) return False;
        return True;
    }

    /**
     * Deletes data from a table in the database.
     *
     * @param string $table The table name.
     * @param string $conditions The conditions to match for the delete.
     * @return bool True if successful, otherwise false.
     */
    public function delete(string $table, string $conditions)
    {
        if (!$this->entry_exists($table, $conditions)) return False;
        
        $table_clause = '`' . $this->tables[$table] . '`';
        $condition_clause = $this->get_condition_clause($conditions);

        $query = "DELETE FROM $table_clause WHERE $condition_clause;";

        if (!$this->conn->query($query)) return False;
        return True;
    }

    /**
     * Retrieves an entry from a table in the database.
     *
     * @param string $table The table name.
     * @param mixed $value The value to match.
     * @param string $key The column to match.
     * @param array $selectors The columns to select.
     * @return mixed The fetched entry if found, otherwise false.
     */
    public function get_entry_by_key(string $table, $value, string $key = 'id', array $selectors = ['*'])
    {
        $select_clause = implode(', ', $selectors);
        $table_clause = '`' . $this->tables[$table] . '`';
        $condition_clause = "$key = '$value'";

        $query = "SELECT $select_clause FROM $table_clause WHERE $condition_clause;";
        return $this->get_entry($query);
    }

    /**
     * Retrieves an entry from a table in the database.
     *
     * @param string $table The table name.
     * @param string $conditions The conditions to match.
     * @param array $selectors The columns to select.
     * @return array The fetched entry if found, otherwise an empty array.
     */
    public function get_entry_by_condition(string $table, string $conditions, array $selectors = ['*'])
    {
        $select_clause = implode(', ', $selectors);
        $table_clause = '`' . $this->tables[$table] . '`';
        $condition_clause = $this->get_condition_clause($conditions);

        $query = "SELECT $select_clause FROM $table_clause WHERE $condition_clause;";
        return $this->get_entry($query);
    }

    /**
     * Retrieves a list of entries from a table in the database.
     *
     * @param string $table The table name.
     * @param string $conditions The conditions to match.
     * @param array $selectors The columns to select.
     * @return array The fetched entries if found, otherwise an empty array.
     */
    public function get_entry_list(string $table, string $conditions, array $selectors = ['*'])
    {
        $select_clause = implode(', ', $selectors);
        $table_clause = '`' . $this->tables[$table] . '`';
        $condition_clause = $this->get_condition_clause($conditions);

        $query = "SELECT $select_clause FROM $table_clause WHERE $condition_clause;";
        return $this->get_entry($query, true);
    }

    /**
     * Retrieves the last entry from a table in the database.
     *
     * @param string $table The table name.
     * @param string $key The column to order by.
     * @param array $selectors The columns to select.
     * @return mixed The last fetched entry if found, otherwise false.
     */
    public function get_last_entry(string $table, string $key = 'id', array $selectors = ['*'])
    {
        $select_clause = implode(', ', $selectors);
        $table_clause = '`' . $this->tables[$table] . '`';
        $query = "SELECT $select_clause FROM `$table_clause` ORDER BY $key DESC LIMIT 1;";

        $entry = mysqli_fetch_assoc(mysqli_query($this->conn, $query));
        return $entry;
    }

    /**
     * Checks if an entry exists in a table in the database.
     *
     * @param string $table The table name.
     * @param string $conditions The conditions to match.
     * @return bool True if the entry exists, otherwise false.
     */
    public function entry_exists(string $table, string $conditions)
    {
        $table_clause = '`' . $this->tables[$table] . '`';
        $condition_clause = $this->get_condition_clause($conditions);

        $query = "SELECT id FROM $table_clause WHERE $condition_clause LIMIT 1;";
        return ($this->get_entry($query)) ? true : false;
    }

    /**
     * Retrieves an entry or a list of entries from the database based on the provided query.
     *
     * @param string $query The SQL query.
     * @param bool $multiple Indicates whether to fetch multiple entries.
     * @return mixed The fetched entry or list of entries if found, otherwise false.
     */
    protected function get_entry($query, $multiple = false)
    {
        if ($multiple) {
            $result = [];

            if ($queried_result = mysqli_query($this->conn, $query)) {
                while ($fetched = mysqli_fetch_assoc($queried_result)) {
                    array_push($result, $fetched);
                }
            }

            return $result;
        }
        return mysqli_fetch_assoc(mysqli_query($this->conn, $query));
    }

    /**
     * Replaces logical operators && with AND, || with OR, and ! with NOT in a condition string.
     *
     * @param string $conditions The condition string.
     * @return string The modified condition string.
     */
    protected function get_condition_clause(string $conditions)
    {
        return str_replace(["&&", "||", "!"], ["AND", "OR", "NOT"], $conditions);
    }
}
