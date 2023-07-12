<?php

class DataBase
{
    protected $tables = [
        'login' => 'login_table'
    ];

    private $dbhost;
    private $dbuser;
    private $dbpass;
    private $dbname;
    protected $conn;

    public function __construct()
    {
        $this->dbhost = $_ENV['DB_HOST'];
        $this->dbuser = $_ENV['DB_USERNAME'];
        $this->dbpass = $_ENV['DB_PASSWORD'];
        $this->dbname = $_ENV['DB_DATABASE'];

        $this->conn = $this->connect();
    }

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

    public function insert(string $table, array $values = [])
    {
        $columns =  array_keys($values);
        $parsed_values = [];

        foreach ($values as $key => $value) {
            $parsed_values[$key] = "'$value'";
        }

        $column_clause = '`' . $this->tables[$table] . '` (' . implode(', ', $columns) . ')';
        $value_clause = '(' . implode(', ', $parsed_values) . ')';

        $query = "INSERT INTO $column_clause VALUES $value_clause;";

        if (!$this->conn->query($query)) return False;
        return $this->get_last_entry($table);
    }

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

    public function delete(string $table, string $conditions)
    {
        if (!$this->entry_exists($table, $conditions)) return False;
        
        $table_clause = '`' . $this->tables[$table] . '`';
        $condition_clause = $this->get_condition_clause($conditions);

        $query = "DELETE FROM $table_clause WHERE $condition_clause;";

        if (!$this->conn->query($query)) return False;
        return True;
    }

    public function get_entry_by_key(string $table, $value, string $key ='id', array $selectors = ['*'])
    {
        $select_clause = implode(', ', $selectors);
        $table_clause = '`' . $this->tables[$table] . '`';
        $condition_clause = "$key = '$value'";

        $query = "SELECT $select_clause FROM $table_clause WHERE $condition_clause;";
        return $this->get_entry($query);
    }

    public function get_entry_list(string $table, string $conditions, array $selectors = ['*'])
    {
        $select_clause = implode(', ', $selectors);
        $table_clause = '`' . $this->tables[$table] . '`';
        $condition_clause = $this->get_condition_clause($conditions);

        $query = "SELECT $select_clause FROM $table_clause WHERE $condition_clause;";
        return $this->get_entry($query, true);
    }

    public function get_last_entry(string $table, string $key = 'id', array $selectors = ['*'])
    {
        $select_clause = implode(', ', $selectors);
        $table_clause = '`' . $this->tables[$table] . '`';
        $query = "SELECT $select_clause FROM `$table_clause` ORDER BY $key DESC LIMIT 1;";

        $entry = mysqli_fetch_assoc(mysqli_query($this->conn, $query));
        return $entry;
    }

    public function entry_exists(string $table, string $conditions)
    {
        $table_clause = '`' . $this->tables[$table] . '`';
        $condition_clause = $this->get_condition_clause($conditions);

        $query = "SELECT id FROM $table_clause WHERE $condition_clause LIMIT 1;";
        return ($this->get_entry($query)) ? true : false;
    }

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

    protected function get_condition_clause(string $conditions)
    {
        return str_replace(["&&", "||", "!"], ["AND", "OR", "NOT"], $conditions);
    }
}
