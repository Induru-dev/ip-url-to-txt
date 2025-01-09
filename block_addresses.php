<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['ip_addresses']) && !empty($_POST['ip_addresses'])) {
        $ip_addresses = $_POST['ip_addresses'];
        save_to_file('ip_addresses.txt', $ip_addresses);
        header("Location: index.php?success=true");
        exit();
    } elseif (isset($_FILES['ip_file']) && $_FILES['ip_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['ip_file']['tmp_name'];
        $fileType = mime_content_type($fileTmpPath);
        
        if (in_array($fileType, ['text/plain', 'text/csv'])) {
            $lines = file($fileTmpPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $validIPs = array_filter($lines, function ($line) {
                return filter_var(trim($line), FILTER_VALIDATE_IP);
            });

            save_to_file('ip_addresses.txt', implode(',', $validIPs));
            header("Location: index.php?success=true");
            exit();
        } else {
            echo "Invalid file format. Please upload a valid .csv or .txt file.";
        }
    } elseif (isset($_POST['urls']) && !empty($_POST['urls'])) {
        $urls = $_POST['urls'];
        save_to_file('urls.txt', $urls);
        header("Location: index.php?success=true");
        exit();
    } elseif (isset($_FILES['url_file']) && $_FILES['url_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['url_file']['tmp_name'];
        $fileType = mime_content_type($fileTmpPath);
        
        if (in_array($fileType, ['text/plain', 'text/csv'])) {
            $lines = file($fileTmpPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            save_to_file('urls.txt', implode(',', $lines));
            header("Location: index.php?success=true");
            exit();
        } else {
            echo "Invalid file format. Please upload a valid .csv or .txt file.";
        }
    } else {
        echo "Invalid input or file upload error.";
    }
}

if (isset($_GET['delete_ip'])) {
    $ip_to_delete = $_GET['delete_ip'];
    delete_from_file('ip_addresses.txt', $ip_to_delete);
    header("Location: index.php");
}

if (isset($_GET['delete_url'])) {
    $url_to_delete = $_GET['delete_url'];
    delete_from_file('urls.txt', $url_to_delete);
    header("Location: index.php");
}

function save_to_file($filename, $data) {
    $data = explode(',', $data);
    file_put_contents($filename, implode(PHP_EOL, array_map('trim', $data)) . PHP_EOL, FILE_APPEND);
}


function delete_from_file($filename, $value) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES);
    $lines = array_filter($lines, function ($line) use ($value) {
        return trim($line) !== trim($value);
    });
    file_put_contents($filename, implode(PHP_EOL, $lines) . PHP_EOL);
}
?>