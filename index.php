<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firewall Blocker - Modify/Delete</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 30px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
        }
        .tab-button {
            padding: 10px 25px;
            cursor: pointer;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 5px 5px 0 0;
            margin-right: 15px;
            transition: background-color 0.3s ease;
            font-weight: 500;
        }
        .tab-button:hover {
            background-color: #ebebeb;
        }
        .tab-button.active {
            background-color: #3f51b5;
            color: white;
        }
        .tab-content {
            display: none;
            padding: 20px;
        }
        .tab-content.active {
            display: block;
        }
        input[type="file"] {
            margin-top: 10px;
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 14px;
            font-family: 'Roboto', sans-serif;
            resize: vertical;
            height: 150px;
        }
        input[type="submit"] {
            background-color: #3f51b5;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #303f9f;
        }
        .message {
            margin-top: 20px;
            font-size: 16px;
            color: #28a745;
            text-align: center;
        }
        .list-container {
            margin-top: 20px;
        }
        .list-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .list-container th, .list-container td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .list-container td input {
            width: 70%;
        }
        .list-container td button {
            background-color: #e53935;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .list-container td button:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Firewall Blocker - Manage IPs & URLs</h1>

    <div class="tabs">
        <button class="tab-button active" onclick="openTab('ip-tab')">Block IP Addresses</button>
        <button class="tab-button" onclick="openTab('url-tab')">Block URLs</button>
    </div>

    <div id="ip-tab" class="tab-content active">
    <h2>Block IP Addresses</h2>
    <form action="block_addresses.php" method="POST" enctype="multipart/form-data">
        <label for="ip_addresses">Enter IP Addresses (comma-separated):</label>
        <textarea name="ip_addresses" id="ip_addresses" placeholder="e.g., 192.168.1.1, 203.0.113.5"></textarea>
        <label for="ip_file">Or upload a file (CSV or TXT):</label>
        <input type="file" name="ip_file" id="ip_file" accept=".txt, .csv">
        <input type="submit" value="Block IPs">
    </form>

        <div class="list-container">
            <h3>Current Blocked IP Addresses:</h3>
            <table id="ip-list">
                <?php
                $ip_file = 'ip_addresses.txt';
                if (file_exists($ip_file)) {
                    $ips = file($ip_file, FILE_IGNORE_NEW_LINES);
                    foreach ($ips as $ip) {
                        echo "<tr>
                                <td><input type='text' value='$ip' disabled></td>
                                <td><button onclick='deleteIp(\"$ip\")'>Delete</button></td>
                            </tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>

    <div id="url-tab" class="tab-content">
        <h2>Block URLs</h2>
        <form action="block_addresses.php" method="POST" enctype="multipart/form-data">
            <label for="urls">Enter URLs (comma-separated):</label>
            <textarea name="urls" id="urls" placeholder="e.g., https://example.com, http://test.com"></textarea>
            <label for="url_file">Or upload a file (CSV or TXT):</label>
            <input type="file" name="url_file" id="url_file" accept=".txt, .csv">
            <input type="submit" value="Block URLs">
        </form>


        <div class="list-container">
            <h3>Current Blocked URLs:</h3>
            <table id="url-list">
                <?php
                $url_file = 'urls.txt';
                if (file_exists($url_file)) {
                    $urls = file($url_file, FILE_IGNORE_NEW_LINES);
                    foreach ($urls as $url) {
                        echo "<tr>
                                <td><input type='text' value='$url' disabled></td>
                                <td><button onclick='deleteUrl(\"$url\")'>Delete</button></td>
                            </tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>

    <div class="message">
        <?php
        if (isset($_GET['success'])) {
            echo "Successfully blocked the IPs/URLs!";
        }
        ?>
    </div>
</div>

<script>
    function openTab(tabId) {
        let tabs = document.querySelectorAll('.tab-content');
        tabs.forEach(function(tab) {
            tab.classList.remove('active');
        });

        let buttons = document.querySelectorAll('.tab-button');
        buttons.forEach(function(button) {
            button.classList.remove('active');
        });

        document.getElementById(tabId).classList.add('active');
        event.target.classList.add('active');
    }

    function deleteIp(ip) {
        if (confirm('Are you sure you want to delete this IP address?')) {
            window.location.href = "block_addresses.php?delete_ip=" + encodeURIComponent(ip);
        }
    }

    function deleteUrl(url) {
        if (confirm('Are you sure you want to delete this URL?')) {
            window.location.href = "block_addresses.php?delete_url=" + encodeURIComponent(url);
        }
    }
</script>

</body>
</html>
