<?php

class User extends DataBase
{
    protected $userTableName = 'user.login.data';
    protected $userTable;

    public function __construct()
    {
        parent::__construct();
        $this->userTable = $this->tables[$this->userTableName];
    }

    protected function create_user_table()
    {
        $sql = "CREATE TABLE " . $this->userTable . " (
                    `id` INT(11) NOT NULL AUTO_INCREMENT , 
                    `username` VARCHAR(225)                             NOT NULL , 
                    `email`    VARCHAR(225)                             NOT NULL , 
                    `password` VARCHAR(225)                             NOT NULL , 
                    `create_time` DATETIME                              NOT NULL DEFAULT CURRENT_TIMESTAMP , 
                    `update_time` DATETIME  on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
                        PRIMARY KEY (`id`), 
                        UNIQUE `username` (`username`), 
                        UNIQUE `EMAIL` (`email`)) 
                            ENGINE = InnoDB;";

        if ($this->conn->query($sql) == TRUE) return True;
        return False;
    }

    protected function find_user_by_username(string $nameOrMail)
    {
        return $this->get_entry_by_condition($this->userTableName, "username = $nameOrMail || emaiil = $nameOrMail");
    }

    protected function find_user_by_id(int $uid)
    {
        return $this->get_entry_by_key($this->userTableName, $uid);
    }

    protected function add_new_user($user)
    {
        if (empty($user['username'])) return [False, 'Error: username can\'t be empty!'];
        if (empty($user['email'])) return [False, 'Error: email can\'t be empty!'];
        if (empty($user['password'])) return [False, 'Error: password can\'t be empty!'];

        if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) return [False, 'Error: invalid email!'];

        if ($this->check_user_exists($user['username'], 'username')) return [False, 'Error: username already taken!'];
        if ($this->check_user_exists($user['email'], 'email')) return [False, 'Error: email already taken!'];

        $user['password'] = md5($user['password']);
        return $this->insert($this->userTableName, $user);
    }

    protected function delete_user_by_id(int $uid)
    {
        $this->delete($this->userTableName, "id = '$uid'");
    }

    protected function check_user_exists(string $value, string $col): bool
    {
        return $this->entry_exists($this->userTableName, "$col = '$value'");
    }
};
