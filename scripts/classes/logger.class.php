<?php

class Logger extends Token
{
    public function login(string $nameOrMail, string $password, bool $rememberMe = false): array
    {
        if (empty($nameOrMail)) return [False, 'Error: input can\'t be empty!'];

        $user = $this->find_user_by_username($nameOrMail);

        // if user found, check the password
        if ($user && md5($password) == $user['password']) {
            $this->session_login($user);
            if ($rememberMe) $this->remember_me($user['id']);
            return [true, 'Login Successful!'];
        }

        return [false, 'Login Failed'];
    }

    /**
     * log a user in the session
     * @param array $user
     * @return bool
     */
    public function session_login(array $user): bool
    {
        // prevent session fixation attack
        if (session_regenerate_id()) {
            // set username & id in the session
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            return true;
        }

        return false;
    }


    /**
     * checks if the user is already logged in
     * @param null
     * @return bool
     */
    public function is_logged_in()
    {
        // check the session
        if (isset($_SESSION['username'])) {
            return true;
        }

        // check the remember_me in cookie
        $token = (isset($_COOKIE['remember_me'])) ? $_COOKIE['remember_me'] : 0 ;
        if ($token && $this->validate_token($token)) {
            $user = $this->find_user_by_token($token);
            if ($user) return $this->session_login($user);
        }
        return false;
    }


    private function remember_me(int $user_id, int $day = 30): void
    {
        [$selector, $validator, $token] = $this->generate_tokens();

        // remove all existing token associated with the user id
        $this->delete_user_token($user_id);
        // set expiration date
        $expired_seconds = time() + 60 * 60 * 24 * $day;

        // insert a token to the database
        $expiry = date('Y-m-d H:i:s', $expired_seconds);

        if ($this->insert_user_token($user_id, $selector, $validator, $expiry)) set_cookie('remember_me', $token, $expired_seconds);
    }


    public function logout(): bool
    {
        if ($this->is_logged_in()) {
            // delete the user token
            $this->delete_user_token($_SESSION['user_id']);

            // delete session
            unset($_SESSION['username'], $_SESSION['user_id`']);

            // remove the remember_me cookie
            if (isset($_COOKIE['remember_me'])) {
                unset($_COOKIE['remember_me']);
                set_cookie('remember_me', null, -1);
            }

            // remove all session data
            session_destroy();

            return True;
        } return False;
    }


    public function register(array $user): array
    {
        if (!preg_match('/^[a-zA-Z][0-9a-zA-Z_]{2,23}[0-9a-zA-Z]$/', $user['username'])) return [False, 'Error: invalid username!'];
        if ($user['password'] !== $user['verify_password']) return [False, 'Error: passwords does not match!'];

        // Check if remember me enabled
        if (array_key_exists("remember_me", $user)) {
            if ($user['remember_me'])  $rememberMe = True;
        }
        $rememberMe = False;

        unset($user['verify_password']);
        unset($user['remember_me']);

        // ADD new user
        $new_user = $this->add_new_user($user);

        if (!$new_user) return [False, 'Error: error adding new user'];

        // LOGIN new user
        $login  = $this->login($new_user['username'], $user['password'], $rememberMe);
        if (!$login[0]) return [True, 'User successfully registered! Try login now...'];
        return [True, 'User successfully registered! logging in...' . $login[1]];
    }
}
