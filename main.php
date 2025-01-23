<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPGuard</title>
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

        body {
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            justify-content: center;
            align-items: center;
        }



    </style>
</head>
<body>

<div class="container">
    <h1>IPGuard</h1>

    <div class="tabs">
        <button class="tab-button active" onclick="openTab('ip-tab',event)">Block IP Addresses</button>
        <button class="tab-button" onclick="openTab('url-tab',event)">Block URLs</button>
    </div>

    <div id="ip-tab" class="tab-content active">
    <h2>Block IP Addresses</h2>
    <form id="block-ip-form" action="block_addresses.php" method="POST" enctype="multipart/form-data">
        <label for="ip_addresses">Enter IP Addresses (comma-separated):</label>
        <textarea name="ip_addresses" id="ip_addresses" placeholder="e.g., 192.168.1.1, 203.0.113.5"></textarea>
        <label for="ip_file">Or upload a file (CSV or TXT):</label>
        <input type="file" name="ip_file" id="ip_file" accept=".txt, .csv">
        <input type="submit" value="Block IPs">
    </form>

        <div class="list-container">
            <h3>Current Blocked IP Addresses:</h3>
            <table id="ip-list">
                <thead>                    
                    <tr>
                        <th>IP Address</th>
                        <th>Username</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ip_user_file = 'ip_users.txt'; // File containing IPs and usernames
                    if (file_exists($ip_user_file)) {
                        // Read the file line by line
                        $entries = file($ip_user_file, FILE_IGNORE_NEW_LINES);
                        foreach ($entries as $entry) {
                            // Split each line by comma to separate IP and username
                            list($ip, $username) = array_map('trim', explode(',', $entry));
                            echo "<tr id=\"ip-row-$ip\">
                                    <td><input type='text' value='$ip' disabled></td>
                                    <td><input type='text' value='$username' disabled></td>
                                    <td><button onclick='deleteIp(\"$ip\")'>Delete</button></td>
                                </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <form action="logout.php" method="post">
        <button type="submit">Log Out</button>
        </form>
    </div>

    <div id="url-tab" class="tab-content">
        <h2>Block URLs</h2>
        <form id="block-url-form" action="block_addresses.php" method="POST" enctype="multipart/form-data">
            <label for="urls">Enter URLs (comma-separated):</label>
            <textarea name="urls" id="urls" placeholder="e.g., example.com, test.com"></textarea>
            <label for="url_file">Or upload a file (CSV or TXT):</label>
            <input type="file" name="url_file" id="url_file" accept=".txt, .csv">
            <input type="submit" value="Block URLs">
        </form>


        <div class="list-container">
            <h3>Current Blocked URLs:</h3>
            <table id="url-list">
            <thead>
                <tr>
                    <th>URL</th>
                    <th>Username</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $url_user_file = 'url_users.txt'; // File containing URLs and usernames
                if (file_exists($url_user_file)) {
                    // Read the file line by line
                    $entries = file($url_user_file, FILE_IGNORE_NEW_LINES);
                    foreach ($entries as $entry) {
                        // Split each line by comma to separate URL and username
                        list($url, $username) = array_map('trim', explode(',', $entry));
                        echo "<tr id=\"url-row-$url\">
                                <td><input type='text' value='$url' disabled></td>
                                <td><input type='text' value='$username' disabled></td>
                                <td><button onclick='deleteUrl(\"$url\")'>Delete</button></td>
                            </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
        </div>

        <form action="logout.php" method="post">
        <button type="submit">Log Out</button>
        </form>

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

    

    function openTab(tabId, event) {
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
            // Perform the AJAX request to delete the IP
            const url = `block_addresses.php?delete_ip=${encodeURIComponent(ip)}`;
            
            fetch(url, {
                method: 'GET',  // Using GET to send the deletion request
            })
            .then(response => response.text())
            .then(data => {
                // You could display a success message or dynamically update your page here.
                alert("IP deleted successfully!");
                console.log(data);  // Log the response (could show a success message)

                // Example: Optionally remove the element from the page (update UI immediately)
                const ipRow = document.getElementById(`ip-row-${ip}`);
                if (ipRow) {
                    ipRow.remove();  // Remove the row of IP immediately from the DOM
                }
            })
            .catch(error => {
                alert("Failed to delete IP. Please try again.");
                console.error("Error:", error);
            });
        }
    }

    function deleteUrl(url) {
        if (confirm('Are you sure you want to delete this URL?')) {
            // Perform the AJAX request to delete the URL
            const deleteUrl = `block_addresses.php?delete_url=${encodeURIComponent(url)}`;
            
            fetch(deleteUrl, {
                method: 'GET',  // Using GET to send the deletion request
            })
            .then(response => response.text())
            .then(data => {
                // You could display a success message or dynamically update your page here.
                alert("URL deleted successfully!");
                console.log(data);  // Log the response (could show a success message)

                // Example: Optionally remove the element from the page (update UI immediately)
                const urlRow = document.getElementById(`url-row-${url}`);
                if (urlRow) {
                    urlRow.remove();  // Remove the URL row immediately from the DOM
                }
            })
            .catch(error => {
                alert("Failed to delete URL. Please try again.");
                console.error("Error:", error);
            });
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
    const ipForm = document.getElementById("block-ip-form");
    const urlForm = document.getElementById("block-url-form");

    // Handle IP blocking form submission
    ipForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = new FormData(ipForm); // Collect form data
        

        fetch("block_addresses.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.text()) // Read response text
        .then(data => {
            alert("IPs blocked successfully.");
            console.log(data); // Optional: Show response for debugging
            // Optionally update the page without reloading

            // Update the IP list dynamically
            const ipList = document.getElementById("ip-list");
            const newIps = formData.get("ip_addresses").split(",").map(ip => ip.trim()).filter(ip => ip);
            const username = "<?= $_SESSION['username'] ?? 'unknown_user' ?>";
            newIps.forEach(ip => {
                const newRow = document.createElement("tr");
                newRow.id = `ip-row-${ip}`;
                newRow.innerHTML = `
                    <td><input type='text' value='${ip}' disabled></td>
                    <td><input type='text' value='${username}' disabled></td>
                    <td><button onclick='deleteIp("${ip}")'>Delete</button></td>
                `;
                ipList.appendChild(newRow);
            });

            // Handle file upload
            const ipFile = formData.get("ip_file");
            if (ipFile && ipFile.size > 0) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const fileContent = e.target.result;
                    const ipsFromFile = fileContent.split(/\r?\n/).map(ip => ip.trim()).filter(ip => ip);
                    ipsFromFile.forEach(ip => {
                        const newRow = document.createElement("tr");
                        newRow.id = `ip-row-${ip}`;

                        newRow.innerHTML = `
                            <td><input type='text' value='${ip}' disabled></td>
                            <td><input type='text' value='${username}' disabled></td>
                            <td><button onclick='deleteIp("${ip}")'>Delete</button></td>
                        `;
                        ipList.appendChild(newRow);
                    });
                };
                reader.readAsText(ipFile);
            }
            ipForm.reset();
        })
        .catch(error => {
            console.error("Error blocking IPs:", error);
            alert("Failed to block IPs. Please try again.");
        });
    });

    // Handle URL blocking form submission
    urlForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = new FormData(urlForm); // Collect form data

        fetch("block_addresses.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.text()) // Read response text
        .then(data => {
            alert("URLs blocked successfully.");
            console.log(data); // Optional: Show response for debugging
            // Optionally update the page without reloading

            // Update the URL list dynamically
            const urlList = document.getElementById("url-list");
            const newUrls = formData.get("urls").split(",").map(url => url.trim()).filter(url => url);
            const username = "<?= $_SESSION['username'] ?? 'unknown_user' ?>";
            newUrls.forEach(url => {
                const newRow = document.createElement("tr");
                newRow.id = `url-row-${url}`;
                newRow.innerHTML = `
                    <td><input type='text' value='${url}' disabled></td>
                    <td><input type='text' value='${username}' disabled></td>
                    <td><button onclick='deleteUrl("${url}")'>Delete</button></td>
                `;
                urlList.appendChild(newRow);
            });

            // Handle file upload
            const urlFile = formData.get("url_file");
            if (urlFile && urlFile.size > 0) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const fileContent = e.target.result;
                    const urlsFromFile = fileContent.split(/\r?\n/).map(url => url.trim()).filter(url => url);
                    
                    urlsFromFile.forEach(url => {
                        const newRow = document.createElement("tr");
                        newRow.id = `url-row-${url}`;
                        newRow.innerHTML = `
                            <td><input type='text' value='${url}' disabled></td>
                            <td><input type='text' value='${username}' disabled></td>
                            <td><button onclick='deleteUrl("${url}")'>Delete</button></td>
                        `;
                        urlList.appendChild(newRow);
                    });
                };
                reader.readAsText(urlFile);
            }

            urlForm.reset();

        })
        .catch(error => {
            console.error("Error blocking URLs:", error);
            alert("Failed to block URLs. Please try again.");
        });
    });
    });

</script>

<?php if (!empty($message)): ?>
        <p class="<?= strpos($message, 'successful') !== false ? 'message' : 'error' ?>"><?= $message ?></p>
    <?php endif; ?>
    

</body>
</html>
