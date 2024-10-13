<?php
include 'db.php'; // Include the database connection
session_start();

// Only proceed if the user is a viewer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'viewer') {
    header("Location: login.php");
    exit();
}

try {
    // Fetch all posts with the corresponding author's username
    $stmt = $pdo->prepare("
        SELECT posts.id, posts.title, posts.content, users.username 
        FROM posts 
        JOIN users ON posts.author_id = users.id
    ");
    $stmt->execute();
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error fetching posts: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Posts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto mt-8 p-4 bg-white rounded shadow-lg">
        <h1 class="text-3xl font-bold mb-6">All Posts</h1>
        <?php if (!empty($posts)): ?>
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Title</th>
                        <th class="px-4 py-2">Content</th>
                        <th class="px-4 py-2">Author</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                    <tr class="bg-gray-100 hover:bg-gray-50">
                        <td class="border px-4 py-2"><?= htmlspecialchars($post['id']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($post['title']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($post['content']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($post['username']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-gray-600">No posts available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
