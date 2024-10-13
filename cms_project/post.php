<?php
include 'db.php'; // Include your database connection file
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Logged-in user's ID

// Fetch the posts made by the logged-in user
$stmt = $pdo->prepare("SELECT * FROM posts WHERE author_id = ?");
$stmt->execute([$user_id]);
$user_posts = $stmt->fetchAll();

// Fetch all other users' posts with their usernames
$stmt_other_posts = $pdo->prepare("
    SELECT posts.*, users.username 
    FROM posts 
    JOIN users ON posts.author_id = users.id 
    WHERE posts.author_id != ?
");
$stmt_other_posts->execute([$user_id]);
$other_posts = $stmt_other_posts->fetchAll();

// Handle new post creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_post'])) {
    $post_content = $_POST['post_content'];
    $post_title = $_POST['post_title'];
    
    if (!empty($post_content) && !empty($post_title)) {
        $stmt_insert = $pdo->prepare("INSERT INTO posts (author_id, title, content) VALUES (?, ?, ?)");
        $stmt_insert->execute([$user_id, $post_title, $post_content]);
        header("Location: post.php"); // Reload the page after posting
        exit();
    } else {
        echo "Post title and content cannot be empty!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Posts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto py-10">
        <!-- Logout Button -->
        <div class="text-right mb-6">
            <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-all">Logout</a>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-6">Your Posts</h1>
        
        <!-- Display user's posts -->
        <div class="mb-6">
            <?php if ($user_posts): ?>
                <h2 class="text-2xl text-gray-700 mb-4">Your Previous Posts</h2>
                <?php foreach ($user_posts as $post): ?>
                    <div class="bg-white p-4 rounded-lg shadow mb-4">
                        <h3 class="font-bold"><?= htmlspecialchars($post['title']) ?></h3>
                        <p><?= htmlspecialchars($post['content']) ?></p>
                        <small class="text-gray-500">Posted on: <?= $post['created_at'] ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-600">You haven't made any posts yet.</p>
            <?php endif; ?>
        </div>

        <!-- Form to create a new post -->
        <h2 class="text-2xl text-gray-700 mb-4">Create a New Post</h2>
        <form method="POST" class="mb-6">
            <input type="text" name="post_title" placeholder="Post Title" class="w-full p-3 rounded border border-gray-300 mb-4" required>
            <textarea name="post_content" placeholder="What's on your mind?" class="w-full p-3 rounded border border-gray-300 mb-4" required></textarea>
            <button type="submit" name="new_post" class="bg-blue-500 text-white px-4 py-2 rounded">Post</button>
        </form>

        <!-- Display other users' posts -->
        <h2 class="text-2xl text-gray-700 mb-4">Other Users' Posts</h2>
        <div>
            <?php if ($other_posts): ?>
                <?php foreach ($other_posts as $post): ?>
                    <div class="bg-white p-4 rounded-lg shadow mb-4">
                        <h3 class="font-bold"><?= htmlspecialchars($post['title']) ?></h3>
                        <p><?= htmlspecialchars($post['content']) ?></p>
                        <small class="text-gray-500">Posted by: <?= htmlspecialchars($post['username']) ?> on <?= $post['created_at'] ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-600">No posts from other users yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
