<?php
include 'db.php';
session_start();

// Only admin users can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch user data by ID
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

// Update user form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $id = $_POST['id'];

    // Update the user details in the database
    try {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
        $stmt->execute([$username, $role, $id]);
        header("Location: admin_dashboard.php?message=User updated successfully");
    } catch (PDOException $e) {
        echo "Error updating user: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto p-8 bg-white mt-8 rounded-lg shadow-lg">
        <h1 class="text-2xl font-semibold mb-6">Update User</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <input type="text" name="username" value="<?= $user['username'] ?>" required class="border p-2 rounded mb-2">
            <select name="role" class="border p-2 rounded mb-2">
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="editor" <?= $user['role'] == 'editor' ? 'selected' : '' ?>>Editor</option>
                <option value="viewer" <?= $user['role'] == 'viewer' ? 'selected' : '' ?>>Viewer</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update User</button>
        </form>
    </div>
</body>
</html>
