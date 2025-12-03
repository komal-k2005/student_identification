<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$con = mysqli_connect('localhost','root','','user');

// Check connection and display detailed errors
if(!$con){
    die("<div style='background: #f8d7da; color: #721c24; padding: 20px; margin: 20px; border: 1px solid #f5c6cb; border-radius: 5px;'>
        <h3>❌ Database Connection Failed!</h3>
        <p><strong>Error:</strong> " . mysqli_connect_error() . "</p>
        <p><strong>Error Number:</strong> " . mysqli_connect_errno() . "</p>
        <p><strong>Please check:</strong></p>
        <ul>
            <li>MySQL service is running (XAMPP Control Panel)</li>
            <li>Database name 'user' exists in phpMyAdmin</li>
            <li>Username and password are correct</li>
            <li>SQL files have been imported successfully</li>
        </ul>
        <p><strong>To fix:</strong></p>
        <ol>
            <li>Open XAMPP Control Panel</li>
            <li>Start MySQL service</li>
            <li>Open phpMyAdmin (http://localhost/phpmyadmin)</li>
            <li>Create database 'user' if it doesn't exist</li>
            <li>Import all SQL files from sql/ folder</li>
        </ol>
    </div>");
}

// Set charset to utf8mb4
mysqli_set_charset($con, "utf8mb4");

?>
