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
    <title>User Registration</title>
    <style>
        /* Modern CSS Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #6a11cb 0%,rgb(6, 52, 133) 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .registration-container {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.125);
        }

        .registration-title {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.25rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.25);
        }

        .submit-btn, .back-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn {
            background-color: #FF4C4C;
            color: white;
            margin-bottom: 15px;
        }

        .submit-btn:hover {
            background-color: #e63946;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        

        .back-btn {
            background-color: #2575fc;
            color: white;
        }

        .back-btn:hover {
            background-color: #1e88e5;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .message {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }

        .success-message {
            color: #38C172;
        }

        .error-message {
            color: #FF6347;
        }

        @media (max-width: 480px) {
            .registration-container {
                padding: 25px;
                width: 95%;
            }

            .registration-title {
                font-size: 1.8rem;
            }

            .form-input, .submit-btn, .back-btn {
                font-size: 0.9rem;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h1 class="registration-title">Register New User</h1>
        
        <form method="post">
            <input type="text" name="user_id" class="form-input" placeholder="New User ID" required>
            <input type="password" name="password" class="form-input" placeholder="New Password" required>
            <input type="text" name="name" class="form-input" placeholder="Full Name" required>
            <input type="email" name="email" class="form-input" placeholder="Email" required>
            
            <button type="submit" class="submit-btn">Register</button>

                <?php if ($message): ?>
                <p><?= $message ?></p>
                <?php endif; ?>
            
            <a href="index.php" style="text-decoration: none;">
                <button type="button" class="back-btn">Back to Login</button>
            </a>
        </form>

        <?php if (!empty($message)): ?>
        <p class="<?= strpos($message, 'successful') !== false ? 'message' : 'error' ?>"><?= $message ?></p>
        <?php endif; ?>

    </div>
</body>
</html>