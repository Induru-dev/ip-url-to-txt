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
    <style>
                /* General reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styling */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc); /* Gradient background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
            text-align: center;
        }

        /* Container for the form */
        form {
            background-color: rgba(255, 255, 255, 0.2); /* Semi-transparent white background */
            border-radius: 10px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        /* Heading styling */
        h1 {
            h1 {
            font-size: 2.5rem;
            margin-bottom: 70px;
            text-align: center; /* Add this line to center the text */
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
}
        }

        /* Input fields styling */
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            background-color: #f1f1f1;
            color: #333;
            transition: all 0.3s ease;
        }

        /* Input focus effect */
        input:focus {
            outline: none;
            background-color: #e1e1e1;
            box-shadow: 0 0 10px rgba(38, 194, 129, 0.5); /* Green focus glow */
        }

        /* Submit button styling */
        button {
            width: 100%;
            padding: 14px;
            background-color: #FF4C4C; /* Red background */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        /* Button hover effect */
        button:hover {
            background-color: #FF1F1F;
            transform: scale(1.05);
        }

        /* Message styling (successful or error) */
        .message {
            color: #38C172;
            font-weight: bold;
            margin-top: 10px;
        }

        .error {
            color: #FF6347;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Back to login button styling */
        form[action="index.php"] {
            margin-top: 500px;
            
        }

        form[action="index.php"] button {
            background-color: #2575fc; /* Blue background */
            font-size: 1rem;
            padding: 12px;
            
        }

        form[action="index.php"] button:hover {
            background-color: #3c8ce7;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            h1 {
                font-size: 2rem;
            }

            form {
                padding: 30px;
                width: 90%;
            }

            input, button {
                font-size: 1rem;
            }
        }

    </style>
    <form method="post">
        <h1>Register New User</h1>
    
        <input type="text" name="user_id" placeholder="New User ID" required>
        <input type="password" name="password" placeholder="New Password" required>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Register</button>
        <?php if ($message): ?>
            <p><?= $message ?></p>
        <?php endif; ?>

        <a href="index.php" style="text-decoration: none;">
        <button type="button" style="padding: 12px 20px; background-color: #2575fc; color: white; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer; transition: background-color 0.3s ease;">
            Back to Login
        </button>
        </a>
        
    </form>
    
    <?php if (!empty($message)): ?>
        <p class="<?= strpos($message, 'successful') !== false ? 'message' : 'error' ?>"><?= $message ?></p>
    <?php endif; ?>
    
    
    

</body>
</html>
