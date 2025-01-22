<?php
session_start(); // Start the session to access the logged-in user's details

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle adding IP addresses manually
    if (isset($_POST['ip_addresses']) && !empty($_POST['ip_addresses'])) {
        $ip_addresses = $_POST['ip_addresses'];
        $username = $_SESSION['username'] ?? 'unknown_user'; // Get username from the session
        save_to_file_with_user('ip_addresses.txt', 'ip_users.txt', $ip_addresses, $username);
        header("Location: index.php?success=true");
        exit();
    } 
    
    // Handle uploading IP file
    elseif (isset($_FILES['ip_file']) && $_FILES['ip_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['ip_file']['tmp_name'];
        $fileType = mime_content_type($fileTmpPath);

        // Check for valid file types
        if (in_array($fileType, ['text/plain', 'text/csv'])) {
            // Read lines from the file
            $lines = file($fileTmpPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Filter only valid IPs
            $validIPs = [];
            foreach ($lines as $line) {
                $line = trim($line); // Remove whitespace
                if (filter_var($line, FILTER_VALIDATE_IP)) {
                    $validIPs[] = $line; // Add valid IPs to the array
                }
            }

            if (!empty($validIPs)) {
                $username = $_SESSION['username'] ?? 'unknown_user'; // Get username from the session
                // Save only valid IPs to both files
                save_to_file_with_user('ip_addresses.txt', 'ip_users.txt', implode(',', $validIPs), $username);
                header("Location: index.php?success=true");
                exit();
            } else {
                // Handle case where no valid IPs were found
                echo "No valid IPs found in the uploaded file.";
            }
        } else {
            // Handle invalid file type
            echo "Invalid file format. Please upload a valid .csv or .txt file.";
        }
    } 
    
    // Handle adding URLs manually
    elseif (isset($_POST['urls']) && !empty($_POST['urls'])) {
        $urls = $_POST['urls'];
        $username = $_SESSION['username'] ?? 'unknown_user'; // Get username from the session
        save_to_file_with_user('urls.txt', 'url_users.txt', $urls, $username);
        header("Location: index.php?success=true");
        exit();
    } 
    
    // Handle uploading URL file
    elseif (isset($_FILES['url_file']) && $_FILES['url_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['url_file']['tmp_name'];
        $fileType = mime_content_type($fileTmpPath);

        if (in_array($fileType, ['text/plain', 'text/csv'])) {
            $lines = file($fileTmpPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $username = $_SESSION['username'] ?? 'unknown_user'; // Get username from the session
            save_to_file_with_user('urls.txt', 'url_users.txt', implode(',', $lines), $username);
            header("Location: index.php?success=true");
            exit();
        } else {
            echo "Invalid file format. Please upload a valid .csv or .txt file.";
        }
    } else {
        echo "Invalid input or file upload error.";
    }
}

// Handle deleting IP addresses
if (isset($_GET['delete_ip'])) {
    $ip_to_delete = $_GET['delete_ip'];
    delete_from_files('ip_addresses.txt', 'ip_users.txt', $ip_to_delete);
    echo "IP {$ip_to_delete} deleted successfully.";
    header("Location: index.php");
    exit();
}

// Handle deleting URLs
if (isset($_GET['delete_url'])) {
    $url_to_delete = $_GET['delete_url'];
    delete_from_files('urls.txt', 'url_users.txt', $url_to_delete);
    echo "URL {$url_to_delete} deleted successfully.";
    header("Location: index.php");
    exit();
}

// Save IP/URL to both the main file and user tracking file
function save_to_file_with_user($dataFile, $userFile, $data, $username) {
    $entries = explode(',', $data);
    foreach ($entries as $entry) {
        $entry = trim($entry);
        // Append to main data file
        file_put_contents($dataFile, $entry . PHP_EOL, FILE_APPEND);
        // Append to user tracking file with username
        file_put_contents($userFile, "$entry, $username" . PHP_EOL, FILE_APPEND);
    }
}

// Delete IP/URL from both the main file and user tracking file
function delete_from_files($dataFile, $userFile, $value) {
    // Remove from main data file
    $lines = file($dataFile, FILE_IGNORE_NEW_LINES);
    $lines = array_filter($lines, function ($line) use ($value) {
        return trim($line) !== trim($value);
    });
    file_put_contents($dataFile, implode(PHP_EOL, $lines) . PHP_EOL);

    // Remove from user tracking file
    $lines = file($userFile, FILE_IGNORE_NEW_LINES);
    $lines = array_filter($lines, function ($line) use ($value) {
        return !str_starts_with($line, trim($value) . ',');
    });
    file_put_contents($userFile, implode(PHP_EOL, $lines) . PHP_EOL);
}
?>
