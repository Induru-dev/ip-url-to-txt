<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli('localhost', 'root', '', 'users_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = $conn->real_escape_string($_POST['user_id']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);

    $result = $conn->query("SELECT * FROM users WHERE user_id='$user_id'");

    if ($result->num_rows > 0) {
        $message = 'User ID already exists.';
    } else {
        $conn->query("INSERT INTO users (user_id, password, name, email) VALUES ('$user_id', '$password', '$name', '$email')");
        $message = 'Registration successful!';
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New User</title>
</head>
<body>
    <h1>Register New User</h1>
    <form method="post">
        <input type="text" name="user_id" placeholder="New User ID" required>
        <input type="password" name="password" placeholder="New Password" required>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Register</button>
        <?php if ($message): ?>
            <p><?= $message ?></p>
        <?php endif; ?>
    </form>
    
    <?php if (!empty($message)): ?>
        <p class="<?= strpos($message, 'successful') !== false ? 'message' : 'error' ?>"><?= $message ?></p>
    <?php endif; ?>
    <!-- Back to Login Button -->
    <form action="index.php" method="get">
        <button type="submit">Back to Login</button>
    </form>

</body>
</html>
