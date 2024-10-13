<?php
include 'db.php'; // Database connection

// Hardcoded user details (for testing)
$username = 'pooja'; 
$password = 'poo123';  // Plaintext password
$role = 'editor';            // Role

// Hash the password before saving it in the database
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the user into the database
try {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashed_password, $role]);

    // Display success message
    echo "User '$username' created successfully!";
} catch (PDOException $e) {
    // If there is an error inserting the user
    echo "Error creating user: " . $e->getMessage();
}
?>
