<?php
session_start();
include 'db.php'; // Include your database connection

// Check if the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all users from the database
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 w-64 p-6 text-white">
            <h3 class="text-2xl font-semibold text-center mb-6">Admin Panel</h3>
            <ul>
                <li class="mb-4"><a href="admin_dashboard.php" class="hover:text-gray-400">Manage Users</a></li>
                <li class="mb-4"><a href="manage_posts.php" class="hover:text-gray-400">Manage Posts</a></li>
                <li class="mb-4"><a href="settings.php" class="hover:text-gray-400">Settings</a></li>
                <li class="mb-4"><a href="logout.php" class="hover:text-red-400">Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-3xl font-semibold text-gray-800 mb-6">Manage Users</h1>

            <!-- Create User Form -->
            <h2 class="text-xl font-semibold mb-4">Create User</h2>
            <form method="POST" action="user_operations.php">
                <input type="text" name="username" placeholder="Username" required class="border p-2 rounded mb-2">
                <input type="password" name="password" placeholder="Password" required class="border p-2 rounded mb-2">
                <select name="role" required class="border p-2 rounded mb-2">
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="viewer">Viewer</option>
                </select>
                <input type="hidden" name="action" value="create">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create User</button>
            </form>

            <!-- User Table -->
            <h2 class="text-xl font-semibold mt-8 mb-4">All Users</h2>
            <table class="table-auto w-full bg-white rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="px-4 py-2">Username</th>
                        <th class="px-4 py-2">Role</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="border px-4 py-2"><?= $user['username'] ?></td>
                        <td class="border px-4 py-2"><?= $user['role'] ?></td>
                        <td class="border px-4 py-2">
                            <!-- Update User Button -->
                            <form method="POST" action="user_operations.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded-md">Delete</button>
                            </form>
                            <a href="update_user.php?id=<?= $user['id'] ?>" class="bg-yellow-500 text-white px-2 py-1 rounded-md">Update</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
