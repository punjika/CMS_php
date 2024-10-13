<?php
include 'db.php'; // Database connection
session_start();

// Only admin users can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'create') {
        // Create user
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $role]);
            header("Location: admin_dashboard.php?message=User created successfully");
        } catch (PDOException $e) {
            echo "Error creating user: " . $e->getMessage();
        }

    } elseif ($action === 'delete') {
        // Delete user
        $id = $_POST['id'];

        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: admin_dashboard.php?message=User deleted successfully");
        } catch (PDOException $e) {
            echo "Error deleting user: " . $e->getMessage();
        }

    }
}
?>
