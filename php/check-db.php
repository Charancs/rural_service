<?php
// Check MySQL connection and list databases
$host = 'localhost';
$port = '3307';
$user = 'root';
$pass = '';

echo "<h3>Testing MySQL Connection on Port 3307</h3>";

// Try to connect without selecting a database
try {
    $conn = @new mysqli($host, $user, $pass, '', $port);
} catch (Exception $e) {
    $conn = null;
}

if (!$conn || $conn->connect_error) {
    echo "<p style='color:red'>Connection failed: " . $conn->connect_error . "</p>";
    echo "<p>Error Code: " . $conn->connect_errno . "</p>";
    
    // Try default port 3306
    echo "<br><h3>Trying Port 3306 instead...</h3>";
    try {
        $conn2 = @new mysqli($host, $user, $pass, '', 3306);
    } catch (Exception $e) {
        $conn2 = null;
    }
    if (!$conn2 || $conn2->connect_error) {
        echo "<p style='color:red'>Port 3306 also failed: " . $conn2->connect_error . "</p>";
    } else {
        echo "<p style='color:green'>Connected successfully on port 3306!</p>";
        echo "<h4>Available Databases:</h4><ul>";
        $result = $conn2->query("SHOW DATABASES");
        while($row = $result->fetch_assoc()) {
            echo "<li>" . $row['Database'] . "</li>";
        }
        echo "</ul>";
        $conn2->close();
    }
} else {
    echo "<p style='color:green'>Connected successfully to MySQL on port 3307!</p>";
    echo "<p>MySQL Version: " . $conn->server_info . "</p>";
    
    echo "<h4>Available Databases:</h4><ul>";
    $result = $conn->query("SHOW DATABASES");
    while($row = $result->fetch_assoc()) {
        echo "<li>" . $row['Database'] . "</li>";
    }
    echo "</ul>";
    
    // Check if gsk_services exists
    $db_check = $conn->query("SHOW DATABASES LIKE 'gsk_services'");
    if ($db_check->num_rows > 0) {
        echo "<p style='color:green'>✓ Database 'gsk_services' exists!</p>";
    } else {
        echo "<p style='color:orange'>✗ Database 'gsk_services' does NOT exist. You need to create it.</p>";
    }
    
    $conn->close();
}
?>
