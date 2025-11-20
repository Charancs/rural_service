<?php
// Simple script to test database connection

require_once 'config.php';

echo "<html><head><title>Database Connection Test</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f0f0f0;}";
echo ".success{background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:15px;border-radius:5px;margin:10px 0;}";
echo ".error{background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:15px;border-radius:5px;margin:10px 0;}";
echo ".info{background:#d1ecf1;border:1px solid #bee5eb;color:#0c5460;padding:15px;border-radius:5px;margin:10px 0;}";
echo "</style></head><body>";

echo "<h1>🔌 Database Connection Test</h1>";

echo "<div class='info'>";
echo "<strong>Configuration:</strong><br>";
echo "Host: " . DB_HOST . "<br>";
echo "Port: " . DB_PORT . "<br>";
echo "Database: " . DB_NAME . "<br>";
echo "Username: " . DB_USER . "<br>";
echo "</div>";

try {
    echo "<div class='info'>Attempting to connect...</div>";
    
    $conn = getDBConnection();
    
    echo "<div class='success'>";
    echo "<strong>✅ Connection Successful!</strong><br>";
    echo "Connected to database: " . DB_NAME . "<br>";
    echo "MySQL Version: " . $conn->server_info . "<br>";
    echo "</div>";
    
    // Test if tables exist
    echo "<div class='info'><strong>Checking tables...</strong></div>";
    
    $tables = ['users', 'pan_applications', 'pan_verifications', 'recharge_transactions', 'user_sessions'];
    $existingTables = [];
    $missingTables = [];
    
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            $existingTables[] = $table;
        } else {
            $missingTables[] = $table;
        }
    }
    
    if (count($existingTables) > 0) {
        echo "<div class='success'>";
        echo "<strong>✅ Existing Tables:</strong><br>";
        echo implode(", ", $existingTables);
        echo "</div>";
    }
    
    if (count($missingTables) > 0) {
        echo "<div class='error'>";
        echo "<strong>⚠️ Missing Tables:</strong><br>";
        echo implode(", ", $missingTables) . "<br><br>";
        echo "Please run the SQL script from DATABASE_SETUP.md to create these tables.";
        echo "</div>";
    } else {
        echo "<div class='success'><strong>🎉 All tables are present! Your database is ready to use.</strong></div>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<strong>❌ Connection Failed!</strong><br>";
    echo "Error: " . $e->getMessage() . "<br><br>";
    echo "<strong>Troubleshooting:</strong><br>";
    echo "1. Check if your database credentials in php/config.php are correct<br>";
    echo "2. Verify your database server allows remote connections<br>";
    echo "3. Check if the database name exists<br>";
    echo "4. Ensure your firewall allows MySQL port (3306)<br>";
    echo "</div>";
}

echo "<br><a href='../landing.html' style='display:inline-block;padding:10px 20px;background:#667eea;color:white;text-decoration:none;border-radius:5px;'>← Back to Landing Page</a>";
echo "</body></html>";
?>
