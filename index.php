<?php
session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli('localhost', 'root', '', 'users_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = $conn->real_escape_string($_POST['user_id']);
    $password = $conn->real_escape_string($_POST['password']);

    $result = $conn->query("SELECT * FROM users WHERE user_id='$user_id'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user_id;
            if (isset($_POST['login'])) {
                header('Location: main.php');
                exit();
            } elseif (isset($_POST['register'])) {
                header('Location: register.php');
                exit();
            }
        } else {
            $message = 'Invalid password.';
        }
    } else {
        $message = 'User ID not found.';
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        form { margin: 20px auto; width: 300px; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        input { margin: 10px 0; padding: 10px; width: calc(100% - 20px); }
        button { padding: 10px; width: 100%; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Login Page</h1>
    <form method="post">
        <input type="text" name="user_id" placeholder="User ID" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
        <button type="submit" name="register">Register New User</button>
        <?php if ($message): ?>
            <p class="error"><?= $message ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
