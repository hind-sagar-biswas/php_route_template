<?php
// require __DIR__ . '/../../config.php';

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//     if($DEBUG) var_dump($_POST);

//     // Check type of action
//     if (isset($_POST['logger-type'])) $type = $_POST['logger-type'];
//     else $type = 'login';

//     if($type == 'logout') {
//         $logger->logout();
//         $message = urlencode('Logged out successfully!');
//         redirect_to('root', "t=1&m=$message");
//     }

//     // Check remember me
//     if (isset($_POST['remember-me'])) {
//         if ($_POST['remember-me'] == 'false') $rememberMe = false;
//         else $rememberMe = true;
//     }
//     else $rememberMe = true;

//     // If register user
//     if ($type == 'register') {
//         $username = $_POST['username'];
//         $email = $_POST['email'];
//         $password = $_POST['password'];
//         $rePassword = $_POST['re-password'];

//         if ($password != $rePassword) redirect_to('register', "username=" . urlencode($username) . "&email=" . urlencode($email) . "&t=0&m=Retyped%20password%20does%20match");

//         $user = [
//             'username' => trim($username),
//             'email' => trim($email),
//             'password' => $password,
//             'rememberMe' => $rememberMe
//         ];

//         // Initiate registration
//         $register = $logger->register($user);
//         $message = urlencode($register[1]);

//         // Redirect with message
//         if ($register[0]) redirect_to('register', "t=0&m=$message");
//         redirect_to('root', "t=1&m=$message");
//     }

//     // LOGIN PART
//     // 
//     // Check targetpage of action
//     if (isset($_POST['redirect-to'])) $redirectTarget = $_POST['redirect-to'];
//     else $redirectTarget = 'root';

//     $login = $logger->login(trim($_POST['name-or-mail']), $_POST['password'], $rememberMe);
//     $message = urldecode($login[1]);

//     if ($login) redirect_to($redirectTarget, "t=1&m=$message");
//     redirect_to($redirectTarget, "t=0&m=$message");

// }
// else redirect_to('root');
